<?php


namespace App\Service\Api\Odd;

use App\Entity\MatchDayGame;

class OddData
{
    /**
     * @var MatchDayGame
     */
    private $matchDayGame;

    /**
     * @var float
     */
    private $homeOdd;

    /**
     * @var float
     */
    private $drawOdd;

    /**
     * @var float
     */
    private $awayOdd;

    /**
     * @var string
     */
    private $oddProvider;

    /**
     * @return MatchDayGame
     */
    public function getMatchDayGame(): MatchDayGame
    {
        return $this->matchDayGame;
    }

    /**
     * @param MatchDayGame $matchDayGame
     */
    public function setMatchDayGame(MatchDayGame $matchDayGame): void
    {
        $this->matchDayGame = $matchDayGame;
    }

    /**
     * @return float
     */
    public function getHomeOdd(): float
    {
        return $this->homeOdd;
    }

    /**
     * @param float $homeOdd
     */
    public function setHomeOdd(float $homeOdd): void
    {
        $this->homeOdd = $homeOdd;
    }

    /**
     * @return float
     */
    public function getDrawOdd(): float
    {
        return $this->drawOdd;
    }

    /**
     * @param float $drawOdd
     */
    public function setDrawOdd(float $drawOdd): void
    {
        $this->drawOdd = $drawOdd;
    }

    /**
     * @return float
     */
    public function getAwayOdd(): float
    {
        return $this->awayOdd;
    }

    /**
     * @param float $awayOdd
     */
    public function setAwayOdd(float $awayOdd): void
    {
        $this->awayOdd = $awayOdd;
    }

    /**
     * @return string
     */
    public function getOddProvider(): string
    {
        return $this->oddProvider;
    }

    /**
     * @param string $oddProvider
     */
    public function setOddProvider(string $oddProvider): void
    {
        $this->oddProvider = $oddProvider;
    }
}
