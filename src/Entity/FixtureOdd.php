<?php

namespace App\Entity;

use App\Repository\FixtureOddRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FixtureOddRepository::class)
 */
class FixtureOdd
{
    const TYPE_CLASSIC = 'classic';
    const TYPE_DOUBLE_CHANCE = 'doubleChance';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $homeOdd;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $awayOdd;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $drawOdd;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $provider;

    /**
     * @ORM\ManyToOne(targetEntity=Fixture::class, inversedBy="fixtureOdds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fixture;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getHomeOdd(): ?float
    {
        return $this->homeOdd;
    }

    /**
     * @param float|null $homeOdd
     */
    public function setHomeOdd(?float $homeOdd): void
    {
        $this->homeOdd = $homeOdd;
    }

    /**
     * @return float|null
     */
    public function getAwayOdd(): ?float
    {
        return $this->awayOdd;
    }

    /**
     * @param float|null $awayOdd
     */
    public function setAwayOdd(?float $awayOdd): void
    {
        $this->awayOdd = $awayOdd;
    }

    /**
     * @return float|null
     */
    public function getDrawOdd(): ?float
    {
        return $this->drawOdd;
    }

    /**
     * @param float|null $drawOdd
     */
    public function setDrawOdd(?float $drawOdd): void
    {
        $this->drawOdd = $drawOdd;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function setProvider(string $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getFixture(): ?Fixture
    {
        return $this->fixture;
    }

    public function setFixture(?Fixture $fixture): self
    {
        $this->fixture = $fixture;

        return $this;
    }
}
