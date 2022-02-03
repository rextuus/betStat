<?php


namespace App\Service\TableEntry;


use App\Entity\Club;
use App\Entity\MatchDayGame;
use App\Entity\SeasonTable;
use App\Entity\TableEntry;

class TableEntryData
{
    /**
     * @var SeasonTable
     */
    private $seasonTable;

    /**
     * @var Club
     */
    private $club;

    /**
     * @var int
     */
    private $position;

    /**
     * @var int
     */
    private $wins;

    /**
     * @var int
     */
    private $draws;

    /**
     * @var int
     */
    private $loses;

    /**
     * @var int
     */
    private $goals;

    /**
     * @var int
     */
    private $concededGoals;

    /**
     * @var int
     */
    private $points;

    /**
     * @return SeasonTable
     */
    public function getSeasonTable(): SeasonTable
    {
        return $this->seasonTable;
    }

    /**
     * @param SeasonTable $seasonTable
     */
    public function setSeasonTable(SeasonTable $seasonTable): void
    {
        $this->seasonTable = $seasonTable;
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
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * @return int
     */
    public function getWins(): int
    {
        return $this->wins;
    }

    /**
     * @param int $wins
     */
    public function setWins(int $wins): void
    {
        $this->wins = $wins;
    }

    /**
     * @return int
     */
    public function getDraws(): int
    {
        return $this->draws;
    }

    /**
     * @param int $draws
     */
    public function setDraws(int $draws): void
    {
        $this->draws = $draws;
    }

    /**
     * @return int
     */
    public function getLoses(): int
    {
        return $this->loses;
    }

    /**
     * @param int $loses
     */
    public function setLoses(int $loses): void
    {
        $this->loses = $loses;
    }

    /**
     * @return int
     */
    public function getGoals(): int
    {
        return $this->goals;
    }

    /**
     * @param int $goals
     */
    public function setGoals(int $goals): void
    {
        $this->goals = $goals;
    }

    /**
     * @return int
     */
    public function getConcededGoals(): int
    {
        return $this->concededGoals;
    }

    /**
     * @param int $concededGoals
     */
    public function setConcededGoals(int $concededGoals): void
    {
        $this->concededGoals = $concededGoals;
    }

    /**
     * @return int
     */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * @param int $points
     */
    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    public function initFromEntry(TableEntry $entry)
    {
        $this->setWins($entry->getWins());
        $this->setLoses($entry->getLoses());
        $this->setDraws($entry->getDraws());
        $this->setClub($entry->getClub());
        $this->setGoals($entry->getGoals());
        $this->setConcededGoals($entry->getConcededGoals());
        $this->setSeasonTable($entry->getSeasonTable());
        $this->setPoints($entry->getPoints());
        $this->setPosition($entry->getPosition());

        return $this;
    }
}