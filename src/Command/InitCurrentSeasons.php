<?php


namespace App\Command;


use App\Service\Club\ClubData;
use App\Service\Club\ClubService;
use App\Service\Import\RawFileImporter;
use App\Service\League\LeagueData;
use App\Service\League\LeagueService;
use App\Service\LiveFormTable\LiveFormTableProvider;
use App\Service\Season\SeasonData;
use App\Service\Season\SeasonService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class InitCurrentSeasons extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'bet:init:current:seasons';
    /**
     * @var LeagueService
     */
    private $leagueService;
    /**
     * @var LiveFormTableProvider
     */
    private $liveFormTableProvider;
    /**
     * @var SeasonService
     */
    private $seasonService;
    /**
     * @var ClubService
     */
    private $clubService;

    /**
     * LeagueService constructor.
     * @param LiveFormTableProvider $liveFormTableProvider
     * @param LeagueService $leagueService
     * @param SeasonService $seasonService
     * @param ClubService $clubService
     */
    public function __construct(
        LiveFormTableProvider $liveFormTableProvider,
        LeagueService $leagueService,
        SeasonService $seasonService,
        ClubService $clubService
    )
    {
        $this->leagueService = $leagueService;
        $this->liveFormTableProvider = $liveFormTableProvider;
        $this->seasonService = $seasonService;
        $this->clubService = $clubService;
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
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $leagueIdents = [
            'de1',
            'de2',
            'en1',
            'en2',
            'it1',
            'it2',
            'es1',
            'es2',
            'fr1',
            'fr2',
        ];

        // create leagues for idents if not exist already
        foreach ($leagueIdents as $leagueIdent) {
            $league = $this->leagueService->getLeagueByIdent($leagueIdent);
            if (!$league) {
                $leagueData = new LeagueData();
                $leagueData->setIdent($leagueIdent);
                $league = $this->leagueService->createByData($leagueData);
            }

            $clubNames = $this->liveFormTableProvider->getClubsOfLeague($leagueIdent);
            $clubs = array();
            foreach ($clubNames as $clubName){
                $club = $this->clubService->findClubByName($clubName);
                if (!$club) {
                    $clubData = new ClubData();
                    $clubData->setName($clubName);
                    $clubData->setLeague($league);
                    $club = $this->clubService->createByData($clubData);
                }
                $clubs[] = $club;
            }

            $season = $this->seasonService->findByYears(2021, 2022, $league);
            if (!$season) {
                $seasonData = new SeasonData();
                $seasonData->setStartYear(2021);
                $seasonData->setEndYear(2022);
                $seasonData->setLeague($league);
                $seasonData->setClubs($clubs);
                $season = $this->seasonService->createByData($seasonData);
            }
        }

        // get odds for each league
        return Command::SUCCESS;
    }
}