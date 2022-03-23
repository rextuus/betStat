<?php


namespace App\Service\Fixture;


use App\Entity\Fixture;

class FixtureFactory
{
    public function createByData(FixtureData $data)
    {
        $fixture = $this->createNewInstance();
        $this->mapData($data, $fixture);
        return $fixture;
    }

    /**
     * @param FixtureData $data
     * @param Fixture $fixture
     * @return Fixture
     */
    public function mapData(FixtureData $data, Fixture $fixture)
    {
        $fixture->setApiId($data->getApiId());
        $fixture->setSportmonksApiId($data->getSportsmonkApiId());
        $fixture->setRound($data->getRound());
        $fixture->setLeague($data->getLeague());
        $fixture->setSeason($data->getSeason());
        $fixture->setHomeTeam($data->getHomeTeam());
        $fixture->setAwayTeam($data->getAwayTeam());
        $fixture->setDate($data->getDate());
        $fixture->setTimeStamp($data->getTimeStamp());
        $fixture->setMatchDay($data->getMatchDay());
        $fixture->setScoreHomeHalf($data->getScoreHomeHalf());
        $fixture->setScoreHomeFull($data->getScoreHomeFull());
        $fixture->setScoreAwayHalf($data->getScoreAwayHalf());
        $fixture->setScoreAwayFull($data->getScoreAwayFull());
        $fixture->setIsDoubleChanceCandidate($data->isDoubleChanceCandidate());
        $fixture->setIsBetDecorated($data->isBetDecorated());
        if (!is_null($data->getOdds())){
            foreach ($data->getOdds() as $odd){
                $fixture->addFixtureOdd($odd);
            }
        }
        if (!is_null($data->getOddDecorationDate())){
            $fixture->setOddDecorationDate($data->getOddDecorationDate());
        }
        $fixture->setPlayed($data->isPlayed());
        if (!is_null($data->getResultDecorationDate())){
            $fixture->setResultDecorationDate($data->getResultDecorationDate());
        }
        return $fixture;
    }

    private function createNewInstance()
    {
        return new Fixture();
    }
}
