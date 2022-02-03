<?php

namespace App\Entity;

use App\Repository\FormTableStatisticRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormTableStatisticRepository::class)
 */
class FormTableStatistic
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="formTableStatistics")
     */
    private $club;

    /**
     * @ORM\Column(type="integer")
     */
    private $winSeries;

    /**
     * @ORM\Column(type="integer")
     */
    private $endedWith;

    /**
     * @ORM\ManyToOne(targetEntity=Season::class, inversedBy="formTableStatistics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $season;

    /**
     * @ORM\Column(type="integer")
     */
    private $startMatchDay;

    /**
     * @ORM\Column(type="integer")
     */
    private $endMatchDay;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): self
    {
        $this->club = $club;

        return $this;
    }

    public function getWinSeries(): ?int
    {
        return $this->winSeries;
    }

    public function setWinSeries(int $winSeries): self
    {
        $this->winSeries = $winSeries;

        return $this;
    }

    public function getEndedWith(): ?int
    {
        return $this->endedWith;
    }

    public function setEndedWith(int $endedWith): self
    {
        $this->endedWith = $endedWith;

        return $this;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getStartMatchDay(): ?int
    {
        return $this->startMatchDay;
    }

    public function setStartMatchDay(int $startMatchDay): self
    {
        $this->startMatchDay = $startMatchDay;

        return $this;
    }

    public function getEndMatchDay(): ?int
    {
        return $this->endMatchDay;
    }

    public function setEndMatchDay(int $endMatchDay): self
    {
        $this->endMatchDay = $endMatchDay;

        return $this;
    }
}
