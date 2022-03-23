<?php

namespace App\Entity;

use App\Repository\SeasonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SeasonRepository::class)
 */
class Season
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
    private $startYear;

    /**
     * @ORM\Column(type="integer")
     */
    private $endYear;

    /**
     * @ORM\OneToMany(targetEntity=MatchDayGame::class, mappedBy="season")
     */
    private $matchDayGames;

    /**
     * @ORM\OneToMany(targetEntity=FormTableStatistic::class, mappedBy="season")
     */
    private $formTableStatistics;

    /**
     * @ORM\ManyToOne(targetEntity=League::class, inversedBy="seasons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $league;

    /**
     * @ORM\OneToMany(targetEntity=SeasonTable::class, mappedBy="season")
     */
    private $seasonTables;

    /**
     * @ORM\ManyToMany(targetEntity=Club::class, inversedBy="seasons")
     */
    private $clubs;

    /**
     * @ORM\OneToMany(targetEntity=Fixture::class, mappedBy="season")
     */
    private $fixtures;

    /**
     * @ORM\OneToMany(targetEntity=Seeding::class, mappedBy="season")
     */
    private $seedings;

    /**
     * @ORM\Column(type="integer")
     */
    private $sportmonksApiId;

    /**
     * @ORM\OneToMany(targetEntity=Round::class, mappedBy="season")
     */
    private $rounds;

    public function __construct()
    {
        $this->matchDayGames = new ArrayCollection();
        $this->formTableStatistics = new ArrayCollection();
        $this->seasonTables = new ArrayCollection();
        $this->clubs = new ArrayCollection();
        $this->fixtures = new ArrayCollection();
        $this->seedings = new ArrayCollection();
        $this->rounds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartYear(): ?int
    {
        return $this->startYear;
    }

    public function setStartYear(int $startYear): self
    {
        $this->startYear = $startYear;

        return $this;
    }

    public function getEndYear(): ?int
    {
        return $this->endYear;
    }

    public function setEndYear(int $endYear): self
    {
        $this->endYear = $endYear;

        return $this;
    }

    /**
     * @return Collection|MatchDayGame[]
     */
    public function getMatchDayGames(): Collection
    {
        return $this->matchDayGames;
    }

    public function addMatchDayGame(MatchDayGame $matchDayGame): self
    {
        if (!$this->matchDayGames->contains($matchDayGame)) {
            $this->matchDayGames[] = $matchDayGame;
            $matchDayGame->setSeason($this);
        }

        return $this;
    }

    public function removeMatchDayGame(MatchDayGame $matchDayGame): self
    {
        if ($this->matchDayGames->removeElement($matchDayGame)) {
            // set the owning side to null (unless already changed)
            if ($matchDayGame->getSeason() === $this) {
                $matchDayGame->setSeason(null);
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
            $formTableStatistic->setSeason($this);
        }

        return $this;
    }

    public function removeFormTableStatistic(FormTableStatistic $formTableStatistic): self
    {
        if ($this->formTableStatistics->removeElement($formTableStatistic)) {
            // set the owning side to null (unless already changed)
            if ($formTableStatistic->getSeason() === $this) {
                $formTableStatistic->setSeason(null);
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

    public function getMatchesBelongingToMatchDay(int $matchDay)
    {
        $matches = array();
        foreach ($this->matchDayGames as $matchDayGame) {
            if ($matchDayGame->getMatchDay() === $matchDay) {
                $matches[] = $matchDayGame;
            }
        }
        return $matches;
    }

    /**
     * @return Collection|SeasonTable[]
     */
    public function getSeasonTables(): Collection
    {
        return $this->seasonTables;
    }

    public function addTable(SeasonTable $table): self
    {
        if (!$this->seasonTables->contains($table)) {
            $this->seasonTables[] = $table;
            $table->setSeason($this);
        }

        return $this;
    }

    public function removeTable(SeasonTable $table): self
    {
        if ($this->seasonTables->removeElement($table)) {
            // set the owning side to null (unless already changed)
            if ($table->getSeason() === $this) {
                $table->setSeason(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Club[]
     */
    public function getClubs(): Collection
    {
        return $this->clubs;
    }

    public function addClub(Club $club): self
    {
        if (!$this->clubs->contains($club)) {
            $this->clubs[] = $club;
        }

        return $this;
    }

    public function removeClub(Club $club): self
    {
        $this->clubs->removeElement($club);

        return $this;
    }

    public function getNumberOfClubs()
    {
        return count($this->getClubs());
    }

    /**
     * @return Collection|Fixture[]
     */
    public function getFixtures(): Collection
    {
        return $this->fixtures;
    }

    public function addFixture(Fixture $fixture): self
    {
        if (!$this->fixtures->contains($fixture)) {
            $this->fixtures[] = $fixture;
            $fixture->setSeason($this);
        }

        return $this;
    }

    public function removeFixture(Fixture $fixture): self
    {
        if ($this->fixtures->removeElement($fixture)) {
            // set the owning side to null (unless already changed)
            if ($fixture->getSeason() === $this) {
                $fixture->setSeason(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Seeding[]
     */
    public function getSeedings(): Collection
    {
        return $this->seedings;
    }

    public function addSeeding(Seeding $seeding): self
    {
        if (!$this->seedings->contains($seeding)) {
            $this->seedings[] = $seeding;
            $seeding->setSeason($this);
        }

        return $this;
    }

    public function removeSeeding(Seeding $seeding): self
    {
        if ($this->seedings->removeElement($seeding)) {
            // set the owning side to null (unless already changed)
            if ($seeding->getSeason() === $this) {
                $seeding->setSeason(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Round>
     */
    public function getRounds(): Collection
    {
        return $this->rounds;
    }

    public function addRound(Round $round): self
    {
        if (!$this->rounds->contains($round)) {
            $this->rounds[] = $round;
            $round->setSeason($this);
        }

        return $this;
    }

    public function removeRound(Round $round): self
    {
        if ($this->rounds->removeElement($round)) {
            // set the owning side to null (unless already changed)
            if ($round->getSeason() === $this) {
                $round->setSeason(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSportmonksApiId()
    {
        return $this->sportmonksApiId;
    }

    /**
     * @param mixed $sportmonksApiId
     */
    public function setSportmonksApiId($sportmonksApiId): void
    {
        $this->sportmonksApiId = $sportmonksApiId;
    }
}
