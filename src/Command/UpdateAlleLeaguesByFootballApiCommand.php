<?php


namespace App\Command;


use App\Entity\League;
use App\Service\Evaluation\EvaluationService;
use App\Service\Import\RawFileImporter;
use App\Service\Import\UpdateService;
use App\Service\League\LeagueService;
use App\Service\Season\SeasonService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateAlleLeaguesByFootballApiCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'bet:update:all';
    /**
     * @var UpdateService
     */
    private $updateService;

    /**
     * @var EvaluationService
     */
    private $evaluationService;

    /**
     * UpdateAlleLeaguesByFootballApiCommand constructor.
     * @param UpdateService $updateService
     * @param EvaluationService $evaluationService
     */
    public function __construct(UpdateService $updateService, EvaluationService $evaluationService)
    {
        $this->updateService = $updateService;
        $this->evaluationService = $evaluationService;
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

        $this->evaluationService->calculateFormForAllTeamsOfRound(39, 2021, 3);


        return Command::SUCCESS;
    }
}