<?php


namespace App\Service\Simulator\SimulatorStrategy;


use App\Entity\SimulatorStrategy;

class SimulatorStrategyFactory
{
    /**
     * @param SimulatorStrategyData $data
     * @return SimulatorStrategy
     */
    public function createByData(SimulatorStrategyData $data)
    {
        $simulatorStrategy = $this->createNewInstance();
        $this->mapData($data, $simulatorStrategy);
        return $simulatorStrategy;
    }

    /**
     * @param SimulatorStrategyData $data
     * @param SimulatorStrategy $simulatorStrategy
     * @return SimulatorStrategy
     */
    public function mapData(SimulatorStrategyData $data, SimulatorStrategy $simulatorStrategy)
    {
        $simulatorStrategy->setCommitmentChange($data->getCommitmentChange());
        $simulatorStrategy->setResetAfterLoses($data->getResetAfterLoses());
        $simulatorStrategy->setStandardCommitment($data->getStandardCommitment());
        return $simulatorStrategy;
    }

    /**
     * @return SimulatorStrategy
     */
    private function createNewInstance()
    {
        return new SimulatorStrategy();
    }
}