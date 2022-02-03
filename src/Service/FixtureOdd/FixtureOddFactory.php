<?php


namespace App\Service\FixtureOdd;


use App\Entity\FixtureOdd;

class FixtureOddFactory
{
    public function createByData(FixtureOddData $data)
    {
        $fixtureOdd = $this->createNewInstance();
        $this->mapData($data, $fixtureOdd);
        return $fixtureOdd;
    }

    /**
     * @param FixtureOddData $data
     * @param FixtureOdd $fixtureOdd
     * @return FixtureOdd
     */
    public function mapData(FixtureOddData $data, FixtureOdd $fixtureOdd)
    {
        $fixtureOdd->setFixture($data->getFixture());
        $fixtureOdd->setType($data->getType());
        $fixtureOdd->setProvider($data->getProvider());
        $fixtureOdd->setHomeOdd($data->getHomeOdd());
        $fixtureOdd->setDrawOdd($data->getDrawOdd());
        $fixtureOdd->setAwayOdd($data->getAwayOdd());
        return $fixtureOdd;
    }

    private function createNewInstance()
    {
        return new FixtureOdd();
    }
}
