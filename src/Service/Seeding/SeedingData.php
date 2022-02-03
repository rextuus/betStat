<?php


namespace App\Service\Seeding;


use App\Entity\Club;
use App\Entity\MatchDayGame;
use App\Entity\Season;
use App\Entity\SeasonTable;
use App\Entity\Seeding;
use App\Entity\TableEntry;
use phpDocumentor\Reflection\DocBlock\Tags\See;

class SeedingData
{
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
    private $looses;

    /**
     * @var int
     */
    private $goals;

    /**
     * @var int
     */
    private $againstGoals;

    /**
     * @var int
     */
    private $points;

    /**
     * @var string
     */
    private $form;

    /**
     * @var Season
     */
    private $season;

    /**
     * @var int
     */
    private $round;

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
    public function getLooses(): int
    {
        return $this->looses;
    }

    /**
     * @param int $looses
     */
    public function setLooses(int $looses): void
    {
        $this->looses = $looses;
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
    public function getAgainstGoals(): int
    {
        return $this->againstGoals;
    }

    /**
     * @param int $againstGoals
     */
    public function setAgainstGoals(int $againstGoals): void
    {
        $this->againstGoals = $againstGoals;
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

    /**
     * @return string
     */
    public function getForm(): string
    {
        return $this->form;
    }

    /**
     * @param string $form
     */
    public function setForm(string $form): void
    {
        $this->form = $form;
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

    /**
     * @return int
     */
    public function getRound(): int
    {
        return $this->round;
    }

    /**
     * @param int $round
     */
    public function setRound(int $round): void
    {
        $this->round = $round;
    }

    public function initFromEntry(Seeding $seeding)
    {
        $this->setWins($seeding->getWins());
        $this->setDraws($seeding->getDraws());
        $this->setLooses($seeding->getLooses());
        $this->setClub($seeding->getClub());
        $this->setGoals($seeding->getGoals());
        $this->setAgainstGoals($seeding->getAgainstGoals());
        $this->setSeason($seeding->getSeason());
        $this->setPoints($seeding->getPoints());
        $this->setPosition($seeding->getPosition());
        $this->setForm($seeding->getForm());
        $this->setRound($seeding->getRound());

        return $this;
    }
}