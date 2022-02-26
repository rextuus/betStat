<?php

namespace App\Entity;

use App\Repository\SimulatorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SimulatorRepository::class)
 */
class Simulator
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $cashRegister;

    /**
     * @ORM\Column(type="float")
     */
    private $commitment;

    /**
     * @ORM\Column(type="float")
     */
    private $currentCommitment;

    /**
     * @ORM\Column(type="integer")
     */
    private $madePlacements;

    /**
     * @ORM\Column(type="integer")
     */
    private $wonPlacements;

    /**
     * @ORM\Column(type="integer")
     */
    private $loosePlacements;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToMany(targetEntity=SimulatorStrategy::class, inversedBy="simulators")
     */
    private $strategies;

    /**
     * @ORM\OneToMany(targetEntity=Placement::class, mappedBy="simulator")
     */
    private $placements;

    public function __construct()
    {
        $this->strategies = new ArrayCollection();
        $this->placements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCashRegister(): ?float
    {
        return $this->cashRegister;
    }

    public function setCashRegister(float $cashRegister): self
    {
        $this->cashRegister = $cashRegister;

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

    public function getCurrentCommitment(): ?float
    {
        return $this->currentCommitment;
    }

    public function setCurrentCommitment(float $currentCommitment): self
    {
        $this->currentCommitment = $currentCommitment;

        return $this;
    }

    public function getMadePlacements(): ?int
    {
        return $this->madePlacements;
    }

    public function setMadePlacements(int $madePlacements): self
    {
        $this->madePlacements = $madePlacements;

        return $this;
    }

    public function getWonPlacements(): ?int
    {
        return $this->wonPlacements;
    }

    public function setWonPlacements(int $wonPlacements): self
    {
        $this->wonPlacements = $wonPlacements;

        return $this;
    }

    public function getLoosePlacements(): ?int
    {
        return $this->loosePlacements;
    }

    public function setLoosePlacements(int $loosePlacements): self
    {
        $this->loosePlacements = $loosePlacements;

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

    /**
     * @return Collection|SimulatorStrategy[]
     */
    public function getStrategies(): Collection
    {
        return $this->strategies;
    }

    public function addStrategy(SimulatorStrategy $strategy): self
    {
        if (!$this->strategies->contains($strategy)) {
            $this->strategies[] = $strategy;
        }

        return $this;
    }

    public function removeStrategy(SimulatorStrategy $strategy): self
    {
        $this->strategies->removeElement($strategy);

        return $this;
    }

    /**
     * @return Collection|Placement[]
     */
    public function getPlacements(): Collection
    {
        return $this->placements;
    }

    public function addPlacement(Placement $placement): self
    {
        if (!$this->placements->contains($placement)) {
            $this->placements[] = $placement;
            $placement->setSimulator($this);
        }

        return $this;
    }

    public function removePlacement(Placement $placement): self
    {
        if ($this->placements->removeElement($placement)) {
            // set the owning side to null (unless already changed)
            if ($placement->getSimulator() === $this) {
                $placement->setSimulator(null);
            }
        }

        return $this;
    }
}
