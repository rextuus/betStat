<?php


namespace App\Service\LiveFormTable;


use DateTime;

class LiveFormEntry
{
    /**
     * @var string
     */
    private $homeTeamName;

    /**
     * @var string
     */
    private $awayTeamName;

    /**
     * @var bool
     */
    private $homeTeamIsCandidate;

    /**
     * @var bool
     */
    private $awayTeamIsCandidate;

    /**
     * @var DateTime
     */
    private $kickOff;

    /**
     * @var float
     */
    private $targetOdd;

    /**
     * @var float
     */
    private $drawOdd;

    /**
     * @return string
     */
    public function getHomeTeamName(): string
    {
        return $this->homeTeamName;
    }

    /**
     * @param string $homeTeamName
     */
    public function setHomeTeamName(string $homeTeamName): void
    {
        $this->homeTeamName = $homeTeamName;
    }

    /**
     * @return string
     */
    public function getAwayTeamName(): string
    {
        return $this->awayTeamName;
    }

    /**
     * @param string $awayTeamName
     */
    public function setAwayTeamName(string $awayTeamName): void
    {
        $this->awayTeamName = $awayTeamName;
    }

    /**
     * @return bool
     */
    public function isHomeTeamIsCandidate(): bool
    {
        return $this->homeTeamIsCandidate;
    }

    /**
     * @param bool $homeTeamIsCandidate
     */
    public function setHomeTeamIsCandidate(bool $homeTeamIsCandidate): void
    {
        $this->homeTeamIsCandidate = $homeTeamIsCandidate;
    }

    /**
     * @return bool
     */
    public function isAwayTeamIsCandidate(): bool
    {
        return $this->awayTeamIsCandidate;
    }

    /**
     * @param bool $awayTeamIsCandidate
     */
    public function setAwayTeamIsCandidate(bool $awayTeamIsCandidate): void
    {
        $this->awayTeamIsCandidate = $awayTeamIsCandidate;
    }

    /**
     * @return DateTime
     */
    public function getKickOff(): DateTime
    {
        return $this->kickOff;
    }

    /**
     * @param DateTime $kickOff
     */
    public function setKickOff(DateTime $kickOff): void
    {
        $this->kickOff = $kickOff;
    }

    /**
     * @return string
     */
    public function getMatchString(): string
    {
        return $this->getHomeTeamName() . ' - ' . $this->getAwayTeamName();
    }

    /**
     * @return string|null
     */
    public function checkForBetCandidate(): ?string
    {
        if ($this->isHomeTeamIsCandidate() && $this->isAwayTeamIsCandidate()) {
            return null;
        }
        if ($this->isHomeTeamIsCandidate() && !$this->isAwayTeamIsCandidate()) {
            return $this->getAwayTeamName();
        }
        if (!$this->isHomeTeamIsCandidate() && $this->isAwayTeamIsCandidate()) {
            return $this->getHomeTeamName();
        }
        return null;
    }

    /**
     * @return string
     */
    public function getTimeString(): string
    {
        return $this->getKickOff()->format('Y-m-d H:i:s');
    }

    /**
     * @return float
     */
    public function getTargetOdd(): float
    {
        if(is_null($this->targetOdd)){
            return 0.0;
        }
        return $this->targetOdd;
    }

    /**
     * @param float $targetOdd
     */
    public function setTargetOdd(float $targetOdd): void
    {
        $this->targetOdd = $targetOdd;
    }

    /**
     * @return float
     */
    public function getDrawOdd(): float
    {
        if(is_null($this->drawOdd)){
            return 0.0;
        }
        return $this->drawOdd;
    }

    /**
     * @param float $drawOdd
     */
    public function setDrawOdd(float $drawOdd): void
    {
        $this->drawOdd = $drawOdd;
    }
}
