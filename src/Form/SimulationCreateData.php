<?php

namespace App\Form;

class SimulationCreateData
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
     * @var string
     */
    private $commitmentChange;

    /**
     * @var bool
     */
    private $betOnHome;

    /**
     * @var bool
     */
    private $betOnDraw;

    /**
     * @var bool
     */
    private $betOnAway;

    /**
     * @var float
     */
    private $oddBorderLow;

    /**
     * @var float
     */
    private $oddBorderHigh;

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
     * @return bool
     */
    public function isBetOnHome(): bool
    {
        return $this->betOnHome;
    }

    /**
     * @param bool $betOnHome
     */
    public function setBetOnHome(bool $betOnHome): void
    {
        $this->betOnHome = $betOnHome;
    }

    /**
     * @return bool
     */
    public function isBetOnDraw(): bool
    {
        return $this->betOnDraw;
    }

    /**
     * @param bool $betOnDraw
     */
    public function setBetOnDraw(bool $betOnDraw): void
    {
        $this->betOnDraw = $betOnDraw;
    }

    /**
     * @return bool
     */
    public function isBetOnAway(): bool
    {
        return $this->betOnAway;
    }

    /**
     * @param bool $betOnAway
     */
    public function setBetOnAway(bool $betOnAway): void
    {
        $this->betOnAway = $betOnAway;
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
}