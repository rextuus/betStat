<?php

namespace App\Entity;

use App\Repository\SeasonTableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SeasonTableRepository::class)
 */
class SeasonTable
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
    private $matchDay;

    /**
     * @ORM\ManyToOne(targetEntity=Season::class, inversedBy="tables")
     * @ORM\JoinColumn(nullable=false)
     */
    private $season;

    /**
     * @ORM\OneToMany(targetEntity=TableEntry::class, mappedBy="seasonTable")
     */
    private $tableEntries;

    public function __construct()
    {
        $this->tableEntries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatchDay(): ?int
    {
        return $this->matchDay;
    }

    public function setMatchDay(int $matchDay): self
    {
        $this->matchDay = $matchDay;

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

    /**
     * @return Collection|TableEntry[]
     */
    public function getTableEntries(): Collection
    {
        return $this->tableEntries;
    }

    public function addTableEntry(TableEntry $tableEntry): self
    {
        if (!$this->tableEntries->contains($tableEntry)) {
            $this->tableEntries[] = $tableEntry;
            $tableEntry->setSeasonTable($this);
        }

        return $this;
    }

    public function removeTableEntry(TableEntry $tableEntry): self
    {
        if ($this->tableEntries->removeElement($tableEntry)) {
            // set the owning side to null (unless already changed)
            if ($tableEntry->getSeasonTable() === $this) {
                $tableEntry->setSeasonTable(null);
            }
        }

        return $this;
    }
}
