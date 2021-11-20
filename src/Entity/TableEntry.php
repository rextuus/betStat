<?php

namespace App\Entity;

use App\Repository\TableEntryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TableEntryRepository::class)
 */
class TableEntry
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=SeasonTable::class, inversedBy="tableEntries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $seasonTable;

    /**
     * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="tableEntries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $club;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\Column(type="integer")
     */
    private $wins;

    /**
     * @ORM\Column(type="integer")
     */
    private $draws;

    /**
     * @ORM\Column(type="integer")
     */
    private $loses;

    /**
     * @ORM\Column(type="integer")
     */
    private $goals;

    /**
     * @ORM\Column(type="integer")
     */
    private $concededGoals;

    /**
     * @ORM\Column(type="integer")
     */
    private $points;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeasonTable(): ?SeasonTable
    {
        return $this->seasonTable;
    }

    public function setSeasonTable(?SeasonTable $seasonTable): self
    {
        $this->seasonTable = $seasonTable;

        return $this;
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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getWins(): ?int
    {
        return $this->wins;
    }

    public function setWins(int $wins): self
    {
        $this->wins = $wins;

        return $this;
    }

    public function getDraws(): ?int
    {
        return $this->draws;
    }

    public function setDraws(int $draws): self
    {
        $this->draws = $draws;

        return $this;
    }

    public function getLoses(): ?int
    {
        return $this->loses;
    }

    public function setLoses(int $loses): self
    {
        $this->loses = $loses;

        return $this;
    }

    public function getGoals(): ?int
    {
        return $this->goals;
    }

    public function setGoals(int $goals): self
    {
        $this->goals = $goals;

        return $this;
    }

    public function getConcededGoals(): ?int
    {
        return $this->concededGoals;
    }

    public function setConcededGoals(int $concededGoals): self
    {
        $this->concededGoals = $concededGoals;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;

        return $this;
    }
}
