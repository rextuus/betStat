<?php

namespace App\Entity;

use App\Repository\LeagueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LeagueRepository::class)
 */
class League
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
    private $ident;

    /**
     * @ORM\OneToMany(targetEntity=Club::class, mappedBy="league")
     */
    private $clubs;

    /**
     * @ORM\OneToMany(targetEntity=Season::class, mappedBy="league")
     */
    private $seasons;

    /**
     * @ORM\OneToMany(targetEntity=UrlResponseBackup::class, mappedBy="league")
     */
    private $urlResponseBackups;

    /**
     * @ORM\Column(type="integer")
     */
    private $apiId;

    /**
     * @ORM\OneToMany(targetEntity=Fixture::class, mappedBy="league")
     */
    private $fixtures;

    public function __construct()
    {
        $this->clubs = new ArrayCollection();
        $this->seasons = new ArrayCollection();
        $this->urlResponseBackups = new ArrayCollection();
        $this->fixtures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdent(): ?string
    {
        return $this->ident;
    }

    public function setIdent(string $ident): self
    {
        $this->ident = $ident;

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
            $club->setLeague($this);
        }

        return $this;
    }

    public function removeClub(Club $club): self
    {
        if ($this->clubs->removeElement($club)) {
            // set the owning side to null (unless already changed)
            if ($club->getLeague() === $this) {
                $club->setLeague(null);
            }
        }

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
            $season->setLeague($this);
        }

        return $this;
    }

    public function removeSeason(Season $season): self
    {
        if ($this->seasons->removeElement($season)) {
            // set the owning side to null (unless already changed)
            if ($season->getLeague() === $this) {
                $season->setLeague(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UrlResponseBackup[]
     */
    public function getUrlResponseBackups(): Collection
    {
        return $this->urlResponseBackups;
    }

    public function addUrlResponseBackup(UrlResponseBackup $urlResponseBackup): self
    {
        if (!$this->urlResponseBackups->contains($urlResponseBackup)) {
            $this->urlResponseBackups[] = $urlResponseBackup;
            $urlResponseBackup->setLeague($this);
        }

        return $this;
    }

    public function removeUrlResponseBackup(UrlResponseBackup $urlResponseBackup): self
    {
        if ($this->urlResponseBackups->removeElement($urlResponseBackup)) {
            // set the owning side to null (unless already changed)
            if ($urlResponseBackup->getLeague() === $this) {
                $urlResponseBackup->setLeague(null);
            }
        }

        return $this;
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
            $fixture->setLeague($this);
        }

        return $this;
    }

    public function removeFixture(Fixture $fixture): self
    {
        if ($this->fixtures->removeElement($fixture)) {
            // set the owning side to null (unless already changed)
            if ($fixture->getLeague() === $this) {
                $fixture->setLeague(null);
            }
        }

        return $this;
    }
}
