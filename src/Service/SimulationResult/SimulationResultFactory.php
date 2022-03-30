<?php

namespace App\Service\SimulationResult;

use App\Entity\SimulationResult;

class SimulationResultFactory
{
    /**
     * @param SimulationResultData $data
     * @return SimulationResult
     */
    public function createByData(SimulationResultData $data)
    {
        $simulationResult = $this->createNewInstance();
        $this->mapData($data, $simulationResult);
        return $simulationResult;
    }

    /**
     * @param SimulationResultData $data
     * @param SimulationResult $simulationResult
     * @return SimulationResult
     */
    public function mapData(SimulationResultData $data, SimulationResult $simulationResult)
    {
        $simulationResult->setIdent($data->getIdent());
        $simulationResult->setState($data->getState());
        $simulationResult->setLongestLoosingSeries($data->getLongestLoosingSeries());
        $simulationResult->setOddAverage($data->getOddAverage());
        $simulationResult->setLoosePlacements($data->getLoosePlacements());
        $simulationResult->setWonPlacements($data->getWonPlacements());
        $simulationResult->setMadePlacements($data->getMadePlacements());
        $simulationResult->setOddBorderLow($data->getOddBorderLow());
        $simulationResult->setOddBorderHigh($data->getOddBorderHigh());
        $simulationResult->setCashRegister($data->getCashRegister());
        $simulationResult->setCommitment($data->getCommitment());
        $simulationResult->setPlacements($data->getPlacements());
        foreach ($data->getLeagues() as $league){
            $simulationResult->addLeague($league);
        }
        return $simulationResult;
    }

    /**
     * @return SimulationResult
     */
    private function createNewInstance()
    {
        return new SimulationResult();
    }
}