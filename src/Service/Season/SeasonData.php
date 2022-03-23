<?php


namespace App\Service\Season;


use App\Entity\Club;
use App\Entity\League;
use App\Entity\Season;

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
     * @var int
     */
    private $sportsmonkApiId;

    /**
     * @var bool
     */
    private $roundsCompleted;

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

    /**
     * @return int
     */
    public function getSportsmonkApiId(): int
    {
        return $this->sportsmonkApiId;
    }

    /**
     * @param int $sportsmonkApiId
     */
    public function setSportsmonkApiId(int $sportsmonkApiId): void
    {
        $this->sportsmonkApiId = $sportsmonkApiId;
    }

    /**
     * @return bool
     */
    public function isRoundsCompleted(): bool
    {
        return $this->roundsCompleted;
    }

    /**
     * @param bool $roundsCompleted
     */
    public function setRoundsCompleted(bool $roundsCompleted): void
    {
        $this->roundsCompleted = $roundsCompleted;
    }



    public function initFrom(Season $season)
    {
        $seasonData = new self();
        $seasonData->setLeague($season->getLeague());
        $seasonData->setClubs($season->getClubs()->getValues());
        $seasonData->setRoundsCompleted($season->getRoundsCompleted());
        $seasonData->setStartYear($season->getStartYear());
        $seasonData->setEndYear($season->getEndYear());
        $seasonData->setSportsmonkApiId($season->getSportmonksApiId());
        return $seasonData;
    }
}