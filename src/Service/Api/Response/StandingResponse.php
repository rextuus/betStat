<?php


namespace App\Service\Api\Response;


class StandingResponse
{
    /**
     * @var int
     */
    private $leagueId;

    /**
     * @var string
     */
    private $leagueName;

    /**
     * @var int
     */
    private $seasonStartYear;

    /**
     * @var ClubStanding[]
     */
    private $clubStandings;

    /**
     * @return int
     */
    public function getLeagueId(): int
    {
        return $this->leagueId;
    }

    /**
     * @param int $leagueId
     */
    public function setLeagueId(int $leagueId): void
    {
        $this->leagueId = $leagueId;
    }

    /**
     * @return string
     */
    public function getLeagueName(): string
    {
        return $this->leagueName;
    }

    /**
     * @param string $leagueName
     */
    public function setLeagueName(string $leagueName): void
    {
        $this->leagueName = $leagueName;
    }

    /**
     * @return int
     */
    public function getSeasonStartYear(): int
    {
        return $this->seasonStartYear;
    }

    /**
     * @param int $seasonStartYear
     */
    public function setSeasonStartYear(int $seasonStartYear): void
    {
        $this->seasonStartYear = $seasonStartYear;
    }

    /**
     * @return ClubStanding[]
     */
    public function getClubStandings(): array
    {
        return $this->clubStandings;
    }

    /**
     * @param ClubStanding[] $clubStandings
     */
    public function setClubStandings(array $clubStandings): void
    {
        $this->clubStandings = $clubStandings;
    }
}
