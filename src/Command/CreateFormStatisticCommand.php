<?php


namespace App\Command;


use App\Entity\League;
use App\Service\Import\RawFileImporter;
use App\Service\League\LeagueService;
use App\Service\Season\SeasonService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateFormStatisticCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'bet:init:form:table';
    /**
     * @var RawFileImporter
     */
    private $importer;

    /**
     * @var LeagueService
     */
    private $leagueService;

    /**
     * @var SeasonService
     */
    private $seasonService;

    /**
     * ReadRawDataCommand constructor.
     * @param RawFileImporter $importer
     * @param LeagueService $leagueService
     * @param SeasonService $seasonService
     */
    public function __construct(RawFileImporter $importer, LeagueService $leagueService, SeasonService $seasonService)
    {
        $this->importer = $importer;
        $this->leagueService = $leagueService;
        $this->seasonService = $seasonService;
        parent::__construct();
    }


    protected function configure(): void
    {
        $this->addArgument('startYear');
        $this->addArgument('endYear');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Doctrine\ORM\ORMException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $league = $this->leagueService->getLeagueByIdent('de1');

        if (is_null($league)) {
            return Command::FAILURE;
        }

        $seasons = $this->seasonService->getAllSeasonsBelongingToLeague($league);

        $progressBar = new ProgressBar($output, count($seasons));

        // starts and displays the progress bar
        $progressBar->start();

        foreach ($seasons as $season) {
            $this->importer->calculateFormTablesStatistic($season);
            $progressBar->advance();
        }
        $progressBar->finish();


        return Command::SUCCESS;
    }
}