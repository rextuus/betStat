<?php


namespace App\Command;


use App\Service\Import\RawFileImporter;
use App\Service\Import\SeasonTableConstructor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitSeasonTablesCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'bet:init:seasonTable';

    /**
     * @var SeasonTableConstructor
     */
    private $seasonTableConstructor;

    /**
     * ReadRawDataCommand constructor.
     * @param SeasonTableConstructor $seasonTableConstructor
     */
    public function __construct(SeasonTableConstructor $seasonTableConstructor)
    {
        $this->seasonTableConstructor = $seasonTableConstructor;
        parent::__construct();
    }


    protected function configure(): void
    {
        $this->addArgument('league');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Doctrine\ORM\ORMException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $league = $input->getArgument('league');
        $this->seasonTableConstructor->initializeSeasonTablesForLeague($league);
        return Command::SUCCESS;
    }
}