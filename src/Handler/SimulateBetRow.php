<?php

namespace App\Handler;

use App\Entity\SimulationResult;

class SimulateBetRow
{
    /**
     * @var int
     */
    private $simulationResultId;

    /**
     * @param int $simulationResultId
     */
    public function __construct(int $simulationResultId)
    {
        $this->simulationResultId = $simulationResultId;
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
}
