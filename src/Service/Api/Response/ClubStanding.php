<?php


namespace App\Service\Api\Response;


class ClubStanding
{
    /**
     * @var int
     */
    private $rank;

    /**
     * @var string
     */
    private $form;

    /**
     * @var int
     */
    private $clubId;

    /**
     * @var string
     */
    private $clubName;

    /**
     * @var int
     */
    private $wins;

    /**
     * @var int
     */
    private $draws;

    /**
     * @var int
     */
    private $looses;

    /**
     * @var int
     */
    private $goals;

    /**
     * @var int
     */
    private $againstGoals;

    /**
     * @var int
     */
    private $points;

    /**
     * @var int
     */
    private $round;

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * @param int $rank
     */
    public function setRank(int $rank): void
    {
        $this->rank = $rank;
    }

    /**
     * @return string
     */
    public function getForm(): string
    {
        return $this->form;
    }

    /**
     * @param string $form
     */
    public function setForm(string $form): void
    {
        $this->form = $form;
    }

    /**
     * @return int
     */
    public function getClubId(): int
    {
        return $this->clubId;
    }

    /**
     * @param int $clubId
     */
    public function setClubId(int $clubId): void
    {
        $this->clubId = $clubId;
    }

    /**
     * @return string
     */
    public function getClubName(): string
    {
        return $this->clubName;
    }

    /**
     * @param string $clubName
     */
    public function setClubName(string $clubName): void
    {
        $this->clubName = $clubName;
    }

    /**
     * @return int
     */
    public function getWins(): int
    {
        return $this->wins;
    }

    /**
     * @param int $wins
     */
    public function setWins(int $wins): void
    {
        $this->wins = $wins;
    }

    /**
     * @return int
     */
    public function getDraws(): int
    {
        return $this->draws;
    }

    /**
     * @param int $draws
     */
    public function setDraws(int $draws): void
    {
        $this->draws = $draws;
    }

    /**
     * @return int
     */
    public function getLooses(): int
    {
        return $this->looses;
    }

    /**
     * @param int $looses
     */
    public function setLooses(int $looses): void
    {
        $this->looses = $looses;
    }

    /**
     * @return int
     */
    public function getGoals(): int
    {
        return $this->goals;
    }

    /**
     * @param int $goals
     */
    public function setGoals(int $goals): void
    {
        $this->goals = $goals;
    }

    /**
     * @return int
     */
    public function getAgainstGoals(): int
    {
        return $this->againstGoals;
    }

    /**
     * @param int $againstGoals
     */
    public function setAgainstGoals(int $againstGoals): void
    {
        $this->againstGoals = $againstGoals;
    }

    /**
     * @return int
     */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * @param int $points
     */
    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    /**
     * @return int
     */
    public function getRound(): int
    {
        return $this->round;
    }

    /**
     * @param int $round
     */
    public function setRound(int $round): void
    {
        $this->round = $round;
    }
}
