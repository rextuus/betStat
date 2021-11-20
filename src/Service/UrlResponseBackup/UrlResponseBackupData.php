<?php


namespace App\Service\UrlResponseBackup;


use App\Entity\League;
use DateTime;

class UrlResponseBackupData
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var DateTime
     */
    private $collectionDate;

    /**
     * @var string
     */
    private $rawContent;

    /**
     * @var League
     */
    private $league;

    /**
     * @var int
     */
    private $matchDay;

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return DateTime
     */
    public function getCollectionDate(): DateTime
    {
        return $this->collectionDate;
    }

    /**
     * @param DateTime $collectionDate
     */
    public function setCollectionDate(DateTime $collectionDate): void
    {
        $this->collectionDate = $collectionDate;
    }

    /**
     * @return string
     */
    public function getRawContent(): string
    {
        return $this->rawContent;
    }

    /**
     * @param string $rawContent
     */
    public function setRawContent(string $rawContent): void
    {
        $this->rawContent = $rawContent;
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
}