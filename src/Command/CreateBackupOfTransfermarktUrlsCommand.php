<?php


namespace App\Command;

use App\Service\League\LeagueService;
use App\Service\LiveFormTable\LiveFormTableProvider;
use App\Service\UrlResponseBackup\UrlResponseBackupData;
use App\Service\UrlResponseBackup\UrlResponseBackupService;
use DateTime;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CreateBackupOfTransfermarktUrlsCommand extends Command
{
    const BASE_URL = 'https://www.transfermarkt.de';

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'bet:backup:urls';
    /**
     * @var LiveFormTableProvider
     */
    private $liveFormTableProvider;
    /**
     * @var LeagueService
     */
    private $leagueService;
    /**
     * @var UrlResponseBackupService
     */
    private $urlResponseBackupService;
    /**
     * @var LoggerInterface
     */
    private $logger;


    /**
     * ReadRawDataCommand constructor.
     * @param LiveFormTableProvider $liveFormTableProvider
     * @param LeagueService $leagueService
     * @param UrlResponseBackupService $urlResponseBackupService
     * @param LoggerInterface $backupLogger
     */
    public function __construct(
        LiveFormTableProvider $liveFormTableProvider,
        LeagueService $leagueService,
        UrlResponseBackupService $urlResponseBackupService,
        LoggerInterface $backupLogger
    )
    {
        $this->liveFormTableProvider = $liveFormTableProvider;
        $this->leagueService = $leagueService;
        $this->urlResponseBackupService = $urlResponseBackupService;
        $this->logger = $backupLogger;
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
     * @throws DecodingExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $leagueIdents = ['de1', 'de2', 'en1', 'en2', 'it1', 'it2', 'es1', 'es2', 'fr1', 'fr2'];

        foreach ($leagueIdents as $ident){
            $matchDayUrl = self::BASE_URL . $this->liveFormTableProvider->getMatchDayLinkByLeague($ident);


            // get html raw content
            $httpClient = HttpClient::create();
            $response = $httpClient->request('GET', $matchDayUrl);
            $content = $response->getContent();

            $ending = (new DateTime())->format('Y-m-d');
            $contentPath = 'Backups/'.$ident.'_'.$ending.".txt";

            $urlResponseBackupData = new UrlResponseBackupData();
            $urlResponseBackupData->setRawContent($contentPath);
            $urlResponseBackupData->setUrl($matchDayUrl);
            $urlResponseBackupData->setMatchDay($this->liveFormTableProvider->getMatchDayByLeague($ident));
            $urlResponseBackupData->setLeague($this->leagueService->getLeagueByIdent($ident));
            $urlResponseBackupData->setCollectionDate(new DateTime());

            $this->urlResponseBackupService->createByData($urlResponseBackupData);

            file_put_contents($contentPath, $content);
            $this->logger->info("New backup url for current ".$ident." matchday successfull stored");
            $output->writeln("New backup url for current ".$ident." matchday successfull stored");
        }
        return Command::SUCCESS;
    }
}