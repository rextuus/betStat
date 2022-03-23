<?php

namespace App\Service\Round;

use App\Entity\Round;

class RoundFactory
{
    /**
     * @param RoundData $data
     * @return Round
     */
    public function createByData(RoundData $data)
    {
        $round = $this->createNewInstance();
        $this->mapData($data, $round);
        return $round;
    }

    /**
     * @param RoundData $data
     * @param Round $round
     * @return Round
     */
    public function mapData(RoundData $data, Round $round)
    {
        $round->setSeason($data->getSeason());
        $round->setState($data->getState());
        $round->setSportmonksApiId($data->getSportsmonkApiId());
        foreach ($data->getFixtures() as $fixture){
            $round->addFixture($fixture);
        }
        return $round;
    }

    /**
     * @return Round
     */
    private function createNewInstance()
    {
        return new Round();
    }
}