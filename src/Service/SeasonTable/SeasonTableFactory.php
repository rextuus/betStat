<?php


namespace App\Service\SeasonTable;

use App\Entity\SeasonTable;

class SeasonTableFactory
{
    /**
     * @param SeasonTableData $data
     * @return SeasonTable
     */
    public function createByData(SeasonTableData $data)
    {
        $season = $this->createNewInstance();
        $this->mapData($data, $season);
        return $season;
    }

    /**
     * @param SeasonTableData $data
     * @param SeasonTable $seasonTable
     * @return SeasonTable
     */
    public function mapData(SeasonTableData $data, SeasonTable $seasonTable)
    {
        $seasonTable->setSeason($data->getSeason());
        $seasonTable->setMatchDay($data->getMatchDay());
        return $seasonTable;
    }

    /**
     * @return SeasonTable
     */
    private function createNewInstance()
    {
        return new SeasonTable();
    }
}