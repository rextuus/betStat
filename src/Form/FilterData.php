<?php

namespace App\Form;

use DateTime;

class FilterData
{
    /**
     * @var bool|null
     */
    private $oddDecorated;
    /**
     * @var bool|null
     */
    private $played;

    /**
     * @var DateTime|null
     */
    private $from;

    /**
     * @var array|null
     */
    private $leagues;

    /**
     * @var bool|null
     */
    private $useDraws;

    /**
     * @var int|null
     */
    private $maxResults;

    /**
     * @return bool|null
     */
    public function getOddDecorated(): ?bool
    {
        return $this->oddDecorated;
    }

    /**
     * @param bool|null $oddDecorated
     */
    public function setOddDecorated(?bool $oddDecorated): void
    {
        $this->oddDecorated = $oddDecorated;
    }

    /**
     * @return bool|null
     */
    public function getPlayed(): ?bool
    {
        return $this->played;
    }

    /**
     * @param bool|null $played
     */
    public function setPlayed(?bool $played): void
    {
        $this->played = $played;
    }

    /**
     * @return DateTime|null
     */
    public function getFrom(): ?DateTime
    {
        return $this->from;
    }

    /**
     * @param DateTime|null $from
     */
    public function setFrom(?DateTime $from): void
    {
        $this->from = $from;
    }

    /**
     * @return array|null
     */
    public function getLeagues(): ?array
    {
        return $this->leagues;
    }

    /**
     * @param array|null $leagues
     */
    public function setLeagues(?array $leagues): void
    {
        $this->leagues = $leagues;
    }

    /**
     * @return bool|null
     */
    public function getUseDraws(): ?bool
    {
        return $this->useDraws;
    }

    /**
     * @param bool|null $useDraws
     */
    public function setUseDraws(?bool $useDraws): void
    {
        $this->useDraws = $useDraws;
    }

    /**
     * @return int|null
     */
    public function getMaxResults(): ?int
    {
        return $this->maxResults;
    }

    /**
     * @param int|null $maxResults
     */
    public function setMaxResults(?int $maxResults): void
    {
        $this->maxResults = $maxResults;
    }
}
