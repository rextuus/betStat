<?php


namespace App\Service\Simulator\Placement;

use App\Entity\Placement;

class PlacementFactory
{
    /**
     * @param PlacementData $data
     * @return Placement
     */
    public function createByData(PlacementData $data)
    {
        $placement = $this->createNewInstance();
        $this->mapData($data, $placement);
        return $placement;
    }

    /**
     * @param PlacementData $data
     * @param Placement $placement
     * @return Placement
     */
    public function mapData(PlacementData $data, Placement $placement)
    {
        $placement->setFixture($data->getFixture());
        $placement->setCommitment($data->getCommitment());
        $placement->setProfit($data->getProfit());
        $placement->setSimulator($data->getSimulator());
        $placement->setWasSuccessfully($data->isWasSuccessfully());
        return $placement;
    }

    /**
     * @return Placement
     */
    private function createNewInstance()
    {
        return new Placement();
    }
}