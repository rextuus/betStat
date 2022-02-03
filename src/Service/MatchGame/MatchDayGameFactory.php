<?php

namespace App\Service\MatchGame;

use App\Entity\MatchDayGame;

class MatchDayGameFactory
{
    /**
     * @param MatchDayGameData $data
     * @return MatchDayGame
     */
    public function createByData(MatchDayGameData $data)
    {
        $club = $this->createNewInstance();
        $this->mapData($data, $club);
        return $club;
    }

    /**
     * @param MatchDayGameData $data
     * @param MatchDayGame $matchDayGame
     * @return MatchDayGame
     */
    public function mapData(MatchDayGameData $data, MatchDayGame $matchDayGame)
    {
        $matchDayGame->setHomeTeam($data->getHomeTeam());
        $matchDayGame->setAwayTeam($data->getAwayTeam());
        $matchDayGame->setHomeGoalsFirst($data->getHomeGoalsFirst());
        $matchDayGame->setHomeGoalsSecond($data->getHomeGoalsSecond());
        $matchDayGame->setAwayGoalsFirst($data->getAwayGoalsFirst());
        $matchDayGame->setAwayGoalsSecond($data->getAwayGoalsSecond());
        $matchDayGame->setSeason($data->getSeason());
        $matchDayGame->setKickOffDay($data->getKickoffDay());
        $matchDayGame->setMatchDay($data->getMatchDay());
        $matchDayGame->setPlayed($data->isPlayed());

        return $matchDayGame;
    }

    /**
     * @return MatchDayGame
     */
    private function createNewInstance()
    {
        return new MatchDayGame();
    }
}
