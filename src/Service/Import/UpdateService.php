<?php


namespace App\Service\Import;


use App\Entity\Club;
use App\Entity\Fixture;
use App\Entity\FootballApiManager;
use App\Entity\League;
use App\Entity\Season;
use App\Service\Api\FootballApiGateway;
use App\Service\Api\FootballApiManagerService;
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
use function PHPUnit\Framework\isNull;

class UpdateService
{

    private const API_KEY_BUNDESLIGA = 78;
    private const API_KEY_BUNDESLIGA_2 = 79;
    private const API_KEY_PREMIER_LEAGUE = 39;
    private const API_KEY_CHAMPIONS_CHIP = 40;
    private const API_KEY_PRIMERA_DIVISION= 140;
    private const API_KEY_SECUNDA_DIVISION= 141;
    private const API_KEY_SERIE_A= 135;
    private const API_KEY_SERIE_B= 136;
    private const API_KEY_LEAGUE_A= 61;
    private const API_KEY_LEAGUE_B= 62;
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
     * @var FootballApiManagerService
     */
    private $footballApiManagerService;

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
     * @param FootballApiManagerService $footballApiManagerService
     * @param LoggerInterface $autoUpdateLogger
     */
    public function __construct(FootballApiGateway $footballApiGateway, ClubService $clubService, LeagueService $leagueService, SeasonService $seasonService, SeedingService $seedingService, FixtureService $fixtureService, FixtureOddService $fixtureOddService, FootballApiManagerService $footballApiManagerService, LoggerInterface $autoUpdateLogger)
    {
        $this->footballApiGateway = $footballApiGateway;
        $this->clubService = $clubService;
        $this->leagueService = $leagueService;
        $this->seasonService = $seasonService;
        $this->seedingService = $seedingService;
        $this->fixtureService = $fixtureService;
        $this->fixtureOddService = $fixtureOddService;
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
            $leagueData = new LeagueData();
            $leagueData->setIdent($leagueIdent);
            $leagueData->setApiId($standings->getLeagueId());
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
            $this->logger->info('Error during bet collection: EmptyResponse=>%d CountResponse=>%d', (empty($fixtureResponses)), !count($fixtureResponses));
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
        $season = $this->seasonService->findByLeagueAndStartYear($league, $startYear)[0];

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
        foreach ($fixtures as $fixture){
            if ($fixture->isStatus()){
                $fixtureToUpdate = $this->fixtureService->findByApiKey($fixture->getFixtureApiId());
                $fixtureUpdate = (new FixtureData())->initFrom($fixtureToUpdate);
                $fixtureToUpdate->setScoreHomeFull($fixture->getHomeFull());
                $fixtureToUpdate->setScoreAwayFull($fixture->getAwayFull());
                $fixtureToUpdate->setScoreHomeHalf($fixture->getHomeHalf());
                $fixtureToUpdate->setScoreAwayHalf($fixture->getAwayHalf());
                $this->fixtureService->updateFixture($fixtureToUpdate, $fixtureUpdate);
                $this->logger->info(sprintf("Updated result for fixture with Id %d: %s", $fixtureToUpdate->getId(), $fixtureToUpdate));
            }
        }

        return true;
    }
}
