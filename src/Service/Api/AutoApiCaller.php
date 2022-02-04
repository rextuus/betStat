<?php

namespace App\Service\Api;

use App\Entity\Club;
use App\Entity\Fixture;
use App\Service\Club\ClubService;
use App\Service\Fixture\FixtureData;
use App\Service\Fixture\FixtureService;
use App\Service\Import\UpdateService;
use App\Service\League\LeagueService;
use DateTime;
use function PHPUnit\Framework\isEmpty;

class AutoApiCaller
{
    private const DEFAULT_LIMIT = 50;

    /**
     * @var UpdateService
     */
    private $updateService;

    /**
     * @var AutomaticUpdateSettingService
     */
    private $automaticUpdateSettingService;

    /**
     * @var FootballApiManagerService
     */
    private $footballApiManagerService;

    /**
     * @var LeagueService
     */
    private $leagueService;

    /**
     * @var FixtureService
     */
    private $fixtureService;

    /**
     * @var ClubService
     */
    private $clubService;

    /**
     * @var int
     */
    private $fixtureDecorateLimit;

    /**
     * AutoApiCaller constructor.
     * @param UpdateService $updateService
     * @param AutomaticUpdateSettingService $automaticUpdateSettingService
     * @param FootballApiManagerService $footballApiManagerService
     * @param LeagueService $leagueService
     * @param FixtureService $fixtureService
     * @param ClubService $clubService
     * @param int $fixtureDecorateLimit
     */
    public function __construct(UpdateService $updateService, AutomaticUpdateSettingService $automaticUpdateSettingService, FootballApiManagerService $footballApiManagerService, LeagueService $leagueService, FixtureService $fixtureService, ClubService $clubService, int $fixtureDecorateLimit)
    {
        $this->updateService = $updateService;
        $this->automaticUpdateSettingService = $automaticUpdateSettingService;
        $this->footballApiManagerService = $footballApiManagerService;
        $this->leagueService = $leagueService;
        $this->fixtureService = $fixtureService;
        $this->clubService = $clubService;
        $this->fixtureDecorateLimit = $fixtureDecorateLimit;

        if ($this->fixtureDecorateLimit < self::DEFAULT_LIMIT){
            $this->fixtureDecorateLimit = self::DEFAULT_LIMIT;
        }
    }


    public function useFullApiCallLimit(){
        // TODO this method should handle the complete update stuff each day
        // 1. check if a round is over and all fixtures of current rounds are stored
        $lastRoundCompleted = $this->checkIfLastRoundMatchIsReached();
        if ($lastRoundCompleted){
            dump("Current round fixtures for all leagues are stored");
        }else{
            $currentMadeApiCalls = $this->footballApiManagerService->getApiCallLimit();
            dump("There are missing fixtures for current rounds");
            dump(sprintf("Made api calls: %d", $currentMadeApiCalls));
        }
        // 2. identify candidates
        $this->identifyCandidates();
        // 3. decorate fixtures
        $this->goOnWithBetDecoration();
        // 5. get fixtures for older rounds
        $this->increaseOldFixtureStock();
        // 4. update last played round (results)
        $this->updateResultsOfAlreadyFinishedFixtures();
    }

    public function increaseOldFixtureStock(): bool
    {

        $leagueRoundsToUpdate = array();
        $fixtures = $this->fixtureService->getUndecoratedFixtures();
        foreach ($fixtures as $fixture){
            /** @var Fixture $fixture */
            $currentTimestamp = (new DateTime())->getTimestamp();
            if ($currentTimestamp > $fixture->getTimeStamp()+150){
                $leagueRoundsToUpdate[$fixture->getLeague()->getApiId()."_".$fixture->getMatchDay()] = 1;
            }
        }

        // update played fixtures
        foreach (array_keys($leagueRoundsToUpdate) as $roundToUpdate) {
            $information = explode('_', $roundToUpdate);
            $updatedFixtures = $this->updateService->updateFixtureForLeagueAndRound($information[0], 2021, $information[1]);
            if (!$updatedFixtures){
                return false;
            }
        }

        return true;
    }

    public function checkIfLastRoundMatchIsReached(): bool
    {
        $settings = $this->automaticUpdateSettingService->getSettings();
        // go over current saved rounds and get the fixtures for them
        foreach ($settings->getCurrentRounds() as $leagueIdent => $round){
            /** @var Fixture[] $fixtures */
            $fixtures = $this->fixtureService->findByLeagueAndSeasonAndRound(UpdateService::LEAGUES[$leagueIdent], 2021, $round);
            if (count($fixtures) == 0){
                $storedNewFixtures = $this->updateService->storeFixtureForLeagueAndRound(UpdateService::LEAGUES[$leagueIdent], 2021, $round);
                if (!$storedNewFixtures){
                    return false;
                }
                $fixtures = $this->fixtureService->findByLeagueAndSeasonAndRound(UpdateService::LEAGUES[$leagueIdent], 2021, $round);
            }
            // get last startTime of this round
            $lastStartTime = $fixtures[0]->getTimeStamp();
            foreach ($fixtures as $fixture){
                if($fixture->getTimeStamp() > $lastStartTime){
                    $lastStartTime = $fixture->getTimeStamp();
                }
            }
            // check if round is completely finished
            $currentTimestamp = (new DateTime())->getTimestamp();
            if ($currentTimestamp > $lastStartTime){
                // store new round number in DB
                $roundRefreshed = $this->automaticUpdateSettingService->refreshCurrentRound($leagueIdent);
                if (!$roundRefreshed){
                    return false;
                }
            }
        }
        return true;
    }

    public function identifyCandidates()
    {
        $settings = $this->automaticUpdateSettingService->getSettings();
        foreach ($settings->getCompletedRounds() as $leagueIdent => $round){
            $league = $this->leagueService->findByApiKey(UpdateService::LEAGUES[$leagueIdent]);
            $clubs = $this->clubService->findByLeagueAndSeason($league, 2021);
            foreach ($clubs as $club){
                /** @var Club $club */
                if($this->checkIfFormFitsCondition($club->getCurrentForm()) && $round == $club->getFormRound() ){
                    $fixtureSoMarkAsCandidate = $this->fixtureService->findByClubAndSeasonAndRound($club, 2021, $round);
                    $fixtureUpdateData = (new FixtureData())->initFrom($fixtureSoMarkAsCandidate);
                    $fixtureUpdateData->setIsDoubleChanceCandidate(true);
                    $this->fixtureService->updateFixture($fixtureSoMarkAsCandidate, $fixtureUpdateData);
                }
            }
        }
        return 1;
    }

    public function goOnWithBetDecoration(): int
    {
        $settings = $this->automaticUpdateSettingService->getSettings();
        // use last fixture that was decorated to go on with
        $lastFixtureId = $settings->getLastOddDecoratedFixtureId();
        $fixtureToDecorateId = $lastFixtureId+1;
        $fixtureToDecorate = $this->fixtureService->findByDbId($fixtureToDecorateId);
        $nrOfStoredOdds = 0;
        while (!is_null($fixtureToDecorate) && $nrOfStoredOdds <= $this->fixtureDecorateLimit){
            // getOddsForFixture will return false if no api calls were left
            $oddsStored = $this->updateService->storeOddsForFixture($fixtureToDecorate->getApiId());
            if($oddsStored){
                // set Fixture flag to decorated
                $fixtureUpdateData = (new FixtureData())->initFrom($fixtureToDecorate);
                $fixtureUpdateData->setIsBetDecorated(true);
                $this->fixtureService->updateFixture($fixtureToDecorate, $fixtureUpdateData);
                // update settings
                $this->automaticUpdateSettingService->setLastOddDecoratedFixtureId($fixtureToDecorate->getId());
                // go on with next one
                $fixtureToDecorateId = $fixtureToDecorateId + 1;
                $fixtureToDecorate = $this->fixtureService->findByApiKey($fixtureToDecorateId);
                $nrOfStoredOdds++;
            }else{
                $fixtureToDecorate = null;
            }
        }
        return $nrOfStoredOdds;
    }

    public function updateResultsOfAlreadyFinishedFixtures()
    {
        $settings = $this->automaticUpdateSettingService->getSettings();
        foreach ($settings->getCompletedRounds() as $leagueIdent => $round){
            $league = $this->leagueService->findByIdent($leagueIdent);
            $updatedFixtures = $this->updateService->updateFixtureForLeagueAndRound($league->getApiId(), 2021, $round + 1);
            if ($updatedFixtures){
                $this->automaticUpdateSettingService->setCompletedRoundByLeague($leagueIdent, $round + 1);
            }
        }
    }







    public function getCurrentRoundMatchesForAllLeagues(){
        $settings = $this->automaticUpdateSettingService->getSettings();
        foreach ($settings->getCurrentRounds() as $leagueIdent => $round){
            $league = $this->leagueService->findByIdent($leagueIdent);
            $this->updateService->storeFixtureForLeagueAndRound($league->getApiId(), 2021, $round);
        }
    }

    public function getLastRoundMatchesForAllLeagues(){
        $settings = $this->automaticUpdateSettingService->getSettings();
        foreach ($settings->getCurrentRounds() as $leagueIdent => $round){
            $league = $this->leagueService->findByIdent($leagueIdent);
            $this->updateService->storeFixtureForLeagueAndRound($league->getApiId(), 2021, $round-1);
        }
    }

    public function getCurrentMatchDays(){
        $this->automaticUpdateSettingService->refreshCurrentRounds();
    }

    private function checkIfFormFitsCondition(string $currentForm)
    {
        // form is read from right to left: LWLLL == 5. L, 4. L, 3. L, 2. W, 1. L
        $form = str_split($currentForm);
        if (count($form) > 1){
            $lastMatch = $form[count($form)-1];
            $matchBeforeLastMatch = $form[count($form)-2];
            if (($matchBeforeLastMatch == 'D' || $matchBeforeLastMatch == 'L') && $lastMatch == 'W'){
                return true;
            }
        }
        return false;
    }
}
