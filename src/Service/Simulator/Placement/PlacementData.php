<?php


namespace App\Service\Simulator\Placement;


use App\Entity\Fixture;
use App\Entity\Simulator;

class PlacementData
{
    /**
     * @var Fixture
     */
    private $fixture;

    /**
     * @var double
     */
    private $commitment;

    /**
     * @var double
     */
    private $profit;

    /**
     * @var bool
     */
    private $wasSuccessfully;

    /**
     * @var Simulator
     */
    private $simulator;


    /**
     * @return Fixture
     */
    public function getFixture(): Fixture
    {
        return $this->fixture;
    }

    /**
     * @param Fixture $fixture
     */
    public function setFixture(Fixture $fixture): void
    {
        $this->fixture = $fixture;
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
    public function getProfit(): float
    {
        return $this->profit;
    }

    /**
     * @param float $profit
     */
    public function setProfit(float $profit): void
    {
        $this->profit = $profit;
    }

    /**
     * @return bool
     */
    public function isWasSuccessfully(): bool
    {
        return $this->wasSuccessfully;
    }

    /**
     * @param bool $wasSuccessfully
     */
    public function setWasSuccessfully(bool $wasSuccessfully): void
    {
        $this->wasSuccessfully = $wasSuccessfully;
    }

    /**
     * @return Simulator
     */
    public function getSimulator(): Simulator
    {
        return $this->simulator;
    }

    /**
     * @param Simulator $simulator
     */
    public function setSimulator(Simulator $simulator): void
    {
        $this->simulator = $simulator;
    }
}
