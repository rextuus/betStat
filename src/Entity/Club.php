<?php

namespace App\Entity;

use App\Repository\ClubRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClubRepository::class)
 */
class Club
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=MatchDayGame::class, mappedBy="homeTeam", orphanRemoval=true)
     */
    private $matchGames;

    /**
     * @ORM\OneToMany(targetEntity=FormTableStatistic::class, mappedBy="club")
     */
    private $formTableStatistics;

    /**
     * @ORM\ManyToOne(targetEntity=League::class, inversedBy="clubs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $league;

    /**
     * @ORM\ManyToMany(targetEntity=Season::class, mappedBy="clubs")
     */
    private $seasons;

    /**
     * @ORM\OneToMany(targetEntity=TableEntry::class, mappedBy="club")
     */
    private $tableEntries;

    public function __construct()
    {
        $this->matchGames = new ArrayCollection();
        $this->formTableStatistics = new ArrayCollection();
        $this->seasons = new ArrayCollection();
        $this->tableEntries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|MatchDayGame[]
     */
    public function getMatchGames(): Collection
    {
        return $this->matchGames;
    }

    public function addMatchGame(MatchDayGame $matchGame): self
    {
        if (!$this->matchGames->contains($matchGame)) {
            $this->matchGames[] = $matchGame;
            $matchGame->setHomeTeam($this);
        }

        return $this;
    }

    public function removeMatchGame(MatchDayGame $matchGame): self
    {
        if ($this->matchGames->removeElement($matchGame)) {
            // set the owning side to null (unless already changed)
            if ($matchGame->getHomeTeam() === $this) {
                $matchGame->setHomeTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FormTableStatistic[]
     */
    public function getFormTableStatistics(): Collection
    {
        return $this->formTableStatistics;
    }

    public function addFormTableStatistic(FormTableStatistic $formTableStatistic): self
    {
        if (!$this->formTableStatistics->contains($formTableStatistic)) {
            $this->formTableStatistics[] = $formTableStatistic;
            $formTableStatistic->setClub($this);
        }

        return $this;
    }

    public function removeFormTableStatistic(FormTableStatistic $formTableStatistic): self
    {
        if ($this->formTableStatistics->removeElement($formTableStatistic)) {
            // set the owning side to null (unless already changed)
            if ($formTableStatistic->getClub() === $this) {
                $formTableStatistic->setClub(null);
            }
        }

        return $this;
    }

    public function getLeague(): ?League
    {
        return $this->league;
    }

    public function setLeague(?League $league): self
    {
        $this->league = $league;

        return $this;
    }

    /**
     * @return Collection|Season[]
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): self
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons[] = $season;
            $season->addClub($this);
        }

        return $this;
    }

    public function removeSeason(Season $season): self
    {
        if ($this->seasons->removeElement($season)) {
            $season->removeClub($this);
        }

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
            $tableEntry->setClub($this);
        }

        return $this;
    }

    public function removeTableEntry(TableEntry $tableEntry): self
    {
        if ($this->tableEntries->removeElement($tableEntry)) {
            // set the owning side to null (unless already changed)
            if ($tableEntry->getClub() === $this) {
                $tableEntry->setClub(null);
            }
        }

        return $this;
    }
}
