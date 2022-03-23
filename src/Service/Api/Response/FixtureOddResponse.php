<?php


namespace App\Service\Api\Response;


use App\Entity\Fixture;

class FixtureOddResponse
{
    /**
     * @var int
     */
    private $fixtureApiId;

    /**
     * @var string
     */
    private $provider;

    /**
     * @var string
     */
    private $type;

    /**
     * @var float
     */
    private $homeOdd;

    /**
     * @var float
     */
    private $awayOdd;

    /**
     * @var float
     */
    private $drawOdd;

    /**
     * @var bool
     */
    private $isFilled;

    /**
     * @return int
     */
    public function getFixtureApiId(): int
    {
        return $this->fixtureApiId;
    }

    /**
     * @param int $fixtureApiId
     */
    public function setFixtureApiId(int $fixtureApiId): void
    {
        $this->fixtureApiId = $fixtureApiId;
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @param string $provider
     */
    public function setProvider(string $provider): void
    {
        $this->provider = $provider;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return float|null
     */
    public function getHomeOdd(): ?float
    {
        if (is_null($this->drawOdd)){
            dump($this);
        }
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
     * @return float|null
     */
    public function getAwayOdd(): ?float
    {
        if (is_null($this->drawOdd)){
            dump($this);
        }
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
     * @return float|null
     */
    public function getDrawOdd(): ?float
    {
        if (is_null($this->drawOdd)){
            dump($this);
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

    /**
     * @return bool
     */
    public function isFilled(): bool
    {
        return $this->isFilled;
    }

    /**
     * @param bool $isFilled
     */
    public function setIsFilled(bool $isFilled): void
    {
        $this->isFilled = $isFilled;
    }
}
