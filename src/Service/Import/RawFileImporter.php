<?php


namespace App\Service\Import;


use App\Entity\Club;
use App\Entity\League;
use App\Entity\MatchDayGame;
use App\Entity\Season;
use App\Entity\StatisticDto;
use App\Entity\StatisticStep;
use App\Service\Club\ClubData;
use App\Service\Club\ClubService;
use App\Service\FormTableStatistic\FormTableStatisticData;
use App\Service\FormTableStatistic\FormTableStatisticService;
use App\Service\League\LeagueData;
use App\Service\League\LeagueService;
use App\Service\MatchGame\MatchDayGameData;
use App\Service\MatchGame\MatchDayGameService;
use App\Service\Season\SeasonData;
use App\Service\Season\SeasonService;
use DateTime;

class RawFileImporter
{
    private const RAW_FILE_DIR = '/var/www/html/betStat/rawData';

    /**
     * @var ClubService
     */
    private $clubService;
    /**
     * @var MatchDayGameService
     */
    private $matchDayGameService;
    /**
     * @var SeasonService
     */
    private $seasonService;
    /**
     * @var FormTableStatisticService
     */
    private $formTableStatisticService;

    /**
     * @var LeagueService
     */
    private $leagueService;

    /**
     * RawFileImporter constructor.
     * @param ClubService $clubService
     * @param MatchDayGameService $matchDayGameService
     * @param SeasonService $seasonService
     * @param FormTableStatisticService $formTableStatisticService
     * @param LeagueService $leagueService
     */
    public function __construct(
        ClubService $clubService,
        MatchDayGameService $matchDayGameService,
        SeasonService $seasonService,
        FormTableStatisticService $formTableStatisticService,
        LeagueService $leagueService
    )
    {
        $this->clubService = $clubService;
        $this->matchDayGameService = $matchDayGameService;
        $this->seasonService = $seasonService;
        $this->formTableStatisticService = $formTableStatisticService;
        $this->leagueService = $leagueService;
    }

    /**
     * @param string $leagueName
     * @throws \Doctrine\ORM\ORMException
     * @throws \Exception
     */
    public function importRawDataFile(string $leagueName)
    {
        $fileName = self::RAW_FILE_DIR . '/' . $leagueName;
        $files = scandir($fileName);

        $league = $this->leagueService->getLeagueByIdent($leagueName);
        if (!$league) {
            $leagueData = new LeagueData();
            $leagueData->setIdent($leagueName);
            $league = $this->leagueService->createByData($leagueData);
        }


        foreach ($files as $file) {
            if (is_file($fileName . '/' . $file)) {
                $rawContent = file_get_contents($fileName . '/' . $file);
                $lines = explode("\n", $rawContent);

                $clubs = $this->getClubsForSeason($lines, $league);

                foreach ($lines as $line) {
                    $match = explode("\t", $line);
                    if ($match[0] !== "") {
                        $matchDayGameData = new MatchDayGameData();
                        $matchDayGameData->setHomeTeam($this->getClubByName($match[0], $league));
                        $matchDayGameData->setAwayTeam($this->getClubByName($match[1], $league));
                        $this->setFirstHalfTimeResult($match[2], $matchDayGameData);
                        $this->setSecondHalfTimeResult($match[3], $matchDayGameData);
                        $matchDayGameData->setKickoffDay($this->createKickoffDate($match[4], $match[5]));
                        $matchDayGameData->setSeason($this->getSeason($match[6], $match[7], $league, $clubs));
                        $matchDayGameData->setMatchDay($match[8]);
                        $this->matchDayGameService->createByData($matchDayGameData);
                    }
                }
            }
        }
    }

    public function calculateFormTablesStatistic(Season $season)
    {


        // get all clubs of a season
        $clubs = $this->seasonService->getAllClubsBelongingToSeason($season);

        // set a winning counter for each club and a marker for last match
        $winningCounter = array();
        $hasWonLastMatch = array();
        $seriesStarted = array();
        foreach ($clubs as $index => $club) {
            /** @var Club $club */
            $winningCounter[$club->getId()] = 0;
            $hasWonLastMatch[$club->getId()] = true;
            $seriesStarted[$club->getId()] = 1;
        }

        $matchDaysOfSeason = count($season->getMatchDayGames());

        foreach (range(1, $matchDaysOfSeason) as $matchDay) {
            $this->proceedMatchDay($season, $matchDay, $winningCounter, $hasWonLastMatch, $seriesStarted);
        }


        // check for every club if win season goes on

        // if win season is broken, create a new statistic entry
    }

    /**
     * @param string $clubName
     * @param League $league
     * @return \App\Entity\Club|null
     * @throws \Doctrine\ORM\ORMException
     */
    private function getClubByName(string $clubName, League $league): ?Club
    {
        $club = $this->clubService->findClubByName($clubName);
        if ($club) {
            return $club;
        } else {
            $clubData = new ClubData();
            $clubData->setName($clubName);
            $clubData->setLeague($league);
            return $this->clubService->createByData($clubData);
        }
    }

    private function setFirstHalfTimeResult(string $result, MatchDayGameData $matchDayGameData)
    {
        $resultValues = explode(':', $result);
        $matchDayGameData->setHomeGoalsFirst($resultValues[0]);
        $matchDayGameData->setAwayGoalsFirst($resultValues[1]);
    }

    private function setSecondHalfTimeResult(string $result, MatchDayGameData $matchDayGameData)
    {
        $resultValues = explode(':', $result);
        $matchDayGameData->setHomeGoalsSecond($resultValues[0]);
        $matchDayGameData->setAwayGoalsSecond($resultValues[1]);
    }

    /**
     * @param string $day
     * @param string $time
     * @return DateTime
     * @throws \Exception
     */
    private function createKickoffDate(string $day, string $time)
    {
        $dateTime = new DateTime(str_replace(':', '-', $day));
        $timeValues = explode(':', $time);
        $dateTime->setTime($timeValues[0], $timeValues[1]);
        return $dateTime;
    }

    /**
     * @param string $startYear
     * @param string $endYear
     * @param League $league
     * @return \App\Entity\Season|null
     * @throws \Doctrine\ORM\ORMException
     */
    private function getSeason(string $startYear, string $endYear, League $league, array $clubs)
    {
        $season = $this->seasonService->findByYears($startYear, $endYear, $league);
        if ($season) {
            return $season;
        } else {
            $seasonData = new SeasonData();
            $seasonData->setStartYear($startYear);
            $seasonData->setEndYear($endYear);
            $seasonData->setLeague($league);
            $seasonData->setClubs($clubs);
            return $this->seasonService->createByData($seasonData);
        }
    }

    private function getWinnerClubId(MatchDayGame $match)
    {
        if ($match->getHomeGoalsSecond() > $match->getAwayGoalsSecond()) {
            return $match->getHomeTeam()->getId();
        } elseif ($match->getAwayGoalsSecond() > $match->getHomeGoalsSecond()) {
            return $match->getAwayTeam()->getId();
        } else {
            return null;
        }
    }

    private function getLooserClubId(MatchDayGame $match)
    {
        if ($match->getHomeGoalsSecond() < $match->getAwayGoalsSecond()) {
            return $match->getHomeTeam()->getId();
        } elseif ($match->getAwayGoalsSecond() < $match->getHomeGoalsSecond()) {
            return $match->getAwayTeam()->getId();
        } else {
            return null;
        }
    }

    /**
     * @param Season $season
     * @param int $matchDay
     * @param array $winningCounter
     * @param array $hasWonLastMatch
     * @param array $seriesStarted
     * @throws \Doctrine\ORM\ORMException
     */
    public function proceedMatchDay(Season $season, int $matchDay, array &$winningCounter, array &$hasWonLastMatch, array &$seriesStarted): void
    {
        // get all matches of a season and day
        $matches = $season->getMatchesBelongingToMatchDay($matchDay);

        foreach ($matches as $index => $match) {
            /** @var MatchDayGame $match */

            // check if club has won current match
            $winnerClubId = $this->getWinnerClubId($match);
            $looserClubId = $this->getLooserClubId($match);

            // no draw
            if ($winnerClubId) {
                // winner ++1
                $winningCounter[$winnerClubId] = $winningCounter[$winnerClubId] + 1;
                $hasWonLastMatch[$winnerClubId] = true;
                // looser reset
                // if series ends store statistic
                if ($hasWonLastMatch[$looserClubId] && $winningCounter[$looserClubId] > 0) {
                    $formTableStatisticData = new FormTableStatisticData();
                    $formTableStatisticData->setWinSeries($winningCounter[$looserClubId]);
                    $formTableStatisticData->setClub($match->getLooserTeam());
                    $formTableStatisticData->setSeason($match->getSeason());
                    $formTableStatisticData->setStartMatchDay($seriesStarted[$looserClubId]);
                    $formTableStatisticData->setEndMatchDay($matchDay);
                    // ends with loose
                    $formTableStatisticData->setEndWith(0);
                    $this->formTableStatisticService->createByData($formTableStatisticData);
                }
                $winningCounter[$looserClubId] = 0;
                $hasWonLastMatch[$looserClubId] = false;
                // set start of series to next matchDay
                $seriesStarted[$looserClubId] = $matchDay + 1;
            } // draw
            else {
                // both series ends
                if ($hasWonLastMatch[$match->getHomeTeam()->getId()] && $winningCounter[$match->getHomeTeam()->getId()] > 0) {
                    $formTableStatisticData = new FormTableStatisticData();
                    $formTableStatisticData->setWinSeries($winningCounter[$match->getHomeTeam()->getId()]);
                    $formTableStatisticData->setClub($match->getHomeTeam());
                    $formTableStatisticData->setSeason($match->getSeason());
                    $formTableStatisticData->setStartMatchDay($seriesStarted[$match->getHomeTeam()->getId()]);
                    $formTableStatisticData->setEndMatchDay($matchDay);
                    // ends with draw
                    $formTableStatisticData->setEndWith(1);
                    $this->formTableStatisticService->createByData($formTableStatisticData);
                }
                $winningCounter[$match->getHomeTeam()->getId()] = 0;
                $hasWonLastMatch[$match->getHomeTeam()->getId()] = false;
                // set start of series to next matchDay
                $seriesStarted[$match->getHomeTeam()->getId()] = $matchDay + 1;

                if ($hasWonLastMatch[$match->getAwayTeam()->getId()] && $winningCounter[$match->getAwayTeam()->getId()] > 0) {
                    $formTableStatisticData2 = new FormTableStatisticData();
                    $formTableStatisticData2->setWinSeries($winningCounter[$match->getAwayTeam()->getId()]);
                    $formTableStatisticData2->setClub($match->getAwayTeam());
                    $formTableStatisticData2->setSeason($match->getSeason());
                    $formTableStatisticData2->setStartMatchDay($seriesStarted[$match->getAwayTeam()->getId()]);
                    $formTableStatisticData2->setEndMatchDay($matchDay);
                    // ends with draw
                    $formTableStatisticData2->setEndWith(1);
                    $this->formTableStatisticService->createByData($formTableStatisticData2);
                }
                $winningCounter[$match->getAwayTeam()->getId()] = 0;
                $hasWonLastMatch[$match->getAwayTeam()->getId()] = false;
                // set start of series to next matchDay
                $seriesStarted[$match->getAwayTeam()->getId()] = $matchDay + 1;
            }
        }
    }

    public function calculateWinChances(string $leagueIdent, int $start, int $end)
    {
        $result = $this->formTableStatisticService->getAllFormTablesWithLength($leagueIdent, $start, $end);
        unset($result[10]);
        dump($result);
        return $result;
    }

    /**
     * @param array $lines
     * @param League $league
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function getClubsForSeason(array $lines, League $league): array
    {
        $clubs = array();
        foreach ($lines as $line) {
            $match = explode("\t", $line);
            if ($match[0] !== "") {
                $clubs[$match[0]] = $this->getClubByName($match[0], $league);
                $clubs[$match[1]] = $this->getClubByName($match[1], $league);
            }
        }
        return $clubs;
    }
}



/*
 * 302 insgesamt
 * 6 siege in folge = die chance dass ein Team mit 5 siegen in serie noch mal gewinnt = #6 / (#6+#5) * 100
 * 5 siege in folge = die chance dass ein Team mit 4 siegen in serie noch mal gewinnt = #5 / (#5+#4) * 100
 * 2 siege in folge = die chance dass ein Team mit 1 siegen in serie noch mal gewinnt = #2 / (#2+#1) * 100
 *
 *
 *
 */