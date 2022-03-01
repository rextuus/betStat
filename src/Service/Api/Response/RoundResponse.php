<?php


namespace App\Service\Api\Response;


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
}
