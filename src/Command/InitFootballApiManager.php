<?php


namespace App\Command;


use App\Service\Api\AutoApiCaller;
use App\Service\Api\SportsmonkApiGateway;
use App\Service\League\LeagueData;
use App\Service\Season\SeasonService;
use App\Service\Setting\AutomaticUpdateSettingService;
use App\Service\Setting\FootballApiManagerService;
use App\Service\Fixture\FixtureService;
use App\Service\Import\UpdateService;
use App\Service\League\LeagueService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class InitFootballApiManager extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'bet:init:manager';
    /**
     * @var FootballApiManagerService
     */
    private $footballApiManagerService;

    /**
     * @var UpdateService
     */
    private $updateService;

    /**
     * @var AutomaticUpdateSettingService
     */
    private $automaticUpdateSettingService;

    /**
     * @var AutoApiCaller
     */
    private $autoApiCaller;

    /**
     * @var LeagueService
     */
    private $leagueService;

    /**
     * @var FixtureService
     */
    private $fixtureService;

    /**
     * @var SportsmonkApiGateway
     */
    private $sportsmonkApiGateway;

    /**
     * @var SeasonService
     */
    private $seasonService;

    /**
     * InitFootballApiManager constructor.
     * @param FootballApiManagerService $footballApiManagerService
     * @param UpdateService $updateService
     * @param AutomaticUpdateSettingService $automaticUpdateSettingService
     * @param AutoApiCaller $autoApiCaller
     * @param LeagueService $leagueService
     * @param SeasonService $seasonService
     * @param FixtureService $fixtureService
     */
    public function __construct(FootballApiManagerService $footballApiManagerService, UpdateService $updateService, AutomaticUpdateSettingService $automaticUpdateSettingService, AutoApiCaller $autoApiCaller, LeagueService $leagueService, FixtureService $fixtureService, SeasonService $seasonService, SportsmonkApiGateway $sportsmonkApiGateway)
    {
        $this->footballApiManagerService = $footballApiManagerService;
        $this->updateService = $updateService;
        $this->automaticUpdateSettingService = $automaticUpdateSettingService;
        $this->autoApiCaller = $autoApiCaller;
        $this->leagueService = $leagueService;
        $this->fixtureService = $fixtureService;
        $this->seasonService = $seasonService;
        $this->sportsmonkApiGateway = $sportsmonkApiGateway;

        parent::__construct();
    }

    protected function configure(): void
    {
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Doctrine\ORM\ORMException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // step 1: init all available leagues
        if (false){
            $this->updateService->storeLeaguesFromSportmonk();
        }

        // step 2: init all season for leagues
        if (false){
            $helper = $this->getHelper('question');
            $question = new ChoiceQuestion(
                'There is an club with similiar name. Should we use it?',
                // choices can also be PHP objects that implement __toString() method
                ['yes', 'no'],
                0
            );
            $this->storeSeasonsFromSportsmonk($helper, $input, $output);
        }

        // step 3: store fixtures for seasons
        if (true) {

            /*
             * "1. HNL" => 22
  "2. Bundesliga" => 16
  "Admiral Bundesliga" => 18
  "Bundesliga" => 15
  "Champions League" => 1
  "Championship" => 4
  "Eerste Divisie" => 13
  "Ekstraklasa" => 37
  "Eliteserien" => 36
  "Eredivisie" => 12
  "Europa League" => 2
  "First Division" => 25
  "First Division B" => 20
  "Fortuna Liga" => 23
  "La Liga" => 46
  "La Liga 2" => 47
  "League One" => 5
  "League Two" => 6
  "Liga 1" => 40
  "Ligue 1" => 70
  "Ligue 2" => 28
  "Major League Soccer" => 67
  "Parva Liga" => 21
  "Premier League" => 74
  "Premiership" => 45
  "Primeira Liga" => 38
  "Primera Division" => 66
  "Serie A" => 55
  "Serie B" => 56
             */
            $famousLeagues = [22, 16, 18, 15, 1, 4, 13, 37, 36, 12, 2, 25, 20,23,46,47,5,6,40,70,28,67,21,74,45,38,66,55,56];
            foreach ($famousLeagues as $famousLeagueId){
                $famousLeague = $this->leagueService->findById($famousLeagueId);
                $seasons = $this->seasonService->findByLeague($famousLeague);
                dump("Store ".count($seasons)." season for league".$famousLeague->getIdent());
                foreach ($seasons as $season) {
                    dump((string) $season);
                    $this->storeRoundsFromSportsmonk($season->getSportmonksApiId());
                }
            }

//            $seasons = $this->seasonService->getAll();

        }


//        $this->updateService->updateLeagues();

//        $res = $this->updateService->updateSeedingFormsForFixture($this->fixtureService->findByApiKey(719533));

//        $this->updateService->storeLeaguesFromSportmonk();
//        $seasons = $this->seasonService->getAll();
//        foreach ($seasons as $season){
//            $this->updateService->storeRoundsFromSportmonk($season->getSportmonksApiId());
//        }
//        dump($this->sportsmonkApiGateway->getFixturesInDateRange('2006-03-29'));

//        $seasons = $this->seasonService->getAll();
//        foreach ($seasons as $season){
//            if ($season->getStartYear() < 2018 || $season->getStartYear() > 2020){
//                continue;
//            }
//            dump($season->getStartYear());
//            $this->updateService->storeOddsForFixtureFromSportsmonk($season);
//        }

        $helper = $this->getHelper('question');
//        $question = new ChoiceQuestion(
//            'There is an club with similiar name. Should we use it?',
//            // choices can also be PHP objects that implement __toString() method
//            ['yes', 'no'],
//            0
//        );
//        $question->setErrorMessage('Color %s is invalid.');
//
//        $color = $helper->ask($input, $output, $question);

//        $seasons = $this->seasonService->getAll();
//        foreach ($seasons as $season){
//            $this->storeRoundsFromSportsmonk($season->getSportmonksApiId());
//        }

        return Command::SUCCESS;
    }

    private function initFootballApiLeaguesAndClubs(){
        $this->updateService->updateLeague(UpdateService::LEAGUE_DEN_SUPERLIGA, UpdateService::API_KEY_DEN_SUPERLIGA, 2020);
        $this->updateService->updateLeague(UpdateService::LEAGUE_SCT_PREMIERSHIP, UpdateService::API_KEY_SCT_PREMIERSHIP, 2020);
    }

    private function storeSeasonsFromSportsmonk($helper, $input, $output)
    {
        $this->updateService->storeSeasonsFromSportmonk($helper, $input, $output);
    }

    private function storeRoundsFromSportsmonk(int $seasonId)
    {
        $this->updateService->storeRoundsFromSportmonk($seasonId);
    }

    private function storeLeaguesFromSportsmonk(){
        $leagues = $this->sportsmonkApiGateway->getAvailableLeagues();
        foreach ($leagues as $league){
            if(strpos($league['name'], 'Play-offs') || strpos($league['name'], 'Play-Offs')){
                continue;
            }
            // check if league is already stored
            $candidateLeague = null;
            $candidateLeagueEquivalent = 0;
            foreach ($this->leagueService->getAll() as $leagueCandidate){
                $eq = similar_text($league['name'], $leagueCandidate->getIdent(), $percentage);
                if ($percentage > $candidateLeagueEquivalent){
                    $candidateLeagueEquivalent = $percentage;
                    $candidateLeague = $leagueCandidate;
                }
            }

            if ($candidateLeagueEquivalent > 70.0){
                $leagueData = (new LeagueData())->initFrom($candidateLeague);
                $leagueData->setSportsmonkApiId($league['id']);
                $this->leagueService->updateLeague($candidateLeague, $leagueData);
            }else{
                $leagueData = new LeagueData();

                $leagueData->setSportsmonkApiId($league['id']);
                $leagueData->setIdent($league['name']);
                $leagueData->setApiId(-1);
                $this->leagueService->createByData($leagueData);
            }
        }
    }

    private function initFootballApiManager(){
        $this->footballApiManagerService->initializeApiManager();
    }
}