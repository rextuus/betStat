<?php


namespace App\Controller;

use App\Service\Api\AutomaticUpdateSettingService;
use App\Service\Api\FootballApiManagerService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class PaymentController
 * @Route("/automatic")
 */
class AutomaticUpdateController extends AbstractController
{
    /**
     * @var AutomaticUpdateSettingService
     */
    private $automaticUpdateSettingService;

    /**
     * @var FootballApiManagerService
     */
    private $footballApiManagerService;

    /**
     * AutomaticUpdateController constructor.
     * @param AutomaticUpdateSettingService $automaticUpdateSettingService
     * @param FootballApiManagerService $footballApiManagerService
     */
    public function __construct(AutomaticUpdateSettingService $automaticUpdateSettingService, FootballApiManagerService $footballApiManagerService)
    {
        $this->automaticUpdateSettingService = $automaticUpdateSettingService;
        $this->footballApiManagerService = $footballApiManagerService;
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
        $this->automaticUpdateSettingService->initSettings();
        $this->footballApiManagerService->initializeApiManager();
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
        return $this->render('<h1>Reset succeeded</h1>');
    }
}
