<?php


namespace App\Service\MatchGame;


use App\Entity\Club;
use App\Entity\Season;
use DateTime;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Time;

class MatchDayGameData
{
    /**
     * @var Club
     */
    private $homeTeam;

    /**
     * @var Club
     */
    private $awayTeam;

    /**
     * @var int
     */
    private $homeGoalsFirst;

    /**
     * @var int
     */
    private $homeGoalsSecond;

    /**
     * @var int
     */
    private $awayGoalsFirst;

    /**
     * @var int
     */
    private $awayGoalsSecond;

    /**
     * @var DateTime
     */
    private $kickoffDay;

    /**
     * @var int
     */
    private $matchDay;

    /**
     * @var Season
     */
    private $season;

    /**
     * @var bool
     */
    private $played;



    /**
     * @return Club
     */
    public function getHomeTeam(): Club
    {
        return $this->homeTeam;
    }

    /**
     * @param Club $homeTeam
     */
    public function setHomeTeam(Club $homeTeam): void
    {
        $this->homeTeam = $homeTeam;
    }

    /**
     * @return Club
     */
    public function getAwayTeam(): Club
    {
        return $this->awayTeam;
    }

    /**
     * @param Club $awayTeam
     */
    public function setAwayTeam(Club $awayTeam): void
    {
        $this->awayTeam = $awayTeam;
    }

    /**
     * @return int
     */
    public function getHomeGoalsFirst(): int
    {
        return $this->homeGoalsFirst;
    }

    /**
     * @param int $homeGoalsFirst
     */
    public function setHomeGoalsFirst(int $homeGoalsFirst): void
    {
        $this->homeGoalsFirst = $homeGoalsFirst;
    }

    /**
     * @return int
     */
    public function getHomeGoalsSecond(): int
    {
        return $this->homeGoalsSecond;
    }

    /**
     * @param int $homeGoalsSecond
     */
    public function setHomeGoalsSecond(int $homeGoalsSecond): void
    {
        $this->homeGoalsSecond = $homeGoalsSecond;
    }

    /**
     * @return int
     */
    public function getAwayGoalsFirst(): int
    {
        return $this->awayGoalsFirst;
    }

    /**
     * @param int $awayGoalsFirst
     */
    public function setAwayGoalsFirst(int $awayGoalsFirst): void
    {
        $this->awayGoalsFirst = $awayGoalsFirst;
    }

    /**
     * @return int
     */
    public function getAwayGoalsSecond(): int
    {
        return $this->awayGoalsSecond;
    }

    /**
     * @param int $awayGoalsSecond
     */
    public function setAwayGoalsSecond(int $awayGoalsSecond): void
    {
        $this->awayGoalsSecond = $awayGoalsSecond;
    }

    /**
     * @return DateTime
     */
    public function getKickoffDay(): DateTime
    {
        return $this->kickoffDay;
    }

    /**
     * @param DateTime $kickoffDay
     */
    public function setKickoffDay(DateTime $kickoffDay): void
    {
        $this->kickoffDay = $kickoffDay;
    }

    /**
     * @return int
     */
    public function getMatchDay(): int
    {
        return $this->matchDay;
    }

    /**
     * @param int $matchDay
     */
    public function setMatchDay(int $matchDay): void
    {
        $this->matchDay = $matchDay;
    }

    /**
     * @return Season
     */
    public function getSeason(): Season
    {
        return $this->season;
    }

    /**
     * @param Season $season
     */
    public function setSeason(Season $season): void
    {
        $this->season = $season;
    }

    /**
     * @return bool
     */
    public function isPlayed(): bool
    {
        return $this->played;
    }

    /**
     * @param bool $played
     */
    public function setPlayed(bool $played): void
    {
        $this->played = $played;
    }
}