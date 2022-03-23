<?php


namespace App\Service\Import;


use App\Entity\Club;
use App\Entity\Fixture;
use App\Entity\FootballApiManager;
use App\Entity\League;
use App\Entity\Round;
use App\Entity\Season;
use App\Service\Api\FootballApiGateway;
use App\Service\Api\SportsmonkApiGateway;
use App\Service\Round\RoundData;
use App\Service\Round\RoundService;
use App\Service\Setting\FootballApiManagerService;
use App\Service\Api\Response\FixtureResponse;
use App\Service\Club\ClubData;
use App\Service\Club\ClubService;
use App\Service\Fixture\FixtureData;
use App\Service\Fixture\FixtureService;
use App\Service\FixtureOdd\FixtureOddData;
use App\Service\FixtureOdd\FixtureOddService;
use App\Service\League\LeagueData;
use App\Service\League\LeagueService;
use App\Service\Season\SeasonData;
use App\Service\Season\SeasonService;
use App\Service\Seeding\SeedingData;
use App\Service\Seeding\SeedingService;
use DateTime;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use function PHPUnit\Framework\isNull;

class UpdateService
{

    public const API_KEY_BUNDESLIGA = 78;
    public const API_KEY_BUNDESLIGA_2 = 79;
    public const API_KEY_PREMIER_LEAGUE = 39;
    public const API_KEY_CHAMPIONS_CHIP = 40;
    public const API_KEY_PRIMERA_DIVISION= 140;
    public const API_KEY_SECUNDA_DIVISION= 141;
    public const API_KEY_SERIE_A= 135;
    public const API_KEY_SERIE_B= 136;
    public const API_KEY_LEAGUE_A= 61;
    public const API_KEY_LEAGUE_B= 62;
    public const API_KEY_DEN_SUPERLIGA= 119;
    public const API_KEY_SCT_PREMIERSHIP= 179;
    public const LEAGUE_BUNDESLIGA = 'bundesliga';
    public const LEAGUE_BUNDESLIGA_2 = 'bundesliga2';
    public const LEAGUE_PREMIER_LEAGUE = 'premierLeague';
    public const LEAGUE_CHAMPIONS_CHIP = 'championsChip';
    public const LEAGUE_PRIMERA_DIVISION= 'primeraDivision';
    public const LEAGUE_SECUNDA_DIVISION= 'secundaDivision';
    public const LEAGUE_SERIE_A= 'serieA';
    public const LEAGUE_SERIE_B= 'serieB';
    public const LEAGUE_LEAGUE_A= 'leagueA';
    public const LEAGUE_LEAGUE_B= 'leagueB';
    public const LEAGUE_DEN_SUPERLIGA= 'superLiga';
    public const LEAGUE_SCT_PREMIERSHIP= 'premierShip';
    public const ROUND_BUNDESLIGA = 9;
    public const ROUND_BUNDESLIGA_2 = 9;
    public const ROUND_PREMIER_LEAGUE = 10;
    public const ROUND_CHAMPIONS_CHIP = 12;
    public const ROUND_PRIMERA_DIVISION= 10;
    public const ROUND_SECUNDA_DIVISION= 11;
    public const ROUND_SERIE_A= 10;
    public const ROUND_SERIE_B= 10;
    public const ROUND_LEAGUE_A= 10;
    public const ROUND_LEAGUE_B= 10;

    private const SPORTSMONK_API_KEY_DEN_SUPERLIGA = 271;
    private const SPORTSMONK_API_KEY_SCT_PREMIERSHIP = 501;

    public const SPORTSMONK_LEAGUES = [
        self::LEAGUE_DEN_SUPERLIGA => self::SPORTSMONK_API_KEY_DEN_SUPERLIGA,
        self::LEAGUE_SCT_PREMIERSHIP => self::SPORTSMONK_API_KEY_SCT_PREMIERSHIP,
    ];

    public const LEAGUES = [
        self::LEAGUE_BUNDESLIGA => self::API_KEY_BUNDESLIGA,
        self::LEAGUE_BUNDESLIGA_2 => self::API_KEY_BUNDESLIGA_2,
        self::LEAGUE_PREMIER_LEAGUE => self::API_KEY_PREMIER_LEAGUE,
        self::LEAGUE_CHAMPIONS_CHIP => self::API_KEY_CHAMPIONS_CHIP,
        self::LEAGUE_PRIMERA_DIVISION => self::API_KEY_PRIMERA_DIVISION,
        self::LEAGUE_SECUNDA_DIVISION => self::API_KEY_SECUNDA_DIVISION,
        self::LEAGUE_SERIE_A => self::API_KEY_SERIE_A,
        self::LEAGUE_SERIE_B => self::API_KEY_SERIE_B,
        self::LEAGUE_LEAGUE_A => self::API_KEY_LEAGUE_A,
        self::LEAGUE_LEAGUE_B => self::API_KEY_LEAGUE_B,
    ];

    public const ROUNDS = [
        self::LEAGUE_BUNDESLIGA => self::ROUND_BUNDESLIGA,
        self::LEAGUE_BUNDESLIGA_2 => self::ROUND_BUNDESLIGA_2,
        self::LEAGUE_PREMIER_LEAGUE => self::ROUND_PREMIER_LEAGUE,
        self::LEAGUE_CHAMPIONS_CHIP => self::ROUND_CHAMPIONS_CHIP,
        self::LEAGUE_PRIMERA_DIVISION => self::ROUND_PRIMERA_DIVISION,
        self::LEAGUE_SECUNDA_DIVISION => self::ROUND_SECUNDA_DIVISION,
        self::LEAGUE_SERIE_A => self::ROUND_SERIE_A,
        self::LEAGUE_SERIE_B => self::ROUND_SERIE_B,
        self::LEAGUE_LEAGUE_A => self::ROUND_LEAGUE_A,
        self::LEAGUE_LEAGUE_B => self::ROUND_LEAGUE_B,
    ];


    /**
     * @var FootballApiGateway
     */
    private $footballApiGateway;

    /**
     * @var ClubService
     */
    private $clubService;

    /**
     * @var LeagueService
     */
    private $leagueService;

    /**
     * @var SeasonService
     */
    private $seasonService;

    /**
     * @var SeedingService
     */
    private $seedingService;

    /**
     * @var FixtureService
     */
    private $fixtureService;

    /**
     * @var FixtureOddService
     */
    private $fixtureOddService;

    /**
     * @var RoundService
     */
    private $roundService;

    /**
     * @var FootballApiManagerService
     */
    private $footballApiManagerService;

    /**
     * @var SportsmonkApiGateway
     */
    private $sportsmonkApiGateway;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * UpdateService constructor.
     * @param FootballApiGateway $footballApiGateway
     * @param ClubService $clubService
     * @param LeagueService $leagueService
     * @param SeasonService $seasonService
     * @param SeedingService $seedingService
     * @param FixtureService $fixtureService
     * @param FixtureOddService $fixtureOddService
     * @param RoundService $roundService
     * @param FootballApiManagerService $footballApiManagerService
     * @param LoggerInterface $autoUpdateLogger
     */
    public function __construct(
        FootballApiGateway $footballApiGateway,
        SportsmonkApiGateway $sportsmonkApiGateway,
        ClubService $clubService,
        LeagueService $leagueService,
        SeasonService $seasonService,
        SeedingService $seedingService,
        FixtureService $fixtureService,
        FixtureOddService $fixtureOddService,
        RoundService $roundService,
        FootballApiManagerService $footballApiManagerService,
        LoggerInterface $autoUpdateLogger
    )
    {
        $this->footballApiGateway = $footballApiGateway;
        $this->sportsmonkApiGateway = $sportsmonkApiGateway;
        $this->clubService = $clubService;
        $this->leagueService = $leagueService;
        $this->seasonService = $seasonService;
        $this->seedingService = $seedingService;
        $this->fixtureService = $fixtureService;
        $this->fixtureOddService = $fixtureOddService;
        $this->roundService = $roundService;
        $this->footballApiManagerService = $footballApiManagerService;
        $this->logger = $autoUpdateLogger;
    }

    public function updateLeagues(){
        foreach (self::LEAGUES as $leagueName => $apiKey){
            $this->updateLeague($leagueName, $apiKey, 2021);
        }
    }

    public function updateLeague(string $leagueIdent, int $leagueApiKey, int $startYear)
    {
        if ($this->footballApiManagerService->isApiCallLimitReached()){
            return;
        }
        $this->logger->info("UPDATE league: ".$leagueIdent);

        $standings = $this->footballApiGateway->getStandingForLeagues($leagueApiKey, $startYear);

        $league = $this->leagueService->findByApiKey($leagueApiKey);
        if (is_null($league)){
            $league = $this->leagueService->findByIdent($leagueApiKey);
        }
        if (is_null($league)){
            $leagueData = new LeagueData();
            $leagueData->setIdent($leagueIdent);
            $leagueData->setApiId($standings->getLeagueId());
            $leagueData->setSportsmonkApiId(-1);
            $league = $this->leagueService->createByData($leagueData);
        }

        $clubs = array();
        $seedingDataSets = array();
        foreach ($standings->getClubStandings() as $clubStanding){
            $club = $this->clubService->findByApiKey($clubStanding->getClubId());
            if (is_null($club)){
                $clubData = new ClubData();
                $clubData->setLeague($league);
                $clubData->setName($clubStanding->getClubName());
                $clubData->setApiId($clubStanding->getClubId());
                $clubData->setForm($clubStanding->getForm());
                $clubData->setFormRound($clubStanding->getRound());
                $clubData->setSportsmonkApiId(-1);
                $club = $this->clubService->createByData($clubData);
            }else{
                $clubData = (new ClubData())->initFrom($club);
                $clubData->setForm($clubStanding->getForm());
                $club = $this->clubService->updateClub($club, $clubData);
            }

            // store an seeding entry for each club
            $seedingData = new SeedingData();
            $seedingData->setWins($clubStanding->getWins());
            $seedingData->setDraws($clubStanding->getDraws());
            $seedingData->setLooses($clubStanding->getLooses());
            $seedingData->setClub($club);
            $seedingData->setGoals($clubStanding->getGoals());
            $seedingData->setAgainstGoals($clubStanding->getAgainstGoals());
            $seedingData->setPoints($clubStanding->getPoints());
            $seedingData->setPosition($clubStanding->getRank());
            $seedingData->setForm($clubStanding->getForm());
            $seedingData->setRound($clubStanding->getRound());
            $clubs[] = $club;
            $seedingDataSets[] = $seedingData;
        }

        $season = $this->seasonService->findByYears($startYear, $startYear+1, $league);
        if (is_null($season)){
            $seasonData = new SeasonData();
            $seasonData->setLeague($league);
            $seasonData->setClubs($clubs);
            $seasonData->setStartYear($standings->getSeasonStartYear());
            $seasonData->setEndYear($standings->getSeasonStartYear()+1);
            $seasonData->setSportsmonkApiId(-1);
            $season = $this->seasonService->createByData($seasonData);
        }

        foreach ($seedingDataSets as $seedingData){
            $seedingData->setSeason($season);
            $this->seedingService->createByData($seedingData);
        }
    }

    public function getFixturesForCurrentRounds(){
        foreach (self::LEAGUES as $leagueName => $apiKey){
            $this->getFixtureForCurrentRound($apiKey, 2021);
        }
    }

    private function getFixtureForCurrentRound(int $apiLeagueKey, int $seasonYear)
    {
        if ($this->footballApiManagerService->isApiCallLimitReached()){
            return;
        }
        $fixturesResponses = $this->footballApiGateway->getNextFixturesForLeagueAndCurrentRound($apiLeagueKey, $seasonYear);
        $this->storeFixtureResponseToDatabase($apiLeagueKey, $seasonYear, $fixturesResponses);
    }

    public function storeFixtureForLeagueAndRound(int $apiLeagueKey, int $seasonYear, int $round): bool
    {
        if ($this->footballApiManagerService->isApiCallLimitReached()){
            return false;
        }
        $fixturesResponses = $this->footballApiGateway->getNextFixturesForLeagueAndRound($apiLeagueKey, $seasonYear, $round);
        $this->storeFixtureResponseToDatabase($apiLeagueKey, $seasonYear, $fixturesResponses);
        return true;
    }

    public function storeOddsForFixture(int $fixtureApiId): bool
    {
        if ($this->footballApiManagerService->isApiCallLimitReached()){
            return false;
        }
        $fixtureResponses = $this->footballApiGateway->getOddsForFixture($fixtureApiId);
        foreach ($fixtureResponses as $fixtureResponse){
            $oddData = new FixtureOddData();
            // get Fixture for odd
            $fixture = $this->fixtureService->findByApiKey($fixtureResponse->getFixtureApiId());
            $oddData->setFixture($fixture);
            $oddData->setType($fixtureResponse->getType());
            $oddData->setProvider($fixtureResponse->getProvider());
            $oddData->setHomeOdd($fixtureResponse->getHomeOdd());
            $oddData->setDrawOdd($fixtureResponse->getDrawOdd());
            $oddData->setAwayOdd($fixtureResponse->getAwayOdd());
            $this->fixtureOddService->createByData($oddData);

            // update oddtime in fixture
            $fixtureUpdateDate = (new FixtureData())->initFrom($fixture);
            $fixtureUpdateDate->setOddDecorationDate(new DateTime());
            $this->fixtureService->updateFixture($fixture, $fixtureUpdateDate);
        }
        if (empty($fixtureResponses) || !count($fixtureResponses)){
            $this->logger->info(sprintf('Error during bet collection: EmptyResponse=>%d CountResponse=>%d', (empty($fixtureResponses)), !count($fixtureResponses)));
            return false;
        }
        return true;
    }

    /**
     * @param int $apiLeagueKey
     * @param int $seasonYear
     * @param $fixturesResponses
     * @throws \Doctrine\ORM\ORMException
     */
    private function storeFixtureResponseToDatabase(int $apiLeagueKey, int $seasonYear, $fixturesResponses): void
    {
        foreach ($fixturesResponses as $fixtureResponse) {
            $fixtureData = new FixtureData();
            $fixtureData->setApiId($fixtureResponse->getApiId());
            $fixtureData->setTimeStamp($fixtureResponse->getTimeStamp());
            $fixtureData->setDate($fixtureResponse->getDate());
            // parse round string
            $parsedRoundString = explode(' - ', $fixtureResponse->getRound());
            $fixtureData->setMatchDay($parsedRoundString[1]);
            // find Teams
            $homeClub = $this->clubService->findByApiKey($fixtureResponse->getHomeTeamApiId());

            $fixtureData->setHomeTeam($homeClub);
            $awayClub = $this->clubService->findByApiKey($fixtureResponse->getAwayTeamApiId());
            $fixtureData->setAwayTeam($awayClub);
            $fixtureData->setScoreHomeFull($fixtureResponse->getScoreHomeFullTime());
            $fixtureData->setScoreHomeHalf($fixtureResponse->getScoreHomeHalfTime());
            $fixtureData->setScoreAwayFull($fixtureResponse->getScoreAwayFullTime());
            $fixtureData->setScoreAwayHalf($fixtureResponse->getScoreAwayHalfTime());
            //search season
            $league = $this->leagueService->findByApiKey($apiLeagueKey);
            $season = $this->seasonService->findByYears($seasonYear, $seasonYear + 1, $league);
            $fixtureData->setLeague($league);
            $fixtureData->setSeason($season);
            $fixtureData->setIsDoubleChanceCandidate(false);
            $fixtureData->setIsBetDecorated(false);
            $fixtureData->setPlayed(false);
            $this->fixtureService->createByData($fixtureData);
        }
    }

    public function getCurrentRoundForAllLeagues():array
    {
        $currentRounds = array();
        foreach (self::LEAGUES as $leagueName => $apiKey){
            if ($this->footballApiManagerService->isApiCallLimitReached()){
                return [];
            }
            $response = $this->footballApiGateway->getCurrentRoundForLeague($apiKey, 2021);
            $response = json_decode($response->getBody(), true);
            $parsedRoundString = explode(' - ', $response['response'][count($response['response'])-1]);
            dump($parsedRoundString);
            $currentRounds[$leagueName] = $parsedRoundString[1];
        }
        return $currentRounds;
    }

    public function getCurrentRoundForLeague(string $leagueIdent): ?int
    {
        $apiKey = self::LEAGUES[$leagueIdent];
        if ($this->footballApiManagerService->isApiCallLimitReached()){
            return null;
        }
        $response = $this->footballApiGateway->getCurrentRoundForLeague($apiKey, 2021);
        $response = json_decode($response->getBody(), true);
        $parsedRoundString = explode(' - ', $response['response'][count($response['response'])-1]);
        return $parsedRoundString[1];
    }

    public function updateFixtureForLeagueAndRound(int $apiLeagueKey, int $seasonYear, $round)
    {
        if ($this->footballApiManagerService->isApiCallLimitReached()){
            return false;
        }
        $fixturesResponses = $this->footballApiGateway->getNextFixturesForLeagueAndRound($apiLeagueKey, $seasonYear, $round);
        $this->updateFixtureResponseToDatabase($fixturesResponses);
        return true;
    }

    private function updateFixtureResponseToDatabase(array $fixturesResponses)
    {
        foreach ($fixturesResponses as $fixtureResponse) {
            /** @var FixtureResponse $fixtureResponse */
            $fixtureToUpdate = $this->fixtureService->findByApiKey($fixtureResponse->getApiId());
            if (isNull($fixtureToUpdate)){
                dump($fixtureResponse);
                continue;
            }
            $fixtureData = (new FixtureData())->initFrom($fixtureToUpdate);
            $fixtureData->setScoreHomeFull($fixtureResponse->getScoreHomeFullTime());
            $fixtureData->setScoreHomeHalf($fixtureResponse->getScoreHomeHalfTime());
            $fixtureData->setScoreAwayFull($fixtureResponse->getScoreAwayFullTime());
            $fixtureData->setScoreAwayHalf($fixtureResponse->getScoreAwayHalfTime());
            $this->fixtureService->updateFixture($fixtureToUpdate, $fixtureData);
        }
    }

    /**
     * @param Fixture $fixture
     * @return bool
     */
    public function checkIfFixtureHaveSeedings(Fixture $fixture): bool
    {
        $homeSeeding = $this->seedingService->findByClubAndSeasonAndLRound($fixture->getHomeTeam(), $fixture->getSeason(), $fixture->getMatchDay());
        $awaySeeding = $this->seedingService->findByClubAndSeasonAndLRound($fixture->getAwayTeam(), $fixture->getSeason(), $fixture->getMatchDay());

        return !is_null($homeSeeding) && !is_null($awaySeeding);
    }

    /**
     * @param Fixture $fixture
     * @return void
     */
    public function updateSeedingFormsForFixture(Fixture $fixture): void
    {
        // got current form
        $season = $fixture->getSeason();

//        $form = 'WLWWDLLLWWDDLWLWLLWLW';
        $homeTeam = $fixture->getHomeTeam();
        $homeForm =  $this->footballApiGateway->getCurrentFormForClub($fixture->getLeague()->getApiId(), $season->getStartYear(), $homeTeam->getApiId());
        $this->storeFormsTillCurrentRound($homeForm, $homeTeam, $season);

        $awayTeam = $fixture->getHomeTeam();
        $awayForm =  $this->footballApiGateway->getCurrentFormForClub($fixture->getLeague()->getApiId(), $season->getStartYear(), $awayTeam->getApiId());
        $this->storeFormsTillCurrentRound($awayForm, $awayTeam, $season);
    }

    function getSeedingsForClubTillCurrentRound(League $league, int $startYear, Club $club): bool
    {
        $season = $this->seasonService->findByLeagueAndStartYear($league, $startYear);

        $lastSeeding = $this->seedingService->findLastSeedingForClubAndSeason($club, $season);

        if (is_null($lastSeeding)){
            $form =  $this->footballApiGateway->getCurrentFormForClub($league->getApiId(), $startYear, $club->getApiId());
            $roundNr = strlen($form);
            $this->logger->info(sprintf("Get form for %s fitting last %d round: %s", $club->getName(), $roundNr, $form));
            $this->storeFormsTillCurrentRound($form, $club, $season);
            return true;
        }
        return false;
    }

    /**
     * @param array $formTillRound
     * @param string $variant
     * @return int
     */
    private function getNumberOfMatchEndings(array $formTillRound, string $variant)
    {
        $counter = 0;
        foreach ($formTillRound as $round){
            if ($round === $variant){
                $counter++;
            }
        }
        return $counter;
    }

    /**
     * @param string $form
     * @param Club $team
     * @param Season $season
     */
    public function storeFormsTillCurrentRound(string $form, Club $team, Season $season): void
    {
        for ($roundNr = 1; $roundNr <= strlen($form); $roundNr++) {
            $currentForm = substr($form, 0, $roundNr);
            $seeding = $this->seedingService->findByClubAndSeasonAndLRound($team, $season, $roundNr);
            if (is_null($seeding)) {
                $formArray = str_split($currentForm);

                $seedingData = new SeedingData();
                $seedingData->setRound($roundNr);
                $seedingData->setSeason($season);
                $seedingData->setWins($this->getNumberOfMatchEndings($formArray, 'W'));
                $seedingData->setLooses($this->getNumberOfMatchEndings($formArray, 'L'));
                $seedingData->setDraws($this->getNumberOfMatchEndings($formArray, 'D'));
                $seedingData->setGoals(-1);
                $seedingData->setAgainstGoals(-1);
                $seedingData->setClub($team);
                $seedingData->setPoints(-1);
                $seedingData->setPosition(-1);

                $seedingData->setForm($currentForm);

                $this->seedingService->createByData($seedingData);

                $this->logger->info(sprintf("created seeding for %s and round %d: %s", $team->getName(), $roundNr, $currentForm));
            }
        }
    }

    /**
     * @param int $leagueApiIdent
     * @param int $seasonStartYear
     * @param int $round
     * @return bool
     */
    public function getFixturesForRound(int $leagueApiIdent, int $seasonStartYear, int $round): bool
    {
        if ($this->footballApiManagerService->isApiCallLimitReached()){
            return false;
        }

        $fixtures = $this->footballApiGateway->getRoundForLeague($leagueApiIdent, $seasonStartYear, $round);
        $this->logger->info(sprintf("Got fixture results for round %d of league %d", $round, $leagueApiIdent));
        dump($fixtures);
        foreach ($fixtures as $fixture){
            $fixtureToUpdate = $this->fixtureService->findByApiKey($fixture->getFixtureApiId());

            // store new if not exist
            if (is_null($fixtureToUpdate)){
                $fixtureData = new FixtureData();
                $fixtureData->setApiId($fixture->getFixtureApiId());
                $fixtureData->setTimeStamp($fixture->getTimeStamp());
                $fixtureData->setDate($fixture->getDate());
                // parse round string
                $fixtureData->setMatchDay($round);
                // find Teams
                $homeClub = $this->clubService->findByApiKey($fixture->getHomeTeamApiId());

                $fixtureData->setHomeTeam($homeClub);
                $awayClub = $this->clubService->findByApiKey($fixture->getAwayTeamApiId());
                $fixtureData->setAwayTeam($awayClub);
                $fixtureData->setScoreHomeFull($fixture->getHomeFull());
                $fixtureData->setScoreHomeHalf($fixture->getHomeHalf());
                $fixtureData->setScoreAwayFull($fixture->getAwayFull());
                $fixtureData->setScoreAwayHalf($fixture->getAwayHalf());
                //search season
                $league = $this->leagueService->findByApiKey($fixture->getLeagueApiId());
                $season = $this->seasonService->findByYears($seasonStartYear, $seasonStartYear + 1, $league);
                $fixtureData->setLeague($league);
                $fixtureData->setSeason($season);
                $fixtureData->setIsDoubleChanceCandidate(false);
                $fixtureData->setIsBetDecorated(false);
                $fixtureData->setPlayed(false);

                if ($fixture->isStatus()) {
                    $fixtureData->setResultDecorationDate(new DateTime());
                }

                $newFixture = $this->fixtureService->createByData($fixtureData);
                $this->logger->info(sprintf("Created new fixture: %s", $newFixture));
                continue;
            }

            if ($fixture->isStatus()){
                $fixtureUpdateData = (new FixtureData())->initFrom($fixtureToUpdate);
                $fixtureUpdateData->setScoreHomeFull($fixture->getHomeFull());
                $fixtureUpdateData->setScoreAwayFull($fixture->getAwayFull());
                $fixtureUpdateData->setScoreHomeHalf($fixture->getHomeHalf());
                $fixtureUpdateData->setScoreAwayHalf($fixture->getAwayHalf());
                $fixtureUpdateData->setPlayed($fixture->isStatus());
                $fixtureUpdateData->setResultDecorationDate(new DateTime());

                $fixtureToUpdate = $this->fixtureService->updateFixture($fixtureToUpdate, $fixtureUpdateData);
                $this->logger->info(sprintf("Updated result for fixture with Id %d: %s", $fixtureToUpdate->getId(), $fixtureToUpdate));
                $this->logger->info(sprintf("Used values %d, %d, %d, %d", $fixture->getHomeFull(), $fixture->getAwayFull(), $fixture->getHomeHalf(), $fixture->getAwayHalf()));
            }
            else{
                $fixtureToUpdate = $this->fixtureService->findByApiKey($fixture->getFixtureApiId());
                $fixtureUpdateData = (new FixtureData())->initFrom($fixtureToUpdate);
                $fixtureUpdateData->setPlayed(false);
                $fixtureUpdateData->setResultDecorationDate(new DateTime());
                $this->fixtureService->updateFixture($fixtureToUpdate, $fixtureUpdateData);
                $this->logger->info(sprintf("!!!Updated for fixture with Id %d: %s failed", $fixtureToUpdate->getId(), $fixtureToUpdate));
            }
        }

        return true;
    }

    public function storeLeaguesFromSportmonk(){
        $leagues = $this->sportsmonkApiGateway->getAvailableLeagues();

        foreach ($leagues as $league){
            if(strpos($league['name'], 'Play-offs') || strpos($league['name'], 'Play-Offs')){
                continue;
            }
            // check if league is already stored
            if (!is_null($this->leagueService->findBySportsmonksApiKey($league['id']))){
                continue;
            }

            $leagueData = new LeagueData();

            $leagueData->setSportsmonkApiId($league['id']);
            $leagueData->setIdent($league['name']);
            $leagueData->setApiId(-1);
            $this->leagueService->createByData($leagueData);
        }
    }

    public function storeSeasonsFromSportmonk($helper, $input, $output){
        // check pages by simple call
        $pageCall = $this->sportsmonkApiGateway->getAvailableSeasonsPageCall();
        $pages = $pageCall['pagination']['total_pages'];
        for ($pageNr = 0; $pageNr < $pages; $pageNr++){
            $seasons = $this->sportsmonkApiGateway->getAvailableSeasons($pageNr + 1);
            foreach ($seasons as $season){
                $league = $this->leagueService->findBySportsmonksApiKey($season['league_id']);
                if (is_null($league)){
                    continue;
                }
                dump("Store season ".$season['name'].' of '.$league->getIdent());

                $years = explode('/', $season['name']);
                // check if a season already exists

                if (strlen($years[0]) > 4){
                    dump($years);
                    continue;
                }
                $seasonCandidate = $this->seasonService->findByLeagueAndStartYear($league, $years[0]);
                if (is_null($seasonCandidate)){
                    $seasonData = new SeasonData();

                    $seasonData->setStartYear($years[0]);
                    if (!array_key_exists(1, $years)){
                        $years[1] = $years[0]+1;
                    }
                    $seasonData->setEndYear($years[1]);
                    $seasonData->setLeague($league);
                    $seasonData->setSportsmonkApiId($season['id']);
                    $clubs = $this->storeClubsFromSportmonk($season['id'], $league, $helper, $input, $output);
                    if (empty($clubs)){
                        continue;
                    }
                    $seasonData->setClubs($clubs);
                    $this->seasonService->createByData($seasonData);
                }else{
                    $seasonData = (new SeasonData())->initFrom($seasonCandidate);
                    $seasonData->setSportsmonkApiId($season['id']);
                    $this->seasonService->updateSeason($seasonCandidate, $seasonData);
                }
            }
        }
    }

    /**
     * @param int $seasonId
     * @param League $league
     * @return Club[]
     */
    public function storeClubsFromSportmonk(int $seasonId, League $league, $helper, $input, $output): array
    {
        $sportmonksClubs = $this->sportsmonkApiGateway->getClubsForSeason($seasonId);
        $clubs = array();
        foreach ($sportmonksClubs as $sportsmonkClub){
            $club = $this->clubService->findBySportsmonkApiKey($sportsmonkClub['id']);
            if (!is_null($club)){
                $clubs[] = $club;
                continue;
            }
            $club = $this->clubService->findClubByNamePlain($sportsmonkClub['name']);
            if (!is_null($club)){
                $clubs[] = $club;
                continue;
            }
            // if league already has stored clubs
//            $allStoredClubs = $this->clubService->findByLeague($league);
//            $candidateClub = null;
//            $candidateClubEquivalent = 0;
//            foreach ($allStoredClubs as $clubCandidate){
//                $eq = similar_text($sportsmonkClub['name'], $clubCandidate->getName(), $percentage);
//                if ($percentage > $candidateClubEquivalent){
//                    $candidateClubEquivalent = $percentage;
//                    $candidateClub = $clubCandidate;
//                }
//            }
//
//            $question = new ChoiceQuestion(
//                'There is an club with similiar name. Should we use it? Got: '.$sportsmonkClub['name'].' | already stored: '.$candidateClub->getName(),
//                // choices can also be PHP objects that implement __toString() method
//                ['yes', 'no'],
//                0
//            );
//            $question->setErrorMessage('Color %s is invalid.');
//
//            $useStored = $helper->ask($input, $output, $question);
//
//            if ($useStored == 'yes'){
//                $clubData = (new ClubData())->initFrom($candidateClub);
//                $clubData->setSportsmonkApiId($sportsmonkClub['id']);
//                $this->clubService->updateClub($candidateClub, $clubData);
//                continue;
//            }

            // no club stored => create new one
            $clubData = new ClubData();
            $clubData->setLeague($league);
            $clubData->setApiId(-1);
            $clubData->setForm('');
            $clubData->setFormRound(-1);
            $clubData->setName($sportsmonkClub['name']);
            $clubData->setSportsmonkApiId($sportsmonkClub['id']);

            $club = $this->clubService->createByData($clubData);
            $clubs[] = $club;
        }
        return $clubs;
    }

    /**
     * @param int $seasonId
     * @param League $league
     * @return Club[]
     */
    public function storeRoundsFromSportmonk(int $seasonId): array
    {
        // TODO save if a round is complete
        $rounds = $this->sportsmonkApiGateway->getRoundForSeason($seasonId);
        $league = $this->leagueService->findBySportsmonksApiKey($rounds[0]['league_id']);
        $season = $this->seasonService->findBySportsmonksApiKey($seasonId);

        foreach ($rounds as $round){
            $roundNr = $round['name'];

            // if round already is stored and has more than 0 fixtures go on
            $existingRound = $this->roundService->findBySportsmonkApiId($round['id']);
            if (!is_null($existingRound)){
                if ($existingRound->getState() == 2){
                    continue;
                }

                if ($existingRound->getNumberOfFixtures() == count($round['fixtures']['data']) && $existingRound->getNumberOfFixtures() > 0){
                    // set it to complete Stored
                    $roundData = (new RoundData())->initFrom($existingRound);
                    $roundData->setState(Round::STATE_COMPLETE_STORED);
                    $this->roundService->updateRound($existingRound, $roundData);
                    continue;
                }
                dump($existingRound->getNumberOfFixtures());
                dump(count($round['fixtures']['data']));
            }

            // create the round
            $roundData = new RoundData();
            $roundData->setFixtures([]);
            $roundData->setState(1);
            $roundData->setSeason($season);
            $roundData->setSportsmonkApiId($round['id']);
            $newRound = $this->roundService->createByData($roundData);

            $fixtureOfRound = array();
            foreach ($round['fixtures']['data'] as $fixture){
                // skip is fixture not part of season
                if ($fixture['season_id'] !== $seasonId){
                    continue;
                }

                $fixtureData = new FixtureData();
                $fixtureData->setRound($newRound);
                $fixtureData->setApiId(-1);
                $fixtureData->setSportsmonkApiId($fixture['id']);
                $fixtureData->setLeague($league);
                $fixtureData->setTimeStamp($fixture['time']['starting_at']['timestamp']);
                $fixtureData->setDate(new DateTime($fixture['time']['starting_at']['date_time']));
                $fixtureData->setPlayed(true);

                $homeClub = $this->clubService->findBySportsmonkApiKey($fixture['localteam_id']);
                if (is_null($homeClub)){
                    continue;
                }

                $awayClub = $this->clubService->findBySportsmonkApiKey($fixture['visitorteam_id']);
                if (is_null($awayClub)){
                    continue;
                }

                $fixtureData->setHomeTeam($homeClub);
                $fixtureData->setAwayTeam($awayClub);
                $fixtureData->setMatchDay($roundNr);
                $fixtureData->setSeason($season);
                $fixtureData->setScoreHomeHalf(-1);
                $fixtureData->setScoreAwayHalf(-1);
                $scoreHome = $fixture['scores']['localteam_score'];
                $scoreAway = $fixture['scores']['visitorteam_score'];
                if (!is_null($fixture['scores']['ft_score'])){
                    $fullTimeScores = explode('-', $fixture['scores']['ft_score']);
                    if ($fullTimeScores[0] == $scoreHome && $fullTimeScores[1] == $scoreAway){
                    }else{
                        dump('!!!! Fulltime score is invalid');
                    }
                }
                if (!is_null($fixture['scores']['ht_score'])){
                    $halfTimeScores = explode('-', $fixture['scores']['ft_score']);
                    if (is_null($halfTimeScores[0]) && is_null($halfTimeScores[1])){
                        dump('!!!! Halftime score is empty');
                    }else{
                        $fixtureData->setScoreHomeHalf($halfTimeScores[0]);
                        $fixtureData->setScoreAwayHalf($halfTimeScores[1]);
                    }
                }

                $fixtureData->setScoreHomeFull($scoreHome);
                $fixtureData->setScoreAwayFull($scoreAway);
                $fixtureData->setIsDoubleChanceCandidate(false);
                $fixtureData->setIsBetDecorated(false);

                $fixture = $this->fixtureService->createByData($fixtureData);

                $fixtureOfRound[] = $fixture;
                dump((string) $fixture);
            }
            $roundData = (new RoundData())->initFrom($newRound);
            $roundData->setFixtures($fixtureOfRound);
            $roundData->setState(Round::STATE_COMPLETE_STORED);
            $this->roundService->updateRound($newRound, $roundData);
        }
        return [];
    }

    public function storeOddsForFixtureFromSportsmonk(Season $season)
    {
        $fixruesOfFirstRound = $this->fixtureService->findByLeagueAndSeasonAndRound($season->getLeague()->getSportmonksApiId(), $season->getStartYear(), 1, false);

        $fromDate = $fixruesOfFirstRound[0]->getTimeStamp()-86400;

        $fixtures = $this->fixtureService->getUndecoratedFixturesTimeStampVariant($fromDate);

        foreach ($fixtures as $fixture){
            dump("try to store odds for ".$fixture.' :'.$fixture->getSportmonksApiId());
            $this->logger->info("try to store odds for ".$fixture.' :'.$fixture->getSportmonksApiId());
            if (!is_null($fixture->getOddDecorationDate())){
                continue;
            }
            $oddResponses = $this->sportsmonkApiGateway->getOddsForFixture($fixture->getSportmonksApiId());
            foreach($oddResponses as $oddResponse){
                $oddData = new FixtureOddData();

                // get Fixture for odd
                // filter faulty odds
                if (is_null($oddResponse->getHomeOdd()) || is_null($oddResponse->getDrawOdd()) || is_null($oddResponse->getAwayOdd())){
                    dump('Faulty odd response');
                    dump($oddResponse);
                    $this->logger->info("Faulty odd response:");
                    continue;
                }

                $oddData->setFixture($fixture);
                $oddData->setType($oddResponse->getType());
                $oddData->setProvider($oddResponse->getProvider());
                $oddData->setHomeOdd($oddResponse->getHomeOdd());
                $oddData->setDrawOdd($oddResponse->getDrawOdd());

                $oddData->setAwayOdd($oddResponse->getAwayOdd());


                $this->fixtureOddService->createByData($oddData);

                // update oddtime in fixture
                $fixtureUpdateDate = (new FixtureData())->initFrom($fixture);
                $fixtureUpdateDate->setOddDecorationDate(new DateTime());
                $this->fixtureService->updateFixture($fixture, $fixtureUpdateDate);
                dump("stored odds for: ".$fixture);
                $this->logger->info("stored odds for: ".$fixture);
            }
        }
    }

    public function getStandingsForSeason(Season $season){
        $rounds = $this->sportsmonkApiGateway->getRoundForSeason($season->getSportmonksApiId());
        foreach ($rounds as $round){
            // check if seedings are already stored
            $decorated = 0;
            foreach($season->getClubs() as $club){
                $seeding = $this->seedingService->findByClubAndSeasonAndLRound($club, $season, $round['name']);
                if (!is_null($seeding)){
                    $decorated++;
                }
            }

            if ($decorated == count($season->getClubs())){
                continue;
            }

            $standings = $this->sportsmonkApiGateway->getStandingsForSeasonRound($season->getSportmonksApiId(), $round['id']);
            foreach ($standings as $standing){
                $seedingData = new SeedingData();
                $seedingData->setSeason($season);
                $seedingData->setWins($standing['overall']['won']);
                $seedingData->setLooses($standing['overall']['lost']);
                $seedingData->setDraws($standing['overall']['draw']);
                $seedingData->setGoals($standing['overall']['goals_scored']);
                $seedingData->setAgainstGoals($standing['overall']['goals_against']);
                $team = $this->clubService->findBySportsmonkApiKey($standing['team_id']);
                $seedingData->setClub($team);
                $seedingData->setPoints($standing['points']);
                $seedingData->setPosition($standing['position']);
                $seedingData->setRound($round['name']);

                $seedingData->setForm($standing['recent_form']);
                $this->seedingService->createByData($seedingData);
                dump('Stored seeding for club '.$team.' for round '.$round['name'].' of '. $season);
                $this->logger->info('Stored seeding for club '.$team.' for round '.$round['name'].' of '. $season);
            }
        }
    }
}
