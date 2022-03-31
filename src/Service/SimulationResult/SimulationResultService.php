<?php

namespace App\Service\SimulationResult;

use App\Entity\SimulationResult;
use App\Repository\SimulationResultRepository;

class SimulationResultService
{
    /**
     * @var SimulationResultRepository
     */
    private $simulationResultRepository;

    /**
     * @var SimulationResultFactory
     */
    private $simulationResultFactory;

    /**
     * @param SimulationResultRepository $simulationResultRepository
     * @param SimulationResultFactory $simulationResultFactory
     */
    public function __construct(SimulationResultRepository $simulationResultRepository, SimulationResultFactory $simulationResultFactory)
    {
        $this->simulationResultRepository = $simulationResultRepository;
        $this->simulationResultFactory = $simulationResultFactory;
    }

    /**
     * @param SimulationResultData $data
     * @return SimulationResult
     */
    public function createByData(SimulationResultData $data): SimulationResult
    {
        $simulationResult = $this->simulationResultFactory->createByData($data);
        $this->simulationResultRepository->persist($simulationResult);
        return $simulationResult;
    }

    /**
     * @param SimulationResult $simulationResult
     * @param SimulationResultData $updateData
     */
    public function update(SimulationResult $simulationResult, SimulationResultData $updateData = null)
    {
        if (!is_null($updateData)){
            $this->simulationResultFactory->mapData($updateData, $simulationResult);
        }
        $this->simulationResultRepository->persist($simulationResult);
    }

    /**
     * @param SimulationResult $simulationResult
     * @return SimulationResult
     */
    public function persist(SimulationResult $simulationResult): SimulationResult
    {
        $this->simulationResultRepository->persist($simulationResult);
        return $simulationResult;
    }

    public function findById(int $id): ?SimulationResult
    {
        return$this->simulationResultRepository->find($id);
    }

    public function findLatestVersionByIdent(string $getIdent): int
    {
        return $this->simulationResultRepository->findLatestVersionByIdent($getIdent);
    }

    public function findAllByIdent($ident)
    {
        return $this->simulationResultRepository->find(['ident' => $ident]);
    }

    public function findAllLimited(): array
    {
        return $this->simulationResultRepository->findAllLimited();
    }
}