<?php

namespace App\Controller;

use App\Form\SimulationCreateData;
use App\Form\SimulationCreateForm;
use App\Service\Fixture\FixtureTransportFactory;
use App\Service\Simulation\Simulation;
use App\Service\Simulation\SimulationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Fixture\FixtureService;
use App\Service\Import\UpdateService;
use App\Service\League\LeagueService;
use App\Service\Setting\FootballApiManagerService;

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

            return $this->render('simulation/simulation.show.html.twig', [
                'simulation' => $simulation,
            ]);
        }

        return $this->render('simulation/simulation.create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
