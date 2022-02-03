<?php


namespace App\Entity;


class StatisticStep
{
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
}
