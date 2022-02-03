<?php


namespace App\Service\Seeding;


use App\Entity\Seeding;
use App\Entity\TableEntry;

class SeedingFactory
{
    /**
     * @param SeedingData $data
     * @return Seeding
     */
    public function createByData(SeedingData $data)
    {
        $season = $this->createNewInstance();
        $this->mapData($data, $season);
        return $season;
    }

    /**
     * @param SeedingData $data
     * @param Seeding $seeding
     * @return Seeding
     */
    public function mapData(SeedingData $data, Seeding $seeding)
    {
        $seeding->setWins($data->getWins());
        $seeding->setDraws($data->getDraws());
        $seeding->setLooses($data->getLooses());
        $seeding->setClub($data->getClub());
        $seeding->setGoals($data->getGoals());
        $seeding->setAgainstGoals($data->getAgainstGoals());
        $seeding->setSeason($data->getSeason());
        $seeding->setPoints($data->getPoints());
        $seeding->setPosition($data->getPosition());
        $seeding->setRound($data->getRound());
        $seeding->setForm($data->getForm());
        return $seeding;
    }

    /**
     * @return Seeding
     */
    private function createNewInstance()
    {
        return new Seeding();
    }
}