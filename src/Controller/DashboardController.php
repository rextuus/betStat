<?php


namespace App\Controller;

use App\Service\Fixture\FixtureService;
use App\Service\Fixture\FixtureTransportFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class PaymentController
 * @Route("/dashboard")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/show", name="dashboard_show")
     * @param FixtureService $fixtureService
     * @return Response
     */
    public function showUndecorated(FixtureService $fixtureService): Response
    {
        return $this->render('statistic/dashboard.twig', [
            'candidates' => $fixtureService->getUndecoratedFixtures(),
        ]);
    }

    /**
         * @Route("/fixtures", name="dashboard_fixtures")
     * @param FixtureTransportFactory $fixtureTransportFactory
     * @return Response
     */
    public function showFixtures(FixtureTransportFactory $fixtureTransportFactory): Response
    {
        return $this->render('dashboard/fixtures.twig', [
            'fixtures' => $fixtureTransportFactory->createFixtureTransports(),
        ]);
    }
}