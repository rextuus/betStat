<?php


namespace App\Service\Fixture\Transport;


class FixtureTransport
{
    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $date;

    /**
     * @var string
     */
    private $round;

    /**
     * @var bool
     */
    private $played;

    /**
     * @var bool
     */
    private $betDecorated;

    /**
     * @var bool
     */
    private $realBetDecorated;

    /**
     * @var bool
     */
    private $isCandidate;

    /**
     * @var float
     */
    private $singleHome;

    /**
     * @var float
     */
    private $singleDraw;

    /**
     * @var float
     */
    private $singleAway;

    /**
     * @var float
     */
    private $homeDouble;

    /**
     * @var float
     */
    private $awayDouble;

    /**
     * @var bool[]
     */
    private $highlighted;

    /**
     * @var int
     */
    private $toBetOn;

    /**
     * @var int
     */
    private $fixtureId;

    /**
     * @var string
     */
    private $homeForm;

    /**
     * @var string
     */
    private $awayForm;

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getRound(): string
    {
        return $this->round;
    }

    /**
     * @param string $round
     */
    public function setRound(string $round): void
    {
        $this->round = $round;
    }

    /**
     * @return bool
     */
    public function isPlayed(): bool
    {
        return $this->played;
    }

    /**
     * @param bool $played
     */
    public function setPlayed(bool $played): void
    {
        $this->played = $played;
    }

    /**
     * @return bool
     */
    public function isBetDecorated(): bool
    {
        return $this->betDecorated;
    }

    /**
     * @param bool $betDecorated
     */
    public function setBetDecorated(bool $betDecorated): void
    {
        $this->betDecorated = $betDecorated;
    }

    /**
     * @return bool
     */
    public function isRealBetDecorated(): bool
    {
        return $this->realBetDecorated;
    }

    /**
     * @param bool $realBetDecorated
     */
    public function setRealBetDecorated(bool $realBetDecorated): void
    {
        $this->realBetDecorated = $realBetDecorated;
    }

    /**
     * @return bool
     */
    public function isCandidate(): bool
    {
        return $this->isCandidate;
    }

    /**
     * @param bool $isCandidate
     */
    public function setIsCandidate(bool $isCandidate): void
    {
        $this->isCandidate = $isCandidate;
    }

    /**
     * @return float
     */
    public function getSingleHome(): ?float
    {
        return $this->singleHome;
    }

    /**
     * @param float $singleHome
     */
    public function setSingleHome(float $singleHome): void
    {
        $this->singleHome = $singleHome;
    }

    /**
     * @return float
     */
    public function getSingleDraw(): ?float
    {
        return $this->singleDraw;
    }

    /**
     * @param float $singleDraw
     */
    public function setSingleDraw(float $singleDraw): void
    {
        $this->singleDraw = $singleDraw;
    }

    /**
     * @return float
     */
    public function getSingleAway(): ?float
    {
        return $this->singleAway;
    }

    /**
     * @param float $singleAway
     */
    public function setSingleAway(float $singleAway): void
    {
        $this->singleAway = $singleAway;
    }

    /**
     * @return float
     */
    public function getHomeDouble(): ?float
    {
        return $this->homeDouble;
    }

    /**
     * @param float $homeDouble
     */
    public function setHomeDouble(float $homeDouble): void
    {
        $this->homeDouble = $homeDouble;
    }

    /**
     * @return float
     */
    public function getAwayDouble(): ?float
    {
        return $this->awayDouble;
    }

    /**
     * @param float $awayDouble
     */
    public function setAwayDouble(float $awayDouble): void
    {
        $this->awayDouble = $awayDouble;
    }

    /**
     * @return bool[]
     */
    public function getHighlighted(): array
    {
        return $this->highlighted;
    }

    /**
     * @param bool[] $highlighted
     */
    public function setHighlighted(array $highlighted): void
    {
        $this->highlighted = $highlighted;
    }

    /**
     * @return int
     */
    public function getToBetOn(): int
    {
        return $this->toBetOn;
    }

    /**
     * @param int $toBetOn
     */
    public function setToBetOn(int $toBetOn): void
    {
        $this->toBetOn = $toBetOn;
    }

    /**
     * @return int
     */
    public function getFixtureId(): int
    {
        return $this->fixtureId;
    }

    /**
     * @param int $fixtureId
     */
    public function setFixtureId(int $fixtureId): void
    {
        $this->fixtureId = $fixtureId;
    }

    /**
     * @return string
     */
    public function getHomeForm(): string
    {
        return $this->homeForm;
    }

    /**
     * @param string $homeForm
     */
    public function setHomeForm(string $homeForm): void
    {
        $this->homeForm = $homeForm;
    }

    /**
     * @return string
     */
    public function getAwayForm(): string
    {
        return $this->awayForm;
    }

    /**
     * @param string $awayForm
     */
    public function setAwayForm(string $awayForm): void
    {
        $this->awayForm = $awayForm;
    }
}
