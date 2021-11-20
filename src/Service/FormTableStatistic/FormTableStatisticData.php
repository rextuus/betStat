<?php


namespace App\Service\FormTableStatistic;


use App\Entity\Club;
use App\Entity\Season;

class FormTableStatisticData
{
    /**
     * @var Season
     */
    private $season;

    /**
     * @var Club
     */
    private $club;

    /**
     * @var int
     */
    private $winSeries;

    /**
     * @var int
     */
    private $endWith;

    /**
     * @var int
     */
    private $startMatchDay;

    /**
     * @var int
     */
    private $endMatchDay;

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

    /**
     * @return Club
     */
    public function getClub(): Club
    {
        return $this->club;
    }

    /**
     * @param Club $club
     */
    public function setClub(Club $club): void
    {
        $this->club = $club;
    }

    /**
     * @return int
     */
    public function getWinSeries(): int
    {
        return $this->winSeries;
    }

    /**
     * @param int $winSeries
     */
    public function setWinSeries(int $winSeries): void
    {
        $this->winSeries = $winSeries;
    }

    /**
     * @return int
     */
    public function getEndWith(): int
    {
        return $this->endWith;
    }

    /**
     * @param int $endWith
     */
    public function setEndWith(int $endWith): void
    {
        $this->endWith = $endWith;
    }

    /**
     * @return int
     */
    public function getStartMatchDay(): int
    {
        return $this->startMatchDay;
    }

    /**
     * @param int $startMatchDay
     */
    public function setStartMatchDay(int $startMatchDay): void
    {
        $this->startMatchDay = $startMatchDay;
    }

    /**
     * @return int
     */
    public function getEndMatchDay(): int
    {
        return $this->endMatchDay;
    }

    /**
     * @param int $endMatchDay
     */
    public function setEndMatchDay(int $endMatchDay): void
    {
        $this->endMatchDay = $endMatchDay;
    }
}