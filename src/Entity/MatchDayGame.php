<?php

namespace App\Entity;

use App\Repository\MatchDayGameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatchDayGameRepository::class)
 */
class MatchDayGame
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="matchGames")
     * @ORM\JoinColumn(nullable=false)
     */
    private $homeTeam;

    /**
     * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="matchGames")
     * @ORM\JoinColumn(nullable=false)
     */
    private $awayTeam;

    /**
     * @ORM\Column(type="integer")
     */
    private $homeGoalsFirst;

    /**
     * @ORM\Column(type="integer")
     */
    private $homeGoalsSecond;

    /**
     * @ORM\Column(type="integer")
     */
    private $awayGoalsFirst;

    /**
     * @ORM\Column(type="integer")
     */
    private $awayGoalsSecond;

    /**
     * @ORM\Column(type="datetime")
     */
    private $kickOffDay;

    /**
     * @ORM\Column(type="integer")
     */
    private $matchDay;

    /**
     * @ORM\ManyToOne(targetEntity=Season::class, inversedBy="matchDayGames")
     * @ORM\JoinColumn(nullable=false)
     */
    private $season;

    /**
     * @ORM\OneToMany(targetEntity=Odd::class, mappedBy="matchGame")
     */
    private $odds;

    /**
     * @ORM\Column(type="boolean")
     */
    private $played;

    public function __construct()
    {
        $this->odds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getHomeGoalsFirst(): ?int
    {
        return $this->homeGoalsFirst;
    }

    public function setHomeGoalsFirst(int $homeGoalsFirst): self
    {
        $this->homeGoalsFirst = $homeGoalsFirst;

        return $this;
    }

    public function getHomeGoalsSecond(): ?int
    {
        return $this->homeGoalsSecond;
    }

    public function setHomeGoalsSecond(int $homeGoalsSecond): self
    {
        $this->homeGoalsSecond = $homeGoalsSecond;

        return $this;
    }

    public function getAwayGoalsFirst(): ?int
    {
        return $this->awayGoalsFirst;
    }

    public function setAwayGoalsFirst(int $awayGoalsFirst): self
    {
        $this->awayGoalsFirst = $awayGoalsFirst;

        return $this;
    }

    public function getAwayGoalsSecond(): ?int
    {
        return $this->awayGoalsSecond;
    }

    public function setAwayGoalsSecond(int $awayGoalsSecond): self
    {
        $this->awayGoalsSecond = $awayGoalsSecond;

        return $this;
    }

    public function getKickOffDay(): ?\DateTimeInterface
    {
        return $this->kickOffDay;
    }

    public function setKickOffDay(\DateTimeInterface $kickOffDay): self
    {
        $this->kickOffDay = $kickOffDay;

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

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getWinnerTeam()
    {
        if ($this->getHomeGoalsSecond() > $this->getAwayGoalsSecond()) {
            return $this->getHomeTeam();
        } elseif ($this->getAwayGoalsSecond() > $this->getHomeGoalsSecond()) {
            return $this->getAwayTeam();
        } else {
            return null;
        }
    }

    public function getLooserTeam()
    {
        if ($this->getHomeGoalsSecond() < $this->getAwayGoalsSecond()) {
            return $this->getHomeTeam();
        } elseif ($this->getAwayGoalsSecond() < $this->getHomeGoalsSecond()) {
            return $this->getAwayTeam();
        } else {
            return null;
        }
    }

    public function printNice()
    {
        $toString = sprintf(
            "%s [%d] - [%d] %s",
            $this->getHomeTeam()->getName(),
            $this->getHomeGoalsSecond(),
            $this->getAwayGoalsSecond(),
            $this->getAwayTeam()->getName()
        );

        return $toString;
    }

    /**
     * @return Collection|Odd[]
     */
    public function getOdds(): Collection
    {
        return $this->odds;
    }

    public function addOdd(Odd $odd): self
    {
        if (!$this->odds->contains($odd)) {
            $this->odds[] = $odd;
            $odd->setMatchGame($this);
        }

        return $this;
    }

    public function removeOdd(Odd $odd): self
    {
        if ($this->odds->removeElement($odd)) {
            // set the owning side to null (unless already changed)
            if ($odd->getMatchGame() === $this) {
                $odd->setMatchGame(null);
            }
        }

        return $this;
    }

    public function getPlayed(): ?bool
    {
        return $this->played;
    }

    public function setPlayed(bool $played): self
    {
        $this->played = $played;

        return $this;
    }

    public function getTargetTeam(Club $target): int
    {
        if ($target === $this->getHomeTeam()){
            return 1;
        }elseif($target === $this->getAwayTeam()){
            return 2;
        }else{
            return 0;
        }

    }
}
