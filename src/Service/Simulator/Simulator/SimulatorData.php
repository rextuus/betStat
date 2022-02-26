<?php


namespace App\Service\Simulator\Simulator;


use App\Entity\Placement;
use App\Entity\SimulatorStrategy;

class SimulatorData
{
    /**
     * @var double
     */
    private $cashRegister;

    /**
     * @var double
     */
    private $commitment;

    /**
     * @var double
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
     * @var bool
     */
    private $isActive;

    /**
     * @var SimulatorStrategy[]
     */
    private $strategies;


    /**
     * @var Placement[]
     */
    private $placements;

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
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return SimulatorStrategy[]
     */
    public function getStrategies(): array
    {
        return $this->strategies;
    }

    /**
     * @param SimulatorStrategy[] $strategies
     */
    public function setStrategies(array $strategies): void
    {
        $this->strategies = $strategies;
    }

    /**
     * @return Placement[]
     */
    public function getPlacements(): array
    {
        return $this->placements;
    }

    /**
     * @param Placement[] $placements
     */
    public function setPlacements(array $placements): void
    {
        $this->placements = $placements;
    }
}
