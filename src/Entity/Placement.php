<?php

namespace App\Entity;

use App\Repository\PlacementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlacementRepository::class)
 */
class Placement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Fixture::class, inversedBy="placements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fixture;

    /**
     * @ORM\Column(type="float")
     */
    private $commitment;

    /**
     * @ORM\Column(type="float")
     */
    private $profit;

    /**
     * @ORM\Column(type="boolean")
     */
    private $wasSuccessfully;

    /**
     * @ORM\ManyToOne(targetEntity=Simulator::class, inversedBy="placements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $simulator;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCommitment(): ?float
    {
        return $this->commitment;
    }

    public function setCommitment(float $commitment): self
    {
        $this->commitment = $commitment;

        return $this;
    }

    public function getProfit(): ?float
    {
        return $this->profit;
    }

    public function setProfit(float $profit): self
    {
        $this->profit = $profit;

        return $this;
    }

    public function getWasSuccessfully(): ?bool
    {
        return $this->wasSuccessfully;
    }

    public function setWasSuccessfully(bool $wasSuccessfully): self
    {
        $this->wasSuccessfully = $wasSuccessfully;

        return $this;
    }

    public function getSimulator(): ?Simulator
    {
        return $this->simulator;
    }

    public function setSimulator(?Simulator $simulator): self
    {
        $this->simulator = $simulator;

        return $this;
    }
}
