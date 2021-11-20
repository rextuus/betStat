<?php


namespace App\Service\Season;


use App\Entity\Season;

class SeasonFactory
{
    /**
     * @param SeasonData $data
     * @return Season
     */
    public function createByData(SeasonData $data)
    {
        $season = $this->createNewInstance();
        $this->mapData($data, $season);
        return $season;
    }

    /**
     * @param SeasonData $data
     * @param Season $season
     * @return Season
     */
    public function mapData(SeasonData $data, Season $season)
    {
        $season->setStartYear($data->getStartYear());
        $season->setEndYear($data->getEndYear());
        $season->setLeague($data->getLeague());
        foreach ($data->getClubs() as $club) {
            $season->addClub($club);
        }
        return $season;
    }

    /**
     * @return Season
     */
    private function createNewInstance()
    {
        return new Season();
    }
}