<?php


namespace App\Command;


use App\Service\Api\AutoApiCaller;
use App\Service\Api\AutomaticUpdateSettingService;
use App\Service\Api\FootballApiManagerService;
use App\Service\Import\UpdateService;
use App\Service\League\LeagueService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
     * InitFootballApiManager constructor.
     * @param FootballApiManagerService $footballApiManagerService
     * @param UpdateService $updateService
     * @param AutomaticUpdateSettingService $automaticUpdateSettingService
     * @param AutoApiCaller $autoApiCaller
     * @param LeagueService $leagueService
     */
    public function __construct(FootballApiManagerService $footballApiManagerService, UpdateService $updateService, AutomaticUpdateSettingService $automaticUpdateSettingService, AutoApiCaller $autoApiCaller, LeagueService $leagueService)
    {
        $this->footballApiManagerService = $footballApiManagerService;
        $this->updateService = $updateService;
        $this->automaticUpdateSettingService = $automaticUpdateSettingService;
        $this->autoApiCaller = $autoApiCaller;
        $this->leagueService = $leagueService;
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

//        $this->updateService->updateLeagues();

//        $settings = $this->automaticUpdateSettingService->getSettings();
//        foreach ($settings->getCurrentRounds() as $leagueIdent => $round){
//            $league = $this->leagueService->findByIdent($leagueIdent);
//            $this->updateService->storeFixtureForLeagueAndRound($league->getApiId(), 2021, $round-1);
//        }

//        $this->updateService->updateLeague(UpdateService::LEAGUE_PREMIER_LEAGUE, UpdateService::LEAGUES[UpdateService::LEAGUE_PREMIER_LEAGUE], 2021);
        $result = $this->autoApiCaller->identifyCandidates();
        dump($result);

        return Command::SUCCESS;
    }
}