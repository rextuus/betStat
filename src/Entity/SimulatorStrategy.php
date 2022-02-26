<?php

namespace App\Entity;

use App\Repository\SimulatorStrategyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SimulatorStrategyRepository::class)
 */
class SimulatorStrategy
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
    private $standardCommitment;

    /**
     * @ORM\Column(type="float")
     */
    private $commitmentChange;

    /**
     * @ORM\Column(type="integer")
     */
    private $resetAfterLoses;

    /**
     * @ORM\ManyToMany(targetEntity=Simulator::class, mappedBy="strategies")
     */
    private $simulators;

    public function __construct()
    {
        $this->simulators = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStandardCommitment(): ?float
    {
        return $this->standardCommitment;
    }

    public function setStandardCommitment(float $standardCommitment): self
    {
        $this->standardCommitment = $standardCommitment;

        return $this;
    }

    public function getCommitmentChange(): ?float
    {
        return $this->commitmentChange;
    }

    public function setCommitmentChange(float $commitmentChange): self
    {
        $this->commitmentChange = $commitmentChange;

        return $this;
    }

    public function getResetAfterLoses(): ?int
    {
        return $this->resetAfterLoses;
    }

    public function setResetAfterLoses(int $resetAfterLoses): self
    {
        $this->resetAfterLoses = $resetAfterLoses;

        return $this;
    }

    /**
     * @return Collection|Simulator[]
     */
    public function getSimulators(): Collection
    {
        return $this->simulators;
    }

    public function addSimulator(Simulator $simulator): self
    {
        if (!$this->simulators->contains($simulator)) {
            $this->simulators[] = $simulator;
            $simulator->addStrategy($this);
        }

        return $this;
    }

    public function removeSimulator(Simulator $simulator): self
    {
        if ($this->simulators->removeElement($simulator)) {
            $simulator->removeStrategy($this);
        }

        return $this;
    }
}
