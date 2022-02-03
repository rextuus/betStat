<?php


namespace App\Service\Import;


use App\Entity\MatchDayGame;
use App\Entity\Season;
use App\Entity\SeasonTable;
use App\Service\League\LeagueService;
use App\Service\MatchGame\MatchDayGameService;
use App\Service\Season\SeasonService;
use App\Service\SeasonTable\SeasonTableData;
use App\Service\SeasonTable\SeasonTableService;
use App\Service\TableEntry\SeedinData;
use App\Service\TableEntry\TableEntryService;
use Doctrine\ORM\ORMException;
use Exception;

class SeasonTableConstructor
{
    /**
     * @var LeagueService
     */
    private $leagueService;
    /**
     * @var SeasonTableService
     */
    private $seasonTableService;
    /**
     * @var TableEntryService
     */
    private $tableEntryService;
    /**
     * @var MatchDayGameService
     */
    private $matchDayGameService;
    /**
     * @var SeasonService
     */
    private $seasonService;

    /**
     * SeasonTableConstructor constructor.
     * @param LeagueService $leagueService
     * @param SeasonTableService $seasonTableService
     * @param TableEntryService $tableEntryService
     * @param MatchDayGameService $matchDayGameService
     * @param SeasonService $seasonService
     */
    public function __construct(
        LeagueService $leagueService,
        SeasonTableService $seasonTableService,
        TableEntryService $tableEntryService,
        MatchDayGameService $matchDayGameService,
        SeasonService $seasonService
    )
    {
        $this->leagueService = $leagueService;
        $this->seasonTableService = $seasonTableService;
        $this->tableEntryService = $tableEntryService;
        $this->matchDayGameService = $matchDayGameService;
        $this->seasonService = $seasonService;
    }

    public function initializeSeasonTablesForLeague(string $leagueIdent)
    {
        // get league
        $league = $this->leagueService->getLeagueByIdent($leagueIdent);

        // get Seasons
        $seasons = $this->seasonService->getAllSeasonsBelongingToLeague($league);
        $firstSeason = $seasons[0];
        dump($firstSeason->getStartYear() . "/" . $firstSeason->getEndYear());

        // calculate first matchday of season => get all matches of first matchday of seasn
        // create a new Season Table

        $matchDayOneSeasonTableData = new SeasonTableData();
        $matchDayOneSeasonTableData->setMatchDay(1);
        $matchDayOneSeasonTableData->setSeason($firstSeason);
        $matchDayOneSeasonTable = $this->seasonTableService->createByData($matchDayOneSeasonTableData);

        // add a new table entry for each club belonging to season


        $this->evaluateMatchDay($firstSeason, $matchDayOneSeasonTable, true);

        foreach (range(2, $firstSeason->getNumberOfClubs() * 2 - 2) as $index) {
            $matchDayOneSeasonTableData = new SeasonTableData();
            $matchDayOneSeasonTableData->setMatchDay($index);
            $matchDayOneSeasonTableData->setSeason($firstSeason);
            $matchDayOneSeasonTable = $this->seasonTableService->createByData($matchDayOneSeasonTableData);

            // add a new table entry for each club belonging to season


            $this->evaluateMatchDay($firstSeason, $matchDayOneSeasonTable, false);
        }
    }

    /**
     * @param MatchDayGame $matchDayGame
     * @param SeedinData $tableEntryDataHomeTeam
     * @param SeedinData $tableEntryDataAwayTeam
     * @return array
     * @throws ORMException
     */
    private function calculatePoints(MatchDayGame $matchDayGame, SeedinData $tableEntryDataHomeTeam, SeedinData $tableEntryDataAwayTeam)
    {
        if ($matchDayGame->getHomeGoalsSecond() > $matchDayGame->getAwayGoalsSecond()) {
            $tableEntryDataHomeTeam->setWins(1);
            $tableEntryDataHomeTeam->setDraws(0);
            $tableEntryDataHomeTeam->setLoses(0);
            $tableEntryDataHomeTeam->setPoints(3);
            $tableEntryDataAwayTeam->setWins(0);
            $tableEntryDataAwayTeam->setDraws(0);
            $tableEntryDataAwayTeam->setLoses(1);
            $tableEntryDataAwayTeam->setPoints(0);
        } elseif ($matchDayGame->getHomeGoalsSecond() < $matchDayGame->getAwayGoalsSecond()) {
            $tableEntryDataHomeTeam->setWins(0);
            $tableEntryDataHomeTeam->setDraws(0);
            $tableEntryDataHomeTeam->setLoses(1);
            $tableEntryDataHomeTeam->setPoints(0);
            $tableEntryDataAwayTeam->setWins(1);
            $tableEntryDataAwayTeam->setDraws(0);
            $tableEntryDataAwayTeam->setLoses(0);
            $tableEntryDataAwayTeam->setPoints(3);
        } elseif ($matchDayGame->getHomeGoalsSecond() == $matchDayGame->getAwayGoalsSecond()) {
            $tableEntryDataHomeTeam->setWins(0);
            $tableEntryDataHomeTeam->setDraws(1);
            $tableEntryDataHomeTeam->setLoses(0);
            $tableEntryDataHomeTeam->setPoints(1);
            $tableEntryDataAwayTeam->setWins(0);
            $tableEntryDataAwayTeam->setDraws(1);
            $tableEntryDataAwayTeam->setLoses(0);
            $tableEntryDataAwayTeam->setPoints(1);
        }
        $entries = array();
        $entries[$tableEntryDataHomeTeam->getClub()->getId()] = $tableEntryDataHomeTeam;
        $entries[$tableEntryDataAwayTeam->getClub()->getId()] = $tableEntryDataAwayTeam;

        return $entries;
    }

    /**
     * @param Season $season
     * @param SeasonTable $seasonTable
     * @param bool $isFirstMatchDay
     * @throws ORMException
     */
    private function evaluateMatchDay(Season $season, SeasonTable $seasonTable, bool $isFirstMatchDay = false): void
    {
        $matchesOfMatchDay = $this->matchDayGameService->getAllMatchesBelongingToMatchDayAndSeason($seasonTable->getMatchDay(), $season);
        $entries = array();
        foreach ($matchesOfMatchDay as $matchDayGame) {
            $tableEntryDataHomeTeam = new SeedinData();
            $tableEntryDataHomeTeam->setGoals($matchDayGame->getHomeGoalsSecond());
            $tableEntryDataHomeTeam->setConcededGoals($matchDayGame->getAwayGoalsSecond());
            $tableEntryDataHomeTeam->setClub($matchDayGame->getHomeTeam());
            $tableEntryDataHomeTeam->setSeasonTable($seasonTable);
            $tableEntryDataHomeTeam->setPosition(1);

            $tableEntryDataAwayTeam = new SeedinData();
            $tableEntryDataAwayTeam->setGoals($matchDayGame->getAwayGoalsSecond());
            $tableEntryDataAwayTeam->setConcededGoals($matchDayGame->getHomeGoalsSecond());
            $tableEntryDataAwayTeam->setClub($matchDayGame->getAwayTeam());
            $tableEntryDataAwayTeam->setSeasonTable($seasonTable);
            $tableEntryDataAwayTeam->setPosition(1);

            $entries = array_merge($entries, $this->calculatePoints($matchDayGame, $tableEntryDataHomeTeam, $tableEntryDataAwayTeam));
        }

        if (!$isFirstMatchDay) {
            $previousSeasonTable = $this->seasonTableService->getPreviousSeason($seasonTable);
            $previousEntries = $this->tableEntryService->getEntriesForTable($previousSeasonTable[0]);
            foreach ($entries as $entry) {
                /** @var SeedinData $entry */
                foreach ($previousEntries as $previousEntry) {
                    if ($entry->getClub()->getId() == $previousEntry->getClub()->getId()) {
                        $entry->setPoints($entry->getPoints() + $previousEntry->getPoints());
                    }
                }
            }
        }

        $orderedEntries = $this->orderSeasonTableOfMatchDay($entries);

        foreach ($entries as $entry) {
            $entry->setPosition($orderedEntries[$entry->getClub()->getName()]);
            $this->tableEntryService->createByData($entry);
        }
    }


    /**
     * @param array $entries
     * @return array
     */
    public function orderSeasonTableOfMatchDay(array $entries)
    {
        $position = 1;
        $club2position = array();

        // sort Points
        $pointValues = array();
        foreach ($entries as $entry) {
            $pointValues[$entry->getPoints()] = $entry->getPoints();
        }

        rsort($pointValues);
        // go over point distribution
        foreach ($pointValues as $pointValue) {
            // chek if multiple clubs have same point amount
            $clubsWithSamePoints = array();
            foreach ($entries as $entry) {
                if ($entry->getPoints() == $pointValue) {
                    $clubsWithSamePoints[] = $entry;
                }
            }
            // sort goals of point class
            $goalValues = array();
            foreach ($clubsWithSamePoints as $entry) {
                $goalValues[$entry->getGoals() - $entry->getConcededGoals()] = $entry->getGoals() - $entry->getConcededGoals();
            }
            sort($goalValues);

            // go over point distribution
            foreach ($goalValues as $goalValue) {
                // go over clubs
                foreach ($clubsWithSamePoints as $entry) {
                    if ($entry->getGoals() - $entry->getConcededGoals() == $goalValue) {
                        $club2position[$entry->getClub()->getName()] = $position;
                        $position++;
                    }
                }
            }
        }
        return $club2position;
    }
}
