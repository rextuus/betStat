<?php

namespace App\Entity;

use App\Repository\FixtureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FixtureRepository::class)
 */
class Fixture
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
    private $apiId;

    /**
     * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="fixtures")
     */
    private $homeTeam;

    /**
     * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="fixtures")
     */
    private $awayTeam;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $timeStamp;

    /**
     * @ORM\ManyToOne(targetEntity=League::class, inversedBy="fixtures")
     */
    private $league;

    /**
     * @ORM\Column(type="integer")
     */
    private $matchDay;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $scoreHomeHalf;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $scoreHomeFull;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $scoreAwayHalf;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $scoreAwayFull;

    /**
     * @ORM\ManyToOne(targetEntity=Season::class, inversedBy="fixtures")
     */
    private $season;

    /**
     * @ORM\OneToMany(targetEntity=FixtureOdd::class, mappedBy="fixture")
     */
    private $fixtureOdds;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDoubleChanceCandidate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBetDecorated;

    public function __construct()
    {
        $this->fixtureOdds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApiId(): ?int
    {
        return $this->apiId;
    }

    public function setApiId(int $apiId): self
    {
        $this->apiId = $apiId;

        return $this;
    }

    public function getHomeTeam(): ?Club
    {
        return $this->homeTeam;
    }

    public function setHomeTeam(?Club $homeTeam): self
    {
        $this->homeTeam = $homeTeam;

        return $this;
    }

    public function getAwayTeam(): ?Club
    {
        return $this->awayTeam;
    }

    public function setAwayTeam(?Club $awayTeam): self
    {
        $this->awayTeam = $awayTeam;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTimeStamp(): ?int
    {
        return $this->timeStamp;
    }

    public function setTimeStamp(int $timeStamp): self
    {
        $this->timeStamp = $timeStamp;

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

    public function getMatchDay(): ?int
    {
        return $this->matchDay;
    }

    public function setMatchDay(int $matchDay): self
    {
        $this->matchDay = $matchDay;

        return $this;
    }

    public function getScoreHomeHalf(): ?int
    {
        return $this->scoreHomeHalf;
    }

    public function setScoreHomeHalf(?int $scoreHomeHalf): self
    {
        $this->scoreHomeHalf = $scoreHomeHalf;

        return $this;
    }

    public function getScoreHomeFull(): ?int
    {
        return $this->scoreHomeFull;
    }

    public function setScoreHomeFull(?int $scoreHomeFull): self
    {
        $this->scoreHomeFull = $scoreHomeFull;

        return $this;
    }

    public function getScoreAwayHalf(): ?int
    {
        return $this->scoreAwayHalf;
    }

    public function setScoreAwayHalf(?int $scoreAwayHalf): self
    {
        $this->scoreAwayHalf = $scoreAwayHalf;

        return $this;
    }

    public function getScoreAwayFull(): ?int
    {
        return $this->scoreAwayFull;
    }

    public function setScoreAwayFull(?int $scoreAwayFull): self
    {
        $this->scoreAwayFull = $scoreAwayFull;

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
     * @return Collection|FixtureOdd[]
     */
    public function getFixtureOdds(): Collection
    {
        return $this->fixtureOdds;
    }

    public function addFixtureOdd(FixtureOdd $fixtureOdd): self
    {
        if (!$this->fixtureOdds->contains($fixtureOdd)) {
            $this->fixtureOdds[] = $fixtureOdd;
            $fixtureOdd->setFixture($this);
        }

        return $this;
    }

    public function removeFixtureOdd(FixtureOdd $fixtureOdd): self
    {
        if ($this->fixtureOdds->removeElement($fixtureOdd)) {
            // set the owning side to null (unless already changed)
            if ($fixtureOdd->getFixture() === $this) {
                $fixtureOdd->setFixture(null);
            }
        }

        return $this;
    }

    public function getIsDoubleChanceCandidate(): ?bool
    {
        return $this->isDoubleChanceCandidate;
    }

    public function setIsDoubleChanceCandidate(bool $isDoubleChanceCandidate): self
    {
        $this->isDoubleChanceCandidate = $isDoubleChanceCandidate;

        return $this;
    }

    public function getIsBetDecorated(): ?bool
    {
        return $this->isBetDecorated;
    }

    public function setIsBetDecorated(bool $isBetDecorated): self
    {
        $this->isBetDecorated = $isBetDecorated;

        return $this;
    }
}
