<?php


namespace App\Service\TableEntry;


use App\Entity\TableEntry;

class TableEntryFactory
{
    /**
     * @param TableEntryData $data
     * @return TableEntry
     */
    public function createByData(TableEntryData $data)
    {
        $season = $this->createNewInstance();
        $this->mapData($data, $season);
        return $season;
    }

    /**
     * @param TableEntryData $data
     * @param TableEntry $tableEntry
     * @return TableEntry
     */
    public function mapData(TableEntryData $data, TableEntry $tableEntry)
    {
        $tableEntry->setSeasonTable($data->getSeasonTable());
        $tableEntry->setClub($data->getClub());
        $tableEntry->setWins($data->getWins());
        $tableEntry->setDraws($data->getDraws());
        $tableEntry->setLoses($data->getLoses());
        $tableEntry->setPoints($data->getPoints());
        $tableEntry->setPosition($data->getPosition());
        $tableEntry->setGoals($data->getGoals());
        $tableEntry->setConcededGoals($data->getConcededGoals());
        return $tableEntry;
    }

    /**
     * @return TableEntry
     */
    private function createNewInstance()
    {
        return new TableEntry();
    }
}