<?php

namespace App\Entity;

use App\Repository\UrlResponseBackupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UrlResponseBackupRepository::class)
 */
class UrlResponseBackup
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
    private $url;

    /**
     * @ORM\Column(type="datetime")
     */
    private $collectionDate;

    /**
     * @ORM\Column(type="string", length=16777215)
     */
    private $rawContent;

    /**
     * @ORM\ManyToOne(targetEntity=League::class, inversedBy="urlResponseBackups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $league;

    /**
     * @ORM\Column(type="integer")
     */
    private $matchDay;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getCollectionDate(): ?\DateTimeInterface
    {
        return $this->collectionDate;
    }

    public function setCollectionDate(\DateTimeInterface $collectionDate): self
    {
        $this->collectionDate = $collectionDate;

        return $this;
    }

    public function getRawContent(): ?string
    {
        return $this->rawContent;
    }

    public function setRawContent(string $rawContent): self
    {
        $this->rawContent = $rawContent;

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
}
