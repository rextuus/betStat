<?php

namespace App\Handler;

use App\Repository\SimulationResultRepository;
use App\Service\SimulationResult\SimulationResultService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CleanBetRowHandler implements MessageHandlerInterface
{
    /**
     * @var SimulationResultRepository
     */
    private $simulationResultRepository;

    /**
     * @var SimulationResultService
     */
    private $simulationResultService;

    /**
     * @param SimulationResultRepository $simulationResultRepository
     * @param SimulationResultService $simulationResultService
     */
    public function __construct(SimulationResultRepository $simulationResultRepository, SimulationResultService $simulationResultService)
    {
        $this->simulationResultRepository = $simulationResultRepository;
        $this->simulationResultService = $simulationResultService;
    }

    public function __invoke(CleanBetRow $simulateBetRow)
    {
        $results = $this->simulationResultRepository->findBy(['ident' => $simulateBetRow->getIdent()]);
        foreach ($results as $result){
            if ($result->getId() !== $simulateBetRow->getSimulationResultId()){
                $this->simulationResultRepository->remove($result);
            }
        }
    }
}