<?php

namespace App\Entity;

use App\Repository\AutomaticUpdateSettingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AutomaticUpdateSettingRepository::class)
 */
class AutomaticUpdateSetting
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array")
     */
    private $currentRounds = [];

    /**
     * @ORM\Column(type="array")
     */
    private $completedRounds = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $lastOddDecoratedFixtureId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrentRounds(): ?array
    {
        return $this->currentRounds;
    }

    public function setCurrentRounds(array $currentRounds): self
    {
        $this->currentRounds = $currentRounds;

        return $this;
    }

    public function getCompletedRounds(): ?array
    {
        return $this->completedRounds;
    }

    public function setCompletedRounds(array $completedRounds): self
    {
        $this->completedRounds = $completedRounds;

        return $this;
    }

    public function getLastOddDecoratedFixtureId(): ?int
    {
        return $this->lastOddDecoratedFixtureId;
    }

    public function setLastOddDecoratedFixtureId(int $lastOddDecoratedFixtureId): self
    {
        $this->lastOddDecoratedFixtureId = $lastOddDecoratedFixtureId;

        return $this;
    }
}
