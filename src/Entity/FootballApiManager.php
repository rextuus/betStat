<?php

namespace App\Entity;

use App\Repository\FootballApiManagerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FootballApiManagerRepository::class)
 */
class FootballApiManager
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $dailyCalls;

    /**
     * @ORM\Column(type="integer")
     */
    private $dailyLimit;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ident;

    /**
     * @ORM\Column(type="datetime")
     */
    private $resetDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDailyCalls(): ?int
    {
        return $this->dailyCalls;
    }

    public function setDailyCalls(int $dailyCalls): self
    {
        $this->dailyCalls = $dailyCalls;

        return $this;
    }

    public function getDailyLimit(): ?int
    {
        return $this->dailyLimit;
    }

    public function setDailyLimit(int $dailyLimit): self
    {
        $this->dailyLimit = $dailyLimit;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getIdent(): ?string
    {
        return $this->ident;
    }

    public function setIdent(string $ident): self
    {
        $this->ident = $ident;

        return $this;
    }

    public function getResetDate(): ?\DateTimeInterface
    {
        return $this->resetDate;
    }

    public function setResetDate(\DateTimeInterface $resetDate): self
    {
        $this->resetDate = $resetDate;

        return $this;
    }
}
