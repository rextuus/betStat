<?php


namespace App\Service\Api\Response;


use DateTime;

class FixtureResponse
{
    /**
     * @var int
     */
    private $apiId;

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
     * @var int|null
     */
    private $scoreHomeHalfTime;

    /**
     * @var int|null
     */
    private $scoreAwayHalfTime;

    /**
     * @var int|null
     */
    private $scoreHomeFullTime;

    /**
     * @var int|null
     */
    private $scoreAwayFullTime;

    /**
     * @var string
     */
    private $round;

    /**
     * @var int
     */
    private $seasonStartYear;

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

    /**
     * @return int|null
     */
    public function getScoreHomeHalfTime(): ?int
    {
        return $this->scoreHomeHalfTime;
    }

    /**
     * @param int|null $scoreHomeHalfTime
     */
    public function setScoreHomeHalfTime(?int $scoreHomeHalfTime): void
    {
        $this->scoreHomeHalfTime = $scoreHomeHalfTime;
    }

    /**
     * @return int|null
     */
    public function getScoreAwayHalfTime(): ?int
    {
        return $this->scoreAwayHalfTime;
    }

    /**
     * @param int|null $scoreAwayHalfTime
     */
    public function setScoreAwayHalfTime(?int $scoreAwayHalfTime): void
    {
        $this->scoreAwayHalfTime = $scoreAwayHalfTime;
    }

    /**
     * @return int|null
     */
    public function getScoreHomeFullTime(): ?int
    {
        return $this->scoreHomeFullTime;
    }

    /**
     * @param int|null $scoreHomeFullTime
     */
    public function setScoreHomeFullTime(?int $scoreHomeFullTime): void
    {
        $this->scoreHomeFullTime = $scoreHomeFullTime;
    }

    /**
     * @return int|null
     */
    public function getScoreAwayFullTime(): ?int
    {
        return $this->scoreAwayFullTime;
    }

    /**
     * @param int|null $scoreAwayFullTime
     */
    public function setScoreAwayFullTime(?int $scoreAwayFullTime): void
    {
        $this->scoreAwayFullTime = $scoreAwayFullTime;
    }

    /**
     * @return string
     */
    public function getRound(): string
    {
        return $this->round;
    }

    /**
     * @param string $round
     */
    public function setRound(string $round): void
    {
        $this->round = $round;
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
}