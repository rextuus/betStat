<?php


namespace App\Controller;

use App\Form\FilterData;
use App\Form\FilterForm;
use App\Service\Fixture\FixtureService;
use App\Service\Fixture\FixtureTransportFactory;
use App\Service\Import\UpdateService;
use App\Service\League\LeagueService;
use App\Service\Setting\FootballApiManagerService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function showFixtures(Request $request, FixtureTransportFactory $fixtureTransportFactory): Response
    {
//        $pagination = $paginator->paginate(
//            $queryBuilder, /* query NOT result */
//            $request->query->getInt('page', 1)/*page number*/,
//            10/*limit per page*/
//        );
        $transactionData = (new FilterData());
        $form = $this->createForm(FilterForm::class, $transactionData);
        $form->handleRequest($request);

        $filter = ['maxResults' => 100];
        if ($form->isSubmitted() && $form->isValid()) {
            $fromDate = new DateTime('2018-06-01');

            /** @var FilterData $data */
            $data = $form->getData();
            $filter =
                [
                    'from' => $data->getFrom() ? $data->getFrom()->getTimestamp() : $fromDate->getTimestamp(),
                    'leagues' => $data->getLeagues() ?: null,
                    'oddDecorated' => $data->getOddDecorated() ?: false,
                    'played' => $data->getPlayed() ?: false,
                    'useDraws' => $data->getUseDraws() ?: false,
                    'maxResults' => $data->getMaxResults() ?: 100,
                ];
        }
        dump($filter);
        $fixtureTransports = $fixtureTransportFactory->createFixtureTransports(
            $filter
        );

        $candidates = 0;
        $wins = 0;
        $loses = 0;
        $lastResult = 0;
        $currentLoosingRow = 0;
        $longestLoosingRow = 0;
        foreach ($fixtureTransports as $fixtureTransport){
            if ($fixtureTransport->getWishedResult() != 0){
                $candidates++;
                //green lines
                if ($fixtureTransport->getWishedResult() === 1){
                    $wins++;
                    $lastResult = 1;
                }
                //red lines
                if ($fixtureTransport->getWishedResult() === -1){
                    $loses++;
                    if ($lastResult == -1){
                        $currentLoosingRow++;
                    }else{
                        $currentLoosingRow = 1;
                    }
                    if ($currentLoosingRow > $longestLoosingRow){
                        $longestLoosingRow = $currentLoosingRow;
                    }
                    $lastResult = -1;
                }
            }
        }
        return $this->render('dashboard/fixtures.twig', [
            'form' => $form->createView(),
            'fixtures' => $fixtureTransports,
            'total' => $candidates,
            'wins' => $wins,
            'loses' => $loses,
            'loosingRow' => $longestLoosingRow,
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