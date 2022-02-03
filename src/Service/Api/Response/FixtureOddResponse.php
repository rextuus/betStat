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
     * @return float
     */
    public function getHomeOdd(): float
    {
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
     * @return float
     */
    public function getAwayOdd(): float
    {
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
     * @return float
     */
    public function getDrawOdd(): float
    {
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
