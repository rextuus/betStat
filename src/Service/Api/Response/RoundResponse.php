<?php


namespace App\Service\Api\Response;


use DateTime;

class RoundResponse
{
    /**
     * @var int
     */
    private $fixtureApiId;

    /**
     * @var bool
     */
    private $status;

    /**
     * @var int
     */
    private $homeHalf;

    /**
     * @var int
     */
    private $homeFull;

    /**
     * @var int
     */
    private $awayHalf;

    /**
     * @var int
     */
    private $awayFull;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * @var int
     */
    private $timeStamp;

    /**
     * @var int
     */
    private $leagueApiId;

    /**
     * @var int
     */
    private $homeTeamApiId;

    /**
     * @var int
     */
    private $awayTeamApiId;


    /**
     * @return int
     */
    public function getFixtureApiId(): int
    {
        return $this->fixtureApiId;
    }

    /**
     * @param int $fixtureApiId
     */
    public function setFixtureApiId(int $fixtureApiId): void
    {
        $this->fixtureApiId = $fixtureApiId;
    }

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     */
    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getHomeHalf(): int
    {
        return $this->homeHalf;
    }

    /**
     * @param int $homeHalf
     */
    public function setHomeHalf(int $homeHalf): void
    {
        $this->homeHalf = $homeHalf;
    }

    /**
     * @return int
     */
    public function getHomeFull(): int
    {
        return $this->homeFull;
    }

    /**
     * @param int $homeFull
     */
    public function setHomeFull(int $homeFull): void
    {
        $this->homeFull = $homeFull;
    }

    /**
     * @return int
     */
    public function getAwayHalf(): int
    {
        return $this->awayHalf;
    }

    /**
     * @param int $awayHalf
     */
    public function setAwayHalf(int $awayHalf): void
    {
        $this->awayHalf = $awayHalf;
    }

    /**
     * @return int
     */
    public function getAwayFull(): int
    {
        return $this->awayFull;
    }

    /**
     * @param int $awayFull
     */
    public function setAwayFull(int $awayFull): void
    {
        $this->awayFull = $awayFull;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getTimeStamp(): int
    {
        return $this->timeStamp;
    }

    /**
     * @param int $timeStamp
     */
    public function setTimeStamp(int $timeStamp): void
    {
        $this->timeStamp = $timeStamp;
    }

    /**
     * @return int
     */
    public function getLeagueApiId(): int
    {
        return $this->leagueApiId;
    }

    /**
     * @param int $leagueApiId
     */
    public function setLeagueApiId(int $leagueApiId): void
    {
        $this->leagueApiId = $leagueApiId;
    }

    /**
     * @return int
     */
    public function getHomeTeamApiId(): int
    {
        return $this->homeTeamApiId;
    }

    /**
     * @param int $homeTeamApiId
     */
    public function setHomeTeamApiId(int $homeTeamApiId): void
    {
        $this->homeTeamApiId = $homeTeamApiId;
    }

    /**
     * @return int
     */
    public function getAwayTeamApiId(): int
    {
        return $this->awayTeamApiId;
    }

    /**
     * @param int $awayTeamApiId
     */
    public function setAwayTeamApiId(int $awayTeamApiId): void
    {
        $this->awayTeamApiId = $awayTeamApiId;
    }
}
