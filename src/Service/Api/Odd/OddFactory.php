<?php


namespace App\Service\Api\Odd;

use App\Entity\Odd;

class OddFactory
{
    /**
     * @param OddData $data
     * @return Odd
     */
    public function createByData(OddData $data): Odd
    {
        $odd = $this->createNewInstance();
        $this->mapData($data, $odd);
        return $odd;
    }

    /**
     * @param OddData $data
     * @param Odd $odd
     * @return Odd
     */
    public function mapData(OddData $data, Odd $odd)
    {
        $odd->setMatchGame($data->getMatchDayGame());
        $odd->setOddProvider($data->getOddProvider());
        $odd->setHomeOdd($data->getHomeOdd());
        $odd->setDrawOdd($data->getDrawOdd());
        $odd->setAwayOdd($data->getAwayOdd());
        return $odd;
    }

    /**
     * @return Odd
     */
    private function createNewInstance()
    {
        return new Odd();
    }
}