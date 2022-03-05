<?php


namespace App\Service\Fixture;


use App\Entity\Club;
use App\Entity\Fixture;
use App\Entity\FixtureOdd;
use App\Entity\League;
use App\Entity\Season;
use DateTime;
use DateTimeInterface;

class FixtureData
{
    /**
     * @var int
     */
    private $apiId;

    /**
     * @var Club
     */
    private $homeTeam;

    /**
     * @var Club
     */
    private $awayTeam;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * @var int
     */
    private $timeStamp;

    /**
     * @var League
     */
    private $league;

    /**
     * @var int
     */
    private $matchDay;

    /**
     * @var int|null
     */
    private $scoreHomeHalf;

    /**
     * @var int|null
     */
    private $scoreHomeFull;

    /**
     * @var int|null
     */
    private $scoreAwayHalf;

    /**
     * @var int|null
     */
    private $scoreAwayFull;

    /**
     * @var Season
     */
    private $season;

    /**
     * @var FixtureOdd[]
     */
    private $odds;

    /**
     * @var bool
     */
    private $isBetDecorated;


    /**
     * @var boolean
     */
    private $isDoubleChanceCandidate;

    /**
     * @var DateTimeInterface|null
     */
    private $oddDecorationDate;

    /**
     * @var boolean
     */
    private $played;

    /**
     * @var DateTimeInterface|null
     */
    private $resultDecorationDate;

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
     * @return int|null
     */
    public function getScoreHomeHalf(): ?int
    {
        return $this->scoreHomeHalf;
    }

    /**
     * @param int|null $scoreHomeHalf
     */
    public function setScoreHomeHalf(?int $scoreHomeHalf): void
    {
        $this->scoreHomeHalf = $scoreHomeHalf;
    }

    /**
     * @return int|null
     */
    public function getScoreHomeFull(): ?int
    {
        return $this->scoreHomeFull;
    }

    /**
     * @param int|null $scoreHomeFull
     */
    public function setScoreHomeFull(?int $scoreHomeFull): void
    {
        $this->scoreHomeFull = $scoreHomeFull;
    }

    /**
     * @return int|null
     */
    public function getScoreAwayHalf(): ?int
    {
        return $this->scoreAwayHalf;
    }

    /**
     * @param int|null $scoreAwayHalf
     */
    public function setScoreAwayHalf(?int $scoreAwayHalf): void
    {
        $this->scoreAwayHalf = $scoreAwayHalf;
    }

    /**
     * @return int|null
     */
    public function getScoreAwayFull(): ?int
    {
        return $this->scoreAwayFull;
    }

    /**
     * @param int|null $scoreAwayFull
     */
    public function setScoreAwayFull(?int $scoreAwayFull): void
    {
        $this->scoreAwayFull = $scoreAwayFull;
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
     * @return FixtureOdd[]|null
     */
    public function getOdds(): ?array
    {
        return $this->odds;
    }

    /**
     * @param FixtureOdd[] $odds
     */
    public function setOdds(array $odds): void
    {
        $this->odds = $odds;
    }

    /**
     * @return bool
     */
    public function isDoubleChanceCandidate(): bool
    {
        return $this->isDoubleChanceCandidate;
    }

    /**
     * @param bool $isDoubleChanceCandidate
     */
    public function setIsDoubleChanceCandidate(bool $isDoubleChanceCandidate): void
    {
        $this->isDoubleChanceCandidate = $isDoubleChanceCandidate;
    }

    /**
     * @return bool
     */
    public function isBetDecorated(): bool
    {
        return $this->isBetDecorated;
    }

    /**
     * @param bool $isBetDecorated
     */
    public function setIsBetDecorated(bool $isBetDecorated): void
    {
        $this->isBetDecorated = $isBetDecorated;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getOddDecorationDate(): ?DateTimeInterface
    {
        return $this->oddDecorationDate;
    }

    /**
     * @param DateTimeInterface|null $oddDecorationDate
     */
    public function setOddDecorationDate(?DateTimeInterface $oddDecorationDate): void
    {
        $this->oddDecorationDate = $oddDecorationDate;
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

    /**
     * @return DateTimeInterface|null
     */
    public function getResultDecorationDate(): ?DateTimeInterface
    {
        return $this->resultDecorationDate;
    }

    /**
     * @param DateTimeInterface|null $resultDecorationDate
     */
    public function setResultDecorationDate(?DateTimeInterface $resultDecorationDate): void
    {
        $this->resultDecorationDate = $resultDecorationDate;
    }



    /**
     * @param Fixture $fixture
     * @return FixtureData
     */
    public function initFrom(Fixture $fixture): FixtureData
    {
        $fixtureData = new self();
        $fixtureData->setApiId($fixture->getApiId());
        $fixtureData->setDate($fixture->getDate());
        $fixtureData->setTimeStamp($fixture->getTimeStamp());
        $fixtureData->setLeague($fixture->getLeague());
        $fixtureData->setSeason($fixture->getSeason());
        $fixtureData->setMatchDay($fixture->getMatchDay());
        $fixtureData->setHomeTeam($fixture->getHomeTeam());
        $fixtureData->setAwayTeam($fixture->getAwayTeam());
        $fixtureData->setScoreHomeHalf($fixture->getScoreHomeHalf());
        $fixtureData->setScoreHomeFull($fixture->getScoreHomeFull());
        $fixtureData->setScoreAwayHalf($fixture->getScoreAwayHalf());
        $fixtureData->setScoreAwayFull($fixture->getScoreAwayFull());
        $fixtureData->setIsDoubleChanceCandidate($fixture->getIsDoubleChanceCandidate());
        $fixtureData->setIsBetDecorated($fixture->getIsBetDecorated());
        $fixtureData->setOddDecorationDate($fixture->getOddDecorationDate());
        $fixtureData->setResultDecorationDate($fixture->getResultDecorationDate());
        $fixtureData->setPlayed($fixture->isPlayed());

        return $fixtureData;
    }
}