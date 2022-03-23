<?php

namespace App\Entity;

use App\Repository\RoundRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoundRepository::class)
 */
class Round
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Season::class, inversedBy="rounds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $season;

    /**
     * @ORM\Column(type="integer")
     */
    private $state;

    /**
     * @ORM\OneToMany(targetEntity=Fixture::class, mappedBy="round")
     */
    private $fixtures;

    /**
     * @ORM\Column(type="integer")
     */
    private $sportmonksApiId;

    public function __construct()
    {
        $this->fixtures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection<int, Fixture>
     */
    public function getFixtures(): Collection
    {
        return $this->fixtures;
    }

    public function addFixture(Fixture $fixture): self
    {
        if (!$this->fixtures->contains($fixture)) {
            $this->fixtures[] = $fixture;
            $fixture->setRound($this);
        }

        return $this;
    }

    public function removeFixture(Fixture $fixture): self
    {
        if ($this->fixtures->removeElement($fixture)) {
            // set the owning side to null (unless already changed)
            if ($fixture->getRound() === $this) {
                $fixture->setRound(null);
            }
        }

        return $this;
    }

    public function getSportmonksApiId(): ?int
    {
        return $this->sportmonksApiId;
    }

    public function setSportmonksApiId(int $sportmonksApiId): self
    {
        $this->sportmonksApiId = $sportmonksApiId;

        return $this;
    }
}
