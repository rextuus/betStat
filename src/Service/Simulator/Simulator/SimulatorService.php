<?php


namespace App\Service\Simulator\Simulator;


use App\Entity\Simulator;
use App\Repository\SimulatorRepository;

class SimulatorService
{
    /**
     * @var SimulatorRepository
     */
    private $simulatorRepository;

    /**
     * @var SimulatorFactory
     */
    private $simulatorFactory;

    /**
     * SimulatorService constructor.
     * @param SimulatorRepository $simulatorRepository
     * @param SimulatorFactory $simulatorFactory
     */
    public function __construct(SimulatorRepository $simulatorRepository, SimulatorFactory $simulatorFactory)
    {
        $this->simulatorRepository = $simulatorRepository;
        $this->simulatorFactory = $simulatorFactory;
    }

    /**
     * @param SimulatorData $data
     * @return Simulator
     */
    public function createByData(SimulatorData $data)
    {
        $simulator = $this->simulatorFactory->createByData($data);
        $this->simulatorRepository->persist($simulator);
        return $simulator;
    }

    /**
     * @param Simulator $Simulator
     * @param SimulatorData $updateData
     */
    public function update(Simulator $Simulator, SimulatorData $updateData)
    {
        $this->simulatorFactory->mapData($updateData, $Simulator);
        $this->simulatorRepository->persist($Simulator);
    }
}