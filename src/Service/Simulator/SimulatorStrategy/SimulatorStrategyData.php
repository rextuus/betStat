<?php


namespace App\Service\Simulator\SimulatorStrategy;


use App\Entity\Simulator;

class SimulatorStrategyData
{
    /**
     * @var double
     */
    private $standardCommitment;

    /**
     * @var double
     */
    private $commitmentChange;

    /**
     * @var int
     */
    private $resetAfterLoses;

    /**
     * @return float
     */
    public function getStandardCommitment(): float
    {
        return $this->standardCommitment;
    }

    /**
     * @param float $standardCommitment
     */
    public function setStandardCommitment(float $standardCommitment): void
    {
        $this->standardCommitment = $standardCommitment;
    }

    /**
     * @return float
     */
    public function getCommitmentChange(): float
    {
        return $this->commitmentChange;
    }

    /**
     * @param float $commitmentChange
     */
    public function setCommitmentChange(float $commitmentChange): void
    {
        $this->commitmentChange = $commitmentChange;
    }

    /**
     * @return int
     */
    public function getResetAfterLoses(): int
    {
        return $this->resetAfterLoses;
    }

    /**
     * @param int $resetAfterLoses
     */
    public function setResetAfterLoses(int $resetAfterLoses): void
    {
        $this->resetAfterLoses = $resetAfterLoses;
    }
}