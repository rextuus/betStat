<?php

namespace App\Controller;

use App\Service\Club\ClubData;
use App\Service\Club\ClubService;
use App\Service\Import\RawFileImporter;
use App\Service\LiveFormTable\LiveFormTableProvider;
use App\Service\Season\SeasonService;
use App\Service\SeasonTable\SeasonTableService;
use App\Service\TableEntry\TableEntryService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function showWeekend(
        LiveFormTableProvider $liveFormTableProvider
    ): Response
    {
        $leagues = [
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

        $candidates = $liveFormTableProvider->getAllCandidatesForWeekend();dump($candidates->getErrors());
        return $this->render('statistic/weekend.html.twig', [
            'candidates' => $candidates->getMatches(),
            'errors' => $candidates->getErrors(),
            'leagues' => $leagues
        ]);
    }
}
