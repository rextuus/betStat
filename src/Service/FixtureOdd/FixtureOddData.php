<?php


namespace App\Service\FixtureOdd;


use App\Entity\Fixture;
use App\Entity\FixtureOdd;

class FixtureOddData
{
    /**
     * @var Fixture
     */
    private $fixture;

    /**
     * @var string
     */
    private $type;

    /**
     * @var double
     */
    private $homeOdd;

    /**
     * @var double
     */
    private $awayOdd;

    /**
     * @var double
     */
    private $drawOdd;

    /**
     * @var string
     */
    private $provider;

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
     * @param FixtureOdd $fixtureOdd
     * @return FixtureOddData
     */
    public function initFrom(FixtureOdd $fixtureOdd): FixtureOddData
    {
        $fixtureOddData = new self();
        $fixtureOddData->setType($fixtureOdd->getType());
        $fixtureOddData->setFixture($fixtureOdd->getFixture());
        $fixtureOddData->setProvider($fixtureOdd->getProvider());
        $fixtureOddData->setHomeOdd($fixtureOdd->getHomeOdd());
        $fixtureOddData->setDrawOdd($fixtureOdd->getDrawOdd());
        $fixtureOddData->setAwayOdd($fixtureOdd->getAwayOdd());

        return $fixtureOddData;
    }
}