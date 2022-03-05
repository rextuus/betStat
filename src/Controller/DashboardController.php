<?php


namespace App\Controller;

use App\Service\Fixture\FixtureService;
use App\Service\Fixture\FixtureTransportFactory;
use App\Service\Import\UpdateService;
use App\Service\League\LeagueService;
use App\Service\Setting\FootballApiManagerService;
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
     * @param FootballApiManagerService $footballApiManagerService
     * @return Response
     */
    public function showUndecorated(FootballApiManagerService $footballApiManagerService): Response
    {
        return $this->render('dashboard/main.twig', [
            'managers' => $footballApiManagerService->getManagerDtos(),
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

    /**
     * @Route("/leagues", name="dashboard_leagues")
     * @param FixtureService $fixtureService
     * @return Response
     */
    public function showLeagues(FixtureService $fixtureService): Response
    {
        $leagues = array();
        foreach (UpdateService::LEAGUES as $ident => $leagueApiKey){
            $rounds = array();
            $leagues[$ident] = $rounds;
            for($round = 1; $round <= 50; $round++){
                $fixtures = $fixtureService->findByLeagueAndSeasonAndRound($leagueApiKey, 2021, $round);

                if (count($fixtures) == UpdateService::ROUNDS[$ident]){
                    $leagues[$ident][$round] = 2;
                }
                else if (count($fixtures) > 0){
                    $leagues[$ident][$round] = count($fixtures);
                }else{
                    $leagues[$ident][$round] = 0;
                }
            }
        }
        return $this->render('dashboard/leagues.twig', [
            'leagues' => $leagues,
        ]);
    }
}