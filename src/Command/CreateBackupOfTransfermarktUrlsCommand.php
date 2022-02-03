<?php


namespace App\Command;

use App\Service\UrlResponseBackup\UrlBackuper;
use Doctrine\ORM\ORMException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CreateBackupOfTransfermarktUrlsCommand extends Command
{
    const BASE_URL = 'https://www.transfermarkt.de';

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'bet:backup:urls';
    /**
     * @var UrlBackuper
     */
    private $urlBackuper;


    /**
     * ReadRawDataCommand constructor.
     * @param UrlBackuper $urlBackuper
     */
    public function __construct(UrlBackuper $urlBackuper)
    {
        $this->urlBackuper = $urlBackuper;
        parent::__construct();
    }


    protected function configure(): void
    {
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws ClientExceptionInterface
     * @throws ORMException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->urlBackuper->backupUrls();
        return Command::SUCCESS;
    }
}