<?php


namespace App\Service\SeasonTable;


use App\Entity\Club;
use App\Entity\League;
use App\Entity\Season;

class SeasonTableData
{
    /**
     * @var int
     */
    private $matchDay;

    /**
     * @var Season
     */
    private $season;

    /**
     * @return int
     */
    public function getMatchDay(): int
    {
        return $this->matchDay;
    }

    /**
     * @param int $matchDay
     */
    public function setMatchDay(int $matchDay): void
    {
        $this->matchDay = $matchDay;
    }

    /**
     * @return Season
     */
    public function getSeason(): Season
    {
        return $this->season;
    }

    /**
     * @param Season $season
     */
    public function setSeason(Season $season): void
    {
        $this->season = $season;
    }
}
