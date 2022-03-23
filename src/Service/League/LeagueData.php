<?php


namespace App\Service\League;


use App\Entity\League;

class LeagueData
{
    /**
     * @var string
     */
    private $ident;

    /**
     * @var int
     */
    private $apiId;

    /**
     * @var int
     */
    private $sportsmonkApiId;

    /**
     * @return string
     */
    public function getIdent(): string
    {
        return $this->ident;
    }

    /**
     * @param string $ident
     */
    public function setIdent(string $ident): void
    {
        $this->ident = $ident;
    }

    /**
     * @return int
     */
    public function getApiId(): int
    {
        return $this->apiId;
    }

    /**
     * @param int $apiId
     */
    public function setApiId(int $apiId): void
    {
        $this->apiId = $apiId;
    }

    /**
     * @return int
     */
    public function getSportsmonkApiId(): int
    {
        return $this->sportsmonkApiId;
    }

    /**
     * @param int $sportsmonkApiId
     */
    public function setSportsmonkApiId(int $sportsmonkApiId): void
    {
        $this->sportsmonkApiId = $sportsmonkApiId;
    }

    public function initFrom(League $league){
        $leagueData = new self();
        $leagueData->setSportsmonkApiId($league->getSportmonksApiId());
        $leagueData->setApiId($league->getApiId());
        $leagueData->setIdent($league->getIdent());
        return $leagueData;
    }
}