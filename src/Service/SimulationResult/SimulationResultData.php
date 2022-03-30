<?php

namespace App\Service\SimulationResult;

use App\Entity\League;
use DateTime;

class SimulationResultData
{
    /**
     * @var string
     */
    private $ident;

    /**
     * @var DateTime
     */
    private $fromDate;

    /**
     * @var DateTime
     */
    private $untilDate;

    /**
     * @var float
     */
    private $cashRegister;

    /**
     * @var float
     */
    private $commitment;

    /**
     * @var int
     */
    private $madePlacements;

    /**
     * @var int
     */
    private $wonPlacements;

    /**
     * @var int
     */
    private $loosePlacements;

    /**
     * @var float
     */
    private $oddBorderLow;

    /**
     * @var float
     */
    private $oddBorderHigh;

    /**
     * @var float
     */
    private $oddAverage;

    /**
     * @var int
     */
    private $longestLoosingSeries;

    /**
     * @var string[]
     */
    private $placements;

    /**
     * @var League[]
     */
    private $leagues;

    /**
     * @var int
     */
    private $state;

    /**
     * @return string
     */
    public function getIdent(): string
    {
        return $this->ident;
    }

    /**
     * @param string $ident
     */
    public function setIdent(string $ident): void
    {
        $this->ident = $ident;
    }

    /**
     * @return DateTime
     */
    public function getFromDate(): DateTime
    {
        return $this->fromDate;
    }

    /**
     * @param DateTime $fromDate
     */
    public function setFromDate(DateTime $fromDate): void
    {
        $this->fromDate = $fromDate;
    }

    /**
     * @return DateTime
     */
    public function getUntilDate(): DateTime
    {
        return $this->untilDate;
    }

    /**
     * @param DateTime $untilDate
     */
    public function setUntilDate(DateTime $untilDate): void
    {
        $this->untilDate = $untilDate;
    }

    /**
     * @return float
     */
    public function getCashRegister(): float
    {
        return $this->cashRegister;
    }

    /**
     * @param float $cashRegister
     */
    public function setCashRegister(float $cashRegister): void
    {
        $this->cashRegister = $cashRegister;
    }

    /**
     * @return float
     */
    public function getCommitment(): float
    {
        return $this->commitment;
    }

    /**
     * @param float $commitment
     */
    public function setCommitment(float $commitment): void
    {
        $this->commitment = $commitment;
    }

    /**
     * @return int
     */
    public function getMadePlacements(): int
    {
        return $this->madePlacements;
    }

    /**
     * @param int $madePlacements
     */
    public function setMadePlacements(int $madePlacements): void
    {
        $this->madePlacements = $madePlacements;
    }

    /**
     * @return int
     */
    public function getWonPlacements(): int
    {
        return $this->wonPlacements;
    }

    /**
     * @param int $wonPlacements
     */
    public function setWonPlacements(int $wonPlacements): void
    {
        $this->wonPlacements = $wonPlacements;
    }

    /**
     * @return int
     */
    public function getLoosePlacements(): int
    {
        return $this->loosePlacements;
    }

    /**
     * @param int $loosePlacements
     */
    public function setLoosePlacements(int $loosePlacements): void
    {
        $this->loosePlacements = $loosePlacements;
    }

    /**
     * @return float
     */
    public function getOddBorderLow(): float
    {
        return $this->oddBorderLow;
    }

    /**
     * @param float $oddBorderLow
     */
    public function setOddBorderLow(float $oddBorderLow): void
    {
        $this->oddBorderLow = $oddBorderLow;
    }

    /**
     * @return float
     */
    public function getOddBorderHigh(): float
    {
        return $this->oddBorderHigh;
    }

    /**
     * @param float $oddBorderHigh
     */
    public function setOddBorderHigh(float $oddBorderHigh): void
    {
        $this->oddBorderHigh = $oddBorderHigh;
    }

    /**
     * @return float
     */
    public function getOddAverage(): float
    {
        return $this->oddAverage;
    }

    /**
     * @param float $oddAverage
     */
    public function setOddAverage(float $oddAverage): void
    {
        $this->oddAverage = $oddAverage;
    }

    /**
     * @return int
     */
    public function getLongestLoosingSeries(): int
    {
        return $this->longestLoosingSeries;
    }

    /**
     * @param int $longestLoosingSeries
     */
    public function setLongestLoosingSeries(int $longestLoosingSeries): void
    {
        $this->longestLoosingSeries = $longestLoosingSeries;
    }

    /**
     * @return string[]
     */
    public function getPlacements(): array
    {
        return $this->placements;
    }

    /**
     * @param string[] $placements
     */
    public function setPlacements(array $placements): void
    {
        $this->placements = $placements;
    }

    /**
     * @return League[]
     */
    public function getLeagues(): array
    {
        return $this->leagues;
    }

    /**
     * @param League[] $leagues
     */
    public function setLeagues(array $leagues): void
    {
        $this->leagues = $leagues;
    }

    /**
     * @return int
     */
    public function getState(): int
    {
        return $this->state;
    }

    /**
     * @param int $state
     */
    public function setState(int $state): void
    {
        $this->state = $state;
    }
}
