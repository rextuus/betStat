<?php


namespace App\Controller;

use App\Entity\Fixture;
use App\Service\Api\AutoApiCaller;
use App\Service\Api\AutomaticUpdateSettingService;
use App\Service\Api\FootballApiManagerService;
use App\Service\Fixture\FixtureService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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
    public function initApplication(FixtureService $fixtureService): Response
    {


        return $this->render('statistic/dashboard.twig', [
            'candidates' => $fixtureService->getUndecoratedFixtures(),
        ]);
    }

}