<?php


namespace App\Service\Simulator\Placement;


use App\Entity\Placement;
use App\Repository\PlacementRepository;

class PlacementService
{
    /**
     * @var PlacementRepository
     */
    private $placementRepository;

    /**
     * @var PlacementFactory
     */
    private $placementFactory;

    /**
     * PlacementService constructor.
     * @param PlacementRepository $placementRepository
     * @param PlacementFactory $placementFactory
     */
    public function __construct(PlacementRepository $placementRepository, PlacementFactory $placementFactory)
    {
        $this->placementRepository = $placementRepository;
        $this->placementFactory = $placementFactory;
    }

    /**
     * @param PlacementData $data
     * @return Placement
     */
    public function createByData(PlacementData $data)
    {
        $placement = $this->placementFactory->createByData($data);
        $this->placementRepository->persist($placement);
        return $placement;
    }

    /**
     * @param Placement $placement
     * @param PlacementData $updateData
     */
    public function update(Placement $placement, PlacementData $updateData)
    {
        $this->placementFactory->mapData($updateData, $placement);
        $this->placementRepository->persist($placement);
    }
}