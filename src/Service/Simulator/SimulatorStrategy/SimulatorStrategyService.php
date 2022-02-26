<?php


namespace App\Service\Simulator\SimulatorStrategy;


use App\Entity\SimulatorStrategy;
use App\Repository\SimulatorStrategyRepository;

class SimulatorStrategyService
{
    /**
     * @var SimulatorStrategyRepository
     */
    private $simulatorStrategyRepository;

    /**
     * @var SimulatorStrategyFactory
     */
    private $simulatorStrategyFactory;

    /**
     * SimulatorStrategyService constructor.
     * @param SimulatorStrategyRepository $simulatorStrategyRepository
     * @param SimulatorStrategyFactory $simulatorStrategyFactory
     */
    public function __construct(SimulatorStrategyRepository $simulatorStrategyRepository, SimulatorStrategyFactory $simulatorStrategyFactory)
    {
        $this->simulatorStrategyRepository = $simulatorStrategyRepository;
        $this->simulatorStrategyFactory = $simulatorStrategyFactory;
    }

    /**
     * @param SimulatorStrategyData $data
     * @return SimulatorStrategy
     */
    public function createByData(SimulatorStrategyData $data)
    {
        $simulatorStrategy = $this->simulatorStrategyFactory->createByData($data);
        $this->simulatorStrategyRepository->persist($simulatorStrategy);
        return $simulatorStrategy;
    }

    /**
     * @param SimulatorStrategy $SimulatorStrategy
     * @param SimulatorStrategyData $updateData
     */
    public function update(SimulatorStrategy $SimulatorStrategy, SimulatorStrategyData $updateData)
    {
        $this->simulatorStrategyFactory->mapData($updateData, $SimulatorStrategy);
        $this->simulatorStrategyRepository->persist($SimulatorStrategy);
    }
}