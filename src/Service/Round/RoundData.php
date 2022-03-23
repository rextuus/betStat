<?php

namespace App\Service\Round;

use App\Entity\Fixture;
use App\Entity\Round;
use App\Entity\Season;

class RoundData
{
    /**
     * @var Season
     */
    private $season;

    /**
     * @var integer
     */
    private $state;

    /**
     * @var Fixture[]
     */
    private $fixtures;

    /**
     * @var integer
     */
    private $sportsmonkApiId;

    /**
     * @return Season
     */
    public function getSeason(): Season
    {
        return $this->season;
    }

    /**
     * @param Season $season
     */
    public function setSeason(Season $season): void
    {
        $this->season = $season;
    }

    /**
     * @return int
     */
    public function getState(): int
    {
        return $this->state;
    }

    /**
     * @param int $state
     */
    public function setState(int $state): void
    {
        $this->state = $state;
    }

    /**
     * @return Fixture[]
     */
    public function getFixtures(): array
    {
        return $this->fixtures;
    }

    /**
     * @param Fixture[] $fixtures
     */
    public function setFixtures(array $fixtures): void
    {
        $this->fixtures = $fixtures;
    }

    /**
     * @return int
     */
    public function getSportsmonkApiId(): int
    {
        return $this->sportsmonkApiId;
    }

    /**
     * @param int $sportsmonkApiId
     */
    public function setSportsmonkApiId(int $sportsmonkApiId): void
    {
        $this->sportsmonkApiId = $sportsmonkApiId;
    }

    public function initFrom(Round $round)
    {
        $roundData = new self();
        $roundData->setSportsmonkApiId($round->getSportmonksApiId());
        $roundData->setFixtures($round->getFixtures()->toArray());
        $roundData->setSeason($round->getSeason());
        $roundData->setState($round->getState());
        return $roundData;
    }
}