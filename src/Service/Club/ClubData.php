<?php


namespace App\Service\Club;


use App\Entity\League;

class ClubData
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var League
     */
    private $league;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return League
     */
    public function getLeague(): League
    {
        return $this->league;
    }

    /**
     * @param League $league
     */
    public function setLeague(League $league): void
    {
        $this->league = $league;
    }
}
