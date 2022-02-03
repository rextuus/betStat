<?php

namespace App\Entity;

use App\Repository\SeedingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SeedingRepository::class)
 */
class Seeding
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="seedings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $club;

    /**
     * @ORM\ManyToOne(targetEntity=Season::class, inversedBy="seedings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $season;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\Column(type="integer")
     */
    private $round;

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
    private $looses;

    /**
     * @ORM\Column(type="integer")
     */
    private $goals;

    /**
     * @ORM\Column(type="integer")
     */
    private $againstGoals;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $form;

    /**
     * @ORM\Column(type="integer")
     */
    private $points;

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

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): self
    {
        $this->season = $season;

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

    public function getRound(): ?int
    {
        return $this->round;
    }

    public function setRound(int $round): self
    {
        $this->round = $round;

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

    public function getLooses(): ?int
    {
        return $this->looses;
    }

    public function setLooses(int $looses): self
    {
        $this->looses = $looses;

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

    public function getAgainstGoals(): ?int
    {
        return $this->againstGoals;
    }

    public function setAgainstGoals(int $againstGoals): self
    {
        $this->againstGoals = $againstGoals;

        return $this;
    }

    public function getForm(): ?string
    {
        return $this->form;
    }

    public function setForm(string $form): self
    {
        $this->form = $form;

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
