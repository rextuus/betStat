<?php

namespace App\Entity;

use App\Repository\OddRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OddRepository::class)
 */
class Odd
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MatchDayGame::class, inversedBy="odds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matchGame;

    /**
     * @ORM\Column(type="float")
     */
    private $homeOdd;

    /**
     * @ORM\Column(type="float")
     */
    private $drawOdd;

    /**
     * @ORM\Column(type="float")
     */
    private $awayOdd;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $oddProvider;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatchGame(): ?MatchDayGame
    {
        return $this->matchGame;
    }

    public function setMatchGame(?MatchDayGame $matchGame): self
    {
        $this->matchGame = $matchGame;

        return $this;
    }

    public function getHomeOdd(): ?float
    {
        return $this->homeOdd;
    }

    public function setHomeOdd(float $homeOdd): self
    {
        $this->homeOdd = $homeOdd;

        return $this;
    }

    public function getDrawOdd(): ?float
    {
        return $this->drawOdd;
    }

    public function setDrawOdd(float $drawOdd): self
    {
        $this->drawOdd = $drawOdd;

        return $this;
    }

    public function getAwayOdd(): ?float
    {
        return $this->awayOdd;
    }

    public function setAwayOdd(float $awayOdd): self
    {
        $this->awayOdd = $awayOdd;

        return $this;
    }

    public function getOddProvider(): ?string
    {
        return $this->oddProvider;
    }

    public function setOddProvider(string $oddProvider): self
    {
        $this->oddProvider = $oddProvider;

        return $this;
    }
}
