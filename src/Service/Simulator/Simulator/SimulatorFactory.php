<?php


namespace App\Service\Simulator\Simulator;


use App\Entity\Simulator;

class SimulatorFactory
{
    /**
     * @param SimulatorData $data
     * @return Simulator
     */
    public function createByData(SimulatorData $data)
    {
        $simulator = $this->createNewInstance();
        $this->mapData($data, $simulator);
        return $simulator;
    }

    /**
     * @param SimulatorData $data
     * @param Simulator $simulator
     * @return Simulator
     */
    public function mapData(SimulatorData $data, Simulator $simulator)
    {
        $simulator->setCommitment($data->getCommitment());
        $simulator->setCurrentCommitment($data->getCurrentCommitment());
        $simulator->setIsActive($data->isActive());
        $simulator->setCashRegister($data->getCashRegister());
        $simulator->setLoosePlacements($data->getLoosePlacements());
        $simulator->setWonPlacements($data->getWonPlacements());
        $simulator->setMadePlacements($data->getMadePlacements());
        foreach ($data->getPlacements() as $placement){
            $simulator->addPlacement($placement);
        }
        foreach ($data->getStrategies() as $strategy){
            $simulator->addStrategy($strategy);
        }
        return $simulator;
    }

    /**
     * @return Simulator
     */
    private function createNewInstance()
    {
        return new Simulator();
    }
}