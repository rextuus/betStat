<?php

namespace App\Handler;

class CleanBetRow
{
    /**
     * @var int
     */
    private $simulationResultId;

    /**
     * @var string
     */
    private $ident;

    /**
     * @param int $simulationResultId
     * @param string $ident
     */
    public function __construct(int $simulationResultId, string $ident)
    {
        $this->simulationResultId = $simulationResultId;
        $this->ident = $ident;
    }

    /**
     * @return int
     */
    public function getSimulationResultId(): int
    {
        return $this->simulationResultId;
    }

    /**
     * @param int $simulationResultId
     */
    public function setSimulationResultId(int $simulationResultId): void
    {
        $this->simulationResultId = $simulationResultId;
    }

    /**
     * @return string
     */
    public function getIdent(): string
    {
        return $this->ident;
    }

    /**
     * @param string $ident
     */
    public function setIdent(string $ident): void
    {
        $this->ident = $ident;
    }
}