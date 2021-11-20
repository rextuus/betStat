<?php


namespace App\Service\League;


class LeagueData
{
    /**
     * @var string
     */
    private $ident;

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
}