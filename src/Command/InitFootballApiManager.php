<?php


namespace App\Command;


use App\Service\Api\AutoApiCaller;
use App\Service\Api\AutomaticUpdateSettingService;
use App\Service\Api\FootballApiManagerService;
use App\Service\Fixture\FixtureService;
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
     * @var FixtureService
     */
    private $fixtureService;

    /**
     * InitFootballApiManager constructor.
     * @param FootballApiManagerService $footballApiManagerService
     * @param UpdateService $updateService
     * @param AutomaticUpdateSettingService $automaticUpdateSettingService
     * @param AutoApiCaller $autoApiCaller
     * @param LeagueService $leagueService
     * @param FixtureService $fixtureService
     */
    public function __construct(FootballApiManagerService $footballApiManagerService, UpdateService $updateService, AutomaticUpdateSettingService $automaticUpdateSettingService, AutoApiCaller $autoApiCaller, LeagueService $leagueService, FixtureService $fixtureService)
    {
        $this->footballApiManagerService = $footballApiManagerService;
        $this->updateService = $updateService;
        $this->automaticUpdateSettingService = $automaticUpdateSettingService;
        $this->autoApiCaller = $autoApiCaller;
        $this->leagueService = $leagueService;
        $this->fixtureService = $fixtureService;

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

//        $res = $this->updateService->updateSeedingFormsForFixture($this->fixtureService->findByApiKey(719533));

        $this->autoApiCaller->updateResultsOfAlreadyFinishedFixtures();

        return Command::SUCCESS;
    }
}