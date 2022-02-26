<?php


namespace App\Service\Simulator;


use App\Service\Simulator\Placement\PlacementService;
use App\Service\Simulator\SimulatorStrategy\SimulatorStrategyService;

class SimulationService
{
    /**
     * @var PlacementService
     */
    private $placementService;

    /**
     * @var SimulatorStrategyService
     */
    private $simulatorStrategyService;

    /**
     * @var SimulationService
     */
    private $simulationService;

    /**
     * SimulationService constructor.
     * @param PlacementService $placementService
     * @param SimulatorStrategyService $simulatorStrategyService
     * @param SimulationService $simulationService
     */
    public function __construct(PlacementService $placementService, SimulatorStrategyService $simulatorStrategyService, SimulationService $simulationService)
    {
        $this->placementService = $placementService;
        $this->simulatorStrategyService = $simulatorStrategyService;
        $this->simulationService = $simulationService;
    }
}
