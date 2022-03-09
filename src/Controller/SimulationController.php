<?php

namespace App\Controller;

use App\Service\Fixture\FixtureTransportFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PaymentController
 * @Route("/simulation")
 */
class SimulationController  extends AbstractController
{
    /**
     * @Route("/simulate", name="simulator_simulate", methods="GET, POST")
     * @param FixtureTransportFactory $fixtureTransportFactory
     * @return Response
     */
    public function showFixtures(FixtureTransportFactory $fixtureTransportFactory): Response
    {
        return $this->render('dashboard/fixtures.twig', [
            'fixtures' => $fixtureTransportFactory->createFixtureTransports([]),
        ]);
    }
}
