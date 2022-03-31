<?php

namespace App\Controller;

use App\Entity\SimulationResult;
use App\Form\SimulationCreateData;
use App\Form\SimulationCreateForm;
use App\Service\Fixture\FixtureTransportFactory;
use App\Service\Simulation\Simulation;
use App\Service\Simulation\SimulationService;
use App\Service\SimulationResult\SimulationResultService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Fixture\FixtureService;
use App\Service\Import\UpdateService;
use App\Service\League\LeagueService;
use App\Service\Setting\FootballApiManagerService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class SimulationController
 * @Route("/simulation")
 */
class SimulationController extends AbstractController
{
    /**
     * @Route("/simulate", name="simulator_simulate")
     * @param Request $request
     * @return Response
     */
    public function startSimulation(Request $request, SimulationService $simulationService): Response
    {
        $transactionData = (new SimulationCreateData());
        $form = $this->createForm(SimulationCreateForm::class, $transactionData, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var SimulationCreateData $data */
            $data = $form->getData();
            $simulation = (new Simulation())->initFrom($data);

            $simulationService->initSimulation($data);

            return $this->redirect('simulator_list');
        }

        return $this->render('simulation/simulation.create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/list", name="simulator_list")
     * @param Request $request
     * @return Response
     */
    public function listSimulations(SimulationResultService $simulationResultService): Response
    {
        $simulationResults = $simulationResultService->findAllLimited();
        dump($simulationResults);
        return $this->render('simulation/simulation.list.html.twig', [
            'simulations' => $simulationResults,
        ]);
    }

    /**
     * @Route("/show/{simulationResult}", name="simulator_show")
     * @param SimulationResult $simulationResult
     * @return Response
     */
    public function showSimulations(SimulationResult $simulationResult, LeagueService $leagueService): Response
    {
        $leagues = array();
        foreach ($simulationResult->getLeagues() as $league){
            $leagues[] = $leagueService->findById($league)->getIdent();
        }

        $placementsInfo = array();
        foreach ($simulationResult->getPlacements() as $placement){
            if (strpos($placement, 'Won') !== false){
                $placementsInfo[] = true;
            }else{
                $placementsInfo[] = false;
            }
        }
        return $this->render('simulation/simulation.show.html.twig', [
            'simulation' => $simulationResult,
            'leagues' => $leagues,
            'placementsInfo' => $placementsInfo,
        ]);
    }
}
