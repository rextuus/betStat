<?php


namespace App\Controller;

use App\Entity\Fixture;
use App\Service\Api\AutoApiCaller;
use App\Service\Setting\AutomaticUpdateSettingService;
use App\Service\Setting\FootballApiManagerService;
use App\Service\Import\UpdateService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/**
 * Class PaymentController
 * @Route("/automatic")
 */
class AutomaticUpdateController extends AbstractController
{

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var AutomaticUpdateSettingService
     */
    private $automaticUpdateSettingService;

    /**
     * @var FootballApiManagerService
     */
    private $footballApiManagerService;

    /**
     * @var AutoApiCaller
     */
    private $autoApiCaller;

    /**
     * @var UpdateService
     */
    private $updateService;

    /**
     * AutomaticUpdateController constructor.
     * @param RouterInterface $router
     * @param AutomaticUpdateSettingService $automaticUpdateSettingService
     * @param FootballApiManagerService $footballApiManagerService
     * @param AutoApiCaller $autoApiCaller
     * @param UpdateService $updateService
     */
    public function __construct(RouterInterface $router, AutomaticUpdateSettingService $automaticUpdateSettingService, FootballApiManagerService $footballApiManagerService, AutoApiCaller $autoApiCaller, UpdateService $updateService)
    {
        $this->router = $router;
        $this->automaticUpdateSettingService = $automaticUpdateSettingService;
        $this->footballApiManagerService = $footballApiManagerService;
        $this->autoApiCaller = $autoApiCaller;
        $this->updateService = $updateService;
    }

    /**
     * @Route("/init", name="init_applicaction")
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function initApplication(
    ): Response
    {
        $this->footballApiManagerService->initializeApiManager();
        $this->automaticUpdateSettingService->initSettings();
        return $this->render('<h1>Init succeeded</h1>');
    }

    /**
     * @Route("/reset/apicounter", name="reset_api_counter")
     * @return Response
     * @throws \Exception
     */
    public function resetDailyApiCalls(
    ): Response
    {
        $this->footballApiManagerService->resetApiCallCounter();
        return $this->redirectToRoute('dashboard_show', []);
    }

    /**
     * @Route("/process", name="process")
     * @return Response
     * @throws \Exception
     */
    public function processDaily(
    ): Response
    {
        $this->autoApiCaller->useFullApiCallLimit();
        return $this->render('<h1>Reset succeeded</h1>');
    }

    /**
     * @Route("/odds/{fixture_id}", name="fixture_odds", requirements={"fixture_id":"\d+"})
     * @ParamConverter("fixture", options={"id" = "fixture_id"})
     * @param Fixture $fixture
     * @return Response
     */
    public function getOddsForFixture(Fixture $fixture
    ): Response
    {
        $this->updateService->storeOddsForFixture($fixture->getApiId());
        return new RedirectResponse($this->router->generate('dashboard_fixtures'));
    }

    /**
     * @Route("/step/seeding", name="step_seeding")
     * @return Response
     * @throws \Exception
     */
    public function updateSeedings(
    ): Response
    {
        $this->autoApiCaller->updateSeedingsForAllOldOne();

        return new RedirectResponse($this->router->generate('dashboard_fixtures'));
    }

    /**
     * @Route("/step/check", name="step_check")
     * @return Response
     * @throws \Exception
     */
    public function updateChecks(
    ): Response
    {
        $this->autoApiCaller->checkIfLastRoundMatchIsReached();

        return new RedirectResponse($this->router->generate('dashboard_fixtures'));
    }

    /**
     * @Route("/step/bet", name="step_bet")
     * @return Response
     * @throws \Exception
     */
    public function updateBetDecoration(
    ): Response
    {
        $this->autoApiCaller->goOnWithBetDecorationTimestampVariant();

        return new RedirectResponse($this->router->generate('dashboard_fixtures'));
    }

    /**
     * @Route("/step/result", name="step_result")
     * @return Response
     * @throws \Exception
     */
    public function updateResultDecoration(
    ): Response
    {
        $this->autoApiCaller->updateResultsOfAlreadyFinishedFixtures();

        return new RedirectResponse($this->router->generate('dashboard_fixtures'));
    }

    /**
     * @Route("/step/old", name="step_old_results")
     * @return Response
     * @throws \Exception
     */
    public function updateOldRounds(
    ): Response
    {
        $this->autoApiCaller->increaseOldFixtureStock();

        return new RedirectResponse($this->router->generate('dashboard_fixtures'));
    }
}
