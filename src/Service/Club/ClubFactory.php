<?php


namespace App\Service\Club;


use App\Entity\Club;

class ClubFactory
{
    public function createByData(ClubData $data)
    {
        $club = $this->createNewInstance();
        $this->mapData($data, $club);
        return $club;
    }

    /**
     * @param ClubData $data
     * @param Club $club
     * @return Club
     */
    public function mapData(ClubData $data, Club $club)
    {
        $club->setName($data->getName());
        $club->setLeague($data->getLeague());
        $club->setCurrentForm($data->getForm());
        $club->setApiId($data->getApiId());
        $club->setSportmonksApiId($data->getSportsmonkApiId());
        $club->setFormRound($data->getFormRound());
        return $club;
    }

    private function createNewInstance()
    {
        return new Club();
    }
}