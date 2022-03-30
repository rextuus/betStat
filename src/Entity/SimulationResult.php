<?php

namespace App\Entity;

use App\Form\SimulationCreateData;
use App\Repository\SimulationResultRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SimulationResultRepository::class)
 */
class SimulationResult
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fromDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $untilDate;

    /**
     * @ORM\Column(type="float")
     */
    private $cashRegister;

    /**
     * @ORM\Column(type="float")
     */
    private $commitment;

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
     * @ORM\Column(type="float")
     */
    private $oddBorderLow;

    /**
     * @ORM\Column(type="float")
     */
    private $oddBorderHigh;

    /**
     * @ORM\Column(type="float")
     */
    private $oddAverage;

    /**
     * @ORM\Column(type="integer")
     */
    private $longestLoosingSeries;

    /**
     * @ORM\Column(type="array")
     */
    private $placements = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $state;

    /**
     * @ORM\Column(type="float")
     */
    private $currentCommitment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $commitmentChange;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalPages;

    /**
     * @ORM\Column(type="integer")
     */
    private $currentPage;

    /**
     * @ORM\Column(type="integer")
     */
    private $commitmentChanger;

    /**
     * @ORM\Column(type="array")
     */
    private $leagues = [];

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $parentId;

    public function __construct()
    {
        $this->leagues = new ArrayCollection();
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

    public function getFromDate(): ?\DateTimeInterface
    {
        return $this->fromDate;
    }

    public function setFromDate(?\DateTimeInterface $fromDate): self
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    public function getUntilDate(): ?\DateTimeInterface
    {
        return $this->untilDate;
    }

    public function setUntilDate(?\DateTimeInterface $untilDate): self
    {
        $this->untilDate = $untilDate;

        return $this;
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

    public function getOddBorderLow(): ?float
    {
        return $this->oddBorderLow;
    }

    public function setOddBorderLow(float $oddBorderLow): self
    {
        $this->oddBorderLow = $oddBorderLow;

        return $this;
    }

    public function getOddBorderHigh(): ?float
    {
        return $this->oddBorderHigh;
    }

    public function setOddBorderHigh(float $oddBorderHigh): self
    {
        $this->oddBorderHigh = $oddBorderHigh;

        return $this;
    }

    public function getOddAverage(): ?float
    {
        return $this->oddAverage;
    }

    public function setOddAverage(float $oddAverage): self
    {
        $this->oddAverage = $oddAverage;

        return $this;
    }

    public function getLongestLoosingSeries(): ?int
    {
        return $this->longestLoosingSeries;
    }

    public function setLongestLoosingSeries(int $longestLoosingSeries): self
    {
        $this->longestLoosingSeries = $longestLoosingSeries;

        return $this;
    }

    public function getPlacements(): ?array
    {
        return $this->placements;
    }

    public function setPlacements(array $placements): self
    {
        $this->placements = $placements;

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

    public function initFrom(SimulationCreateData $data)
    {

        $this->setCashRegister($data->getCashRegister());
        $this->setCommitment($data->getCommitment());
        $this->setCurrentCommitment($data->getCommitment());
        $this->setOddBorderHigh($data->getOddBorderHigh());
        $this->setOddBorderLow($data->getOddBorderLow());
        $this->setCommitmentChange($data->getCommitmentChange());
        $this->setIdent($data->getIdent());
        $this->setPlacements([]);
        $this->setLeagues($data->getLeagues());

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

    public function getCommitmentChange(): ?string
    {
        return $this->commitmentChange;
    }

    public function setCommitmentChange(string $commitmentChange): self
    {
        $this->commitmentChange = $commitmentChange;

        return $this;
    }

    public function getTotalPages(): ?int
    {
        return $this->totalPages;
    }

    public function setTotalPages(int $totalPages): self
    {
        $this->totalPages = $totalPages;

        return $this;
    }

    public function getCurrentPage(): ?int
    {
        return $this->currentPage;
    }

    public function setCurrentPage(int $currentPage): self
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    public function addPlacement(string $placement)
    {
        $this->placements[] = $placement;
    }

    public function getCommitmentChanger(): ?int
    {
        return $this->commitmentChanger;
    }

    public function setCommitmentChanger(int $commitmentChanger): self
    {
        $this->commitmentChanger = $commitmentChanger;

        return $this;
    }

    public function getLeagues(): ?array
    {
        return $this->leagues;
    }

    public function setLeagues(array $leagues): self
    {
        $this->leagues = $leagues;

        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(?int $parentId): self
    {
        $this->parentId = $parentId;

        return $this;
    }
}
