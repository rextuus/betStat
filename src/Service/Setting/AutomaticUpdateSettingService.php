<?php


namespace App\Service\Setting;


use App\Entity\AutomaticUpdateSetting;
use App\Repository\AutomaticUpdateSettingRepository;
use App\Service\Import\UpdateService;

class AutomaticUpdateSettingService
{
    /**
     * @var AutomaticUpdateSettingRepository
     */
    private $automaticUpdateSettingRepository;

    /**
     * @var UpdateService
     */
    private $updateService;

    /**
     * AutomaticUpdateSettingService constructor.
     * @param AutomaticUpdateSettingRepository $automaticUpdateSettingRepository
     * @param UpdateService $updateService
     */
    public function __construct(AutomaticUpdateSettingRepository $automaticUpdateSettingRepository, UpdateService $updateService)
    {
        $this->automaticUpdateSettingRepository = $automaticUpdateSettingRepository;
        $this->updateService = $updateService;
    }

    public function initSettings(){
        $automaticUpdateSetting = new AutomaticUpdateSetting();
        $currentRounds = $this->updateService->getCurrentRoundForAllLeagues();
        $automaticUpdateSetting->setCurrentRounds($currentRounds);

        $completedRounds = array();
        foreach (UpdateService::LEAGUES as $name => $apiKey){
            $completedRounds[$name] = 0;
        }
        $automaticUpdateSetting->setCompletedRounds($completedRounds);
        $automaticUpdateSetting->setLastOddDecoratedFixtureId(0);
        $this->automaticUpdateSettingRepository->persist($automaticUpdateSetting);
    }

    public function refreshCurrentRounds()
    {
        $automaticUpdateSetting = $this->automaticUpdateSettingRepository->find(1);
        $currentRounds = $this->updateService->getCurrentRoundForAllLeagues();
        if (!empty($currentRounds)){
            $automaticUpdateSetting->setCurrentRounds($currentRounds);
            $this->automaticUpdateSettingRepository->persist($automaticUpdateSetting);
        }
    }

    public function getSettings()
    {
        return $this->automaticUpdateSettingRepository->find(1);
    }

    public function setLastOddDecoratedFixtureId(int $lastDecoratedFixtureId)
    {
        $automaticUpdateSetting = $this->automaticUpdateSettingRepository->find(1);
        $automaticUpdateSetting->setLastOddDecoratedFixtureId($lastDecoratedFixtureId);
        $this->automaticUpdateSettingRepository->persist($automaticUpdateSetting);
    }

    public function refreshCurrentRound(string $leagueIdent): bool
    {
        $currentRound = $this->updateService->getCurrentRoundForLeague($leagueIdent);
        if (is_null($currentRound)){
            return false;
        }
        $automaticUpdateSetting = $this->automaticUpdateSettingRepository->find(1);
        $currentRounds = $automaticUpdateSetting->getCurrentRounds();
        $currentRounds[$leagueIdent] = $currentRound;
        $automaticUpdateSetting->setCurrentRounds($currentRounds);
        $this->automaticUpdateSettingRepository->persist($automaticUpdateSetting);
        return true;
    }

    public function setCompletedRoundByLeague(string $leagueIdent, int $completedRound)
    {
        $automaticUpdateSetting = $this->automaticUpdateSettingRepository->find(1);
        $completedRounds = $automaticUpdateSetting->getCompletedRounds();
        $completedRounds[$leagueIdent] = $completedRound;
        $automaticUpdateSetting->setCompletedRounds($completedRounds);
        $this->automaticUpdateSettingRepository->persist($automaticUpdateSetting);
    }
}