<?php

namespace App\Controller;

use App\Service\Api\FootballApiGateway;
use App\Service\Club\ClubService;
use App\Service\Import\RawFileImporter;
use App\Service\LiveFormTable\LiveFormTableProvider;
use App\Service\Season\SeasonService;
use App\Service\SeasonTable\SeasonTableService;
use App\Service\TableEntry\TableEntryService;
use App\Service\UrlResponseBackup\UrlBackuper;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;


class StatisticController extends AbstractController
{

    /**
     * @Route("/show/{leagueIdent}", name="read_raw_data")
     * @param ClubService $clubService
     * @param RawFileImporter $importer
     * @param string $leagueIdent
     * @return Response
     */
    public function readMatchDaysIn(ClubService $clubService, RawFileImporter $importer, string $leagueIdent): Response
    {
        return $this->render('statistic/index.html.twig', [
            'steps' => $importer->calculateWinChances($leagueIdent, 1, 4),
        ]);
    }

    /**
     * @Route("/table/{leagueIdent}/{seasonYear}/{matchDay}", name="show_match_table")
     * @param SeasonService $seasonService
     * @param SeasonTableService $seasonTableService
     * @param TableEntryService $tableEntryService
     * @param string $leagueIdent
     * @param int $seasonYear
     * @param int $matchDay
     * @return Response
     */
    public function showTable(
        SeasonService $seasonService,
        SeasonTableService $seasonTableService,
        TableEntryService $tableEntryService,
        string $leagueIdent,
        int $seasonYear,
        int $matchDay
    ): Response
    {
        $season = $seasonService->getSeasonByLeagueAndStartYear($leagueIdent, $seasonYear);
        $table = $seasonTableService->getTableBySeasonAndMatchDay($season, $matchDay);
        $sortedEntries = $tableEntryService->getSortedEntriesForTable($table);
        dump($sortedEntries);
        return $this->render('statistic/table.html.twig', [
            'entries' => $sortedEntries
        ]);
    }

    /**
     * @Route("/weekend", name="show_weekend")
     * @param LiveFormTableProvider $liveFormTableProvider
     * @return Response
     * @throws NonUniqueResultException
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function showWeekend(
        LiveFormTableProvider $liveFormTableProvider
    ): Response
    {
        $candidates = $liveFormTableProvider->getAllCandidatesForWeekend();
        dump($candidates->getErrors());
        return $this->render('statistic/weekend.html.twig', [
            'candidates' => $candidates->getMatches(),
            'errors' => $candidates->getErrors(),
            'leagues' => $this->getLeagueUrls()
        ]);
    }

    /**
     * @Route("/backup", name="backup_url")
     * @param UrlBackuper $urlBackuper
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function backupUrls(
        UrlBackuper $urlBackuper
    ): Response
    {
        $urlBackuper->backupUrls();
        return $this->redirect('show_weekend');
    }

    /**
     * @return array
     */
    private function getLeagueUrls(): array
    {
        return [
            'en1' => 'https://www.transfermarkt.de/premier-league/spieltagtabelle/wettbewerb/GB1/saison_id/2021',
            'en2' =>
                'https://www.transfermarkt.de/championship/spieltagtabelle/wettbewerb/GB2/saison_id/2021'
            ,
            'it1' =>
                'https://www.transfermarkt.de/serie-a/spieltagtabelle/wettbewerb/IT1/saison_id/2021'
            ,
            'it2' =>
                'https://www.transfermarkt.de/serie-b/spieltagtabelle/wettbewerb/IT2/saison_id/2021'
            ,
            'es1' =>
                'https://www.transfermarkt.de/laliga/spieltagtabelle/wettbewerb/ES1/saison_id/2021'
            ,
            'es2' =>
                'https://www.transfermarkt.de/laliga2/spieltagtabelle/wettbewerb/ES2/saison_id/2021'
            ,
            'fr1' =>
                'https://www.transfermarkt.de/ligue-1/spieltagtabelle/wettbewerb/FR1/saison_id/2021'
            ,
            'fr2' =>
                'https://www.transfermarkt.de/ligue-2/spieltagtabelle/wettbewerb/FR2/saison_id/2021'
            ,
            'de1' =>
                'https://www.transfermarkt.de/de1/spieltagtabelle/wettbewerb/L1/saison_id/2021'
            ,
            'de2' =>
                'https://www.transfermarkt.de/2-de1/spieltagtabelle/wettbewerb/L2/saison_id/2021'
        ];
    }

    /**
     * @Route("/test", name="backup_url")
     * @param FootballApiGateway $footballApiGateway
     * @return Response
     */
    public function test(
        FootballApiGateway $footballApiGateway
    ): Response
    {
        dd($footballApiGateway->getNextFixturesForLeagueAndRound(39, 2021, 1));
        return $this->redirect('show_weekend');
    }
}
