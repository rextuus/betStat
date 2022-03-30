<?php

namespace App\Form;

use DateTime;

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
     * @var float
     */
    private $oddBorderLow;

    /**
     * @var float
     */
    private $oddBorderHigh;

    /**
     * @var array|null
     */
    private $leagues;

    /**
     * @var DateTime|null
     */
    private $from;

    /**
     * @var DateTime|null
     */
    private $until;

    /**
     * @var string
     */
    private $ident;

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

    /**
     * @return array|null
     */
    public function getLeagues(): ?array
    {
        return $this->leagues;
    }

    /**
     * @param array|null $leagues
     */
    public function setLeagues(?array $leagues): void
    {
        $this->leagues = $leagues;
    }

    /**
     * @return DateTime|null
     */
    public function getFrom(): ?DateTime
    {
        return $this->from;
    }

    /**
     * @param DateTime|null $from
     */
    public function setFrom(?DateTime $from): void
    {
        $this->from = $from;
    }

    /**
     * @return DateTime|null
     */
    public function getUntil(): ?DateTime
    {
        return $this->until;
    }

    /**
     * @param DateTime|null $until
     */
    public function setUntil(?DateTime $until): void
    {
        $this->until = $until;
    }

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
}
