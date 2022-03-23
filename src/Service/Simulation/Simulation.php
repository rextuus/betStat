<?php

namespace App\Service\Simulation;

use App\Entity\Fixture;
use App\Form\SimulationCreateData;

class Simulation
{
    /**
     * @var float
     */
    private $cashRegister;

    /**
     * @var float
     */
    private $commitment;

    /**
     * @var float
     */
    private $currentCommitment;

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
     * @var string
     */
    private $commitmentChange;

    /**
     * @var float
     */
    private $oddAverage;

    /**
     * @var string[]
     */
    private $placements;

    /**
     * @var int
     */
    private $longestLoosingSeries;

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
     * @return float
     */
    public function getCurrentCommitment(): float
    {
        return $this->currentCommitment;
    }

    /**
     * @param float $currentCommitment
     */
    public function setCurrentCommitment(float $currentCommitment): void
    {
        $this->currentCommitment = $currentCommitment;
    }

    /**
     * @return int
     */
    public function getMadePlacements(): int
    {
        if (is_null($this->madePlacements)){
            return 0;
        }
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
        if (is_null($this->wonPlacements)){
            return 0;
        }
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
        if (is_null($this->loosePlacements)){
            return 0;
        }
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
     * @return string
     */
    public function getCommitmentChange(): string
    {
        return $this->commitmentChange;
    }

    /**
     * @param string $commitmentChange
     */
    public function setCommitmentChange(string $commitmentChange): void
    {
        $this->commitmentChange = $commitmentChange;
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
     * @return float
     */
    public function getWinRatePercentage(): float
    {
        return round($this->wonPlacements/$this->madePlacements * 100.0, 2);
    }

    public function initFrom(SimulationCreateData $data)
    {
        $this->setCashRegister($data->getCashRegister());
        $this->setCommitment($data->getCommitment());
        $this->setCurrentCommitment($data->getCommitment());
        $this->setOddBorderHigh($data->getOddBorderHigh());
        $this->setOddBorderLow($data->getOddBorderLow());
        $this->setCommitmentChange($data->getCommitmentChange());
        $this->setPlacements([]);

        return $this;
    }

    public function addPlacement(string $placement)
    {
        $this->placements[] = $placement;
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
}
