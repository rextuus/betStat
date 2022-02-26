<?php


namespace App\Service\Simulator;


use App\Entity\Simulator;
use App\Service\Simulator\Placement\PlacementService;
use App\Service\Simulator\Simulator\SimulatorService;
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
     * @var SimulatorService
     */
    private $simulatorService;

    /**
     * SimulationService constructor.
     * @param PlacementService $placementService
     * @param SimulatorStrategyService $simulatorStrategyService
     * @param SimulationService $simulatorService
     */
    public function __construct(PlacementService $placementService, SimulatorStrategyService $simulatorStrategyService, SimulationService $simulatorService)
    {
        $this->placementService = $placementService;
        $this->simulatorStrategyService = $simulatorStrategyService;
        $this->simulatorService = $simulatorService;
    }
}
