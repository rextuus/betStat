<?php


namespace App\Command;


use App\Service\Import\RawFileImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReadRawDataCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'bet:init:database';
    /**
     * @var RawFileImporter
     */
    private $importer;

    /**
     * ReadRawDataCommand constructor.
     * @param RawFileImporter $importer
     */
    public function __construct(RawFileImporter $importer)
    {
        $this->importer = $importer;
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
        $this->importer->importRawDataFile($league);
        return Command::SUCCESS;
    }
}