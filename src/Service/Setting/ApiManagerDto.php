<?php

namespace App\Service\Setting;

use DateTime;

class ApiManagerDto
{
    /**
     * @var string
     */
    private $ident;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var int
     */
    private $current;

    /**
     * @var bool
     */
    private $active;

    /**
     * @var string
     */
    private $resetDate;

    /**
     * @var string
     */
    private $limitReachedColor;

    /**
     * @var string
     */
    private $lastResetColor;

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

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getCurrent(): int
    {
        return $this->current;
    }

    /**
     * @param int $current
     */
    public function setCurrent(int $current): void
    {
        $this->current = $current;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getPercentage(): float
    {
        $percentage = $this->getCurrent() / $this->getLimit() * 100;
        return round($percentage, 2);
    }

    /**
     * @return string
     */
    public function getResetDate(): string
    {
        return $this->resetDate;
    }

    /**
     * @param string $resetDate
     */
    public function setResetDate(string $resetDate): void
    {
        $this->resetDate = $resetDate;
    }

    /**
     * @return string
     */
    public function getLimitReachedColor(): string
    {
        return $this->limitReachedColor;
    }

    /**
     * @param string $limitReachedColor
     */
    public function setLimitReachedColor(string $limitReachedColor): void
    {
        $this->limitReachedColor = $limitReachedColor;
    }

    /**
     * @return string
     */
    public function getLastResetColor(): string
    {
        return $this->lastResetColor;
    }

    /**
     * @param string $lastResetColor
     */
    public function setLastResetColor(string $lastResetColor): void
    {
        $this->lastResetColor = $lastResetColor;
    }



}
