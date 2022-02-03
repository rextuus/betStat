<?php


namespace App\Service\Season;


use App\Entity\Club;
use App\Entity\League;

class SeasonData
{
    /**
     * @var int
     */
    private $startYear;

    /**
     * @var int
     */
    private $endYear;

    /**
     * @var League
     */
    private $league;

    /**
     * @var Club[]
     */
    private $clubs;


    /**
     * @return int
     */
    public function getStartYear(): int
    {
        return $this->startYear;
    }

    /**
     * @param int $startYear
     */
    public function setStartYear(int $startYear): void
    {
        $this->startYear = $startYear;
    }

    /**
     * @return int
     */
    public function getEndYear(): int
    {
        return $this->endYear;
    }

    /**
     * @param int $endYear
     */
    public function setEndYear(int $endYear): void
    {
        $this->endYear = $endYear;
    }

    /**
     * @return League
     */
    public function getLeague(): League
    {
        return $this->league;
    }

    /**
     * @param League $league
     */
    public function setLeague(League $league): void
    {
        $this->league = $league;
    }

    /**
     * @return Club[]
     */
    public function getClubs(): array
    {
        return $this->clubs;
    }

    /**
     * @param Club[] $clubs
     */
    public function setClubs(array $clubs): void
    {
        $this->clubs = $clubs;
    }
}