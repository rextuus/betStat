<?php


namespace App\Service\League;


use App\Entity\League;

class LeagueFactory
{
    /**
     * @param LeagueData $data
     * @return League
     */
    public function createByData(LeagueData $data)
    {
        $league = $this->createNewInstance();
        $this->mapData($data, $league);
        return $league;
    }

    /**
     * @param LeagueData $data
     * @param League $league
     * @return League
     */
    public function mapData(LeagueData $data, League $league)
    {
        $league->setIdent($data->getIdent());

        return $league;
    }

    /**
     * @return League
     */
    private function createNewInstance()
    {
        return new League();
    }
}