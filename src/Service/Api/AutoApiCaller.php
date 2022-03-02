<?php

namespace App\Service\Api;

use App\Entity\Club;
use App\Entity\Fixture;
use App\Entity\Seeding;
use App\Service\Club\ClubService;
use App\Service\Fixture\FixtureData;
use App\Service\Fixture\FixtureService;
use App\Service\Import\UpdateService;
use App\Service\League\LeagueService;
use DateTime;
use Monolog\Handler\IFTTTHandler;
use Psr\Log\LoggerInterface;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isInstanceOf;

class AutoApiCaller
{
    private const DEFAULT_LIMIT = 50;
    private const DEFAULT_FIXTURE_SEEDING_LIMIT = 0;

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
     * @var int
     */
    private $seedingDecorateLimit;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AutoApiCaller constructor.
     * @param UpdateService $updateService
     * @param AutomaticUpdateSettingService $automaticUpdateSettingService
     * @param FootballApiManagerService $footballApiManagerService
     * @param LeagueService $leagueService
     * @param FixtureService $fixtureService
     * @param ClubService $clubService
     * @param int $fixtureDecorateLimit
     * @param int $seedingDecorateLimit
     * @param LoggerInterface $autoUpdateLogger
     */
    public function __construct(UpdateService $updateService, AutomaticUpdateSettingService $automaticUpdateSettingService, FootballApiManagerService $footballApiManagerService, LeagueService $leagueService, FixtureService $fixtureService, ClubService $clubService, int $fixtureDecorateLimit, int $seedingDecorateLimit, LoggerInterface $autoUpdateLogger)
    {
        $this->updateService = $updateService;
        $this->automaticUpdateSettingService = $automaticUpdateSettingService;
        $this->footballApiManagerService = $footballApiManagerService;
        $this->leagueService = $leagueService;
        $this->fixtureService = $fixtureService;
        $this->clubService = $clubService;
        $this->fixtureDecorateLimit = $fixtureDecorateLimit;
        $this->seedingDecorateLimit = $seedingDecorateLimit;
        $this->logger = $autoUpdateLogger;

        if ($this->fixtureDecorateLimit < self::DEFAULT_LIMIT) {
            $this->fixtureDecorateLimit = self::DEFAULT_LIMIT;
        }
    }


    public function useFullApiCallLimit()
    {
        $this->logger->info("##################################");
        $this->logger->info("Start with daily auto api calling");
        $this->logger->info("##################################");

        // TODO this method should handle the complete update stuff each day
        // 1. check if a round is over and all fixtures of current rounds are stored
        $this->logger->info("1. Start with checking round is finished");
        $lastRoundCompleted = $this->checkIfLastRoundMatchIsReached();
        if ($lastRoundCompleted) {
            $this->logger->info("Current round fixtures for all leagues are stored");
        } else {
            $currentMadeApiCalls = $this->footballApiManagerService->getApiCallLimit();
            $this->logger->info("There are missing fixtures for current rounds");
            $this->logger->info(sprintf("Currently made api calls: %d", $currentMadeApiCalls));
            $this->logger->info("_____________________________________________________");
        }
        // 2. update leagues
        // TODO Think we this for seeding
        $this->logger->info("2. Start with updating leagues");
        $this->updateService->updateLeagues();
        // 3. update Seedings
        $this->logger->info("3. Start with updating seeding");
        $this->updateSeedingsForAllOldOne();
//        $this->updateSeedings();
        // 4. identify candidates
        $this->logger->info("4. Start with candidate identification");
        $this->identifyCandidates();
        // 5. decorate fixtures
//        $this->goOnWithBetDecoration();
        $this->logger->info("5. Go on with bet decoration");
        $this->goOnWithBetDecorationTimestampVariant();
        // 6. update last played round (results)
        $this->logger->info("6. Get results for finished fixtures");
        $this->updateResultsOfAlreadyFinishedFixtures();
        // 7. get fixtures for older rounds
        $this->logger->info("7. Get fixtures for old rounds");
        $this->increaseOldFixtureStock();
        // 8. check fixtures which arent decorated
    }

    public function increaseOldFixtureStock(): bool
    {

        $leagueRoundsToUpdate = array();
        $fixtures = $this->fixtureService->getUnevaluatedFixtures();
        foreach ($fixtures as $fixture) {
            /** @var Fixture $fixture */
            $currentTimestamp = (new DateTime())->getTimestamp();
            if ($currentTimestamp > $fixture->getTimeStamp() + 150) {
                $leagueRoundsToUpdate[$fixture->getLeague()->getApiId() . "_" . $fixture->getMatchDay()] = 1;
            }
        }

        // update played fixtures
        foreach (array_keys($leagueRoundsToUpdate) as $roundToUpdate) {
            $information = explode('_', $roundToUpdate);
            $updatedFixtures = $this->updateService->updateFixtureForLeagueAndRound($information[0], 2021, $information[1]);
            if (!$updatedFixtures) {
                return false;
            }
        }

        return true;
    }

    public function checkIfLastRoundMatchIsReached(): bool
    {
        $settings = $this->automaticUpdateSettingService->getSettings();
        // go over current saved rounds and get the fixtures for them
        foreach ($settings->getCurrentRounds() as $leagueIdent => $round) {
            $this->logger->info(sprintf("Check if round %d of %s is completely played", $round, $leagueIdent));
            /** @var Fixture[] $fixtures */
            $fixtures = $this->fixtureService->findByLeagueAndSeasonAndRound(UpdateService::LEAGUES[$leagueIdent], 2021, $round);

            // if for given round league combi no fixtures are present => get them
            if (count($fixtures) == 0) {
                $storedNewFixtures = $this->updateService->storeFixtureForLeagueAndRound(UpdateService::LEAGUES[$leagueIdent], 2021, $round);
                if (!$storedNewFixtures) {
                    return false;
                }
                $fixtures = $this->fixtureService->findByLeagueAndSeasonAndRound(UpdateService::LEAGUES[$leagueIdent], 2021, $round);
                $this->logger->info(sprintf("Get new %d fixtures for round %d of %s", count($fixtures), $round, $leagueIdent));
            }

            // get last startTime of this round
            $lastStartTime = $fixtures[0]->getTimeStamp();
            $lastStartTimeLog = $fixtures[0]->getDate()->format('Y-m-d H:i:s');
            $fixtureWeeks = array();
            foreach ($fixtures as $fixture) {
                if ($fixture->getTimeStamp() > $lastStartTime) {
                    $lastStartTime = $fixture->getTimeStamp();
                    $lastStartTimeLog = $fixture->getDate()->format('Y-m-d H:i:s');
                }
                $fixtureWeeks[] = $fixture->getDate()->format("W");
            }
            $this->logger->info(sprintf("Last fixture of round %d of %s starts on %s", $round, $leagueIdent, $lastStartTimeLog));

            $weekAverage = array_sum($fixtureWeeks)/count($fixtureWeeks);

            // get last fixture thats fits average week (if a game was shifted)
            foreach ($fixtures as $fixture) {
                $week = $fixture->getDate()->format("W");
                if ($week <= $weekAverage){
                    $lastStartTime = $fixture->getTimeStamp();
                }
            }

            // check if round is completely finished
            // TODO maybe check if round contains of right amount of fixtures to
            $currentTimestamp = (new DateTime())->getTimestamp();
            if ($currentTimestamp > $lastStartTime) {
                // store new round number in DB
                $roundRefreshed = $this->automaticUpdateSettingService->refreshCurrentRound($leagueIdent);
                if (!$roundRefreshed) {
                    return false;
                }
                $this->logger->info(sprintf("Round %d of %s completed", count($fixtures), $round));
            }
        }
        return true;
    }

    public function identifyCandidates()
    {
        $settings = $this->automaticUpdateSettingService->getSettings();
        foreach ($settings->getCompletedRounds() as $leagueIdent => $round) {
            $league = $this->leagueService->findByApiKey(UpdateService::LEAGUES[$leagueIdent]);
            $clubs = $this->clubService->findByLeagueAndSeason($league, 2021);
            foreach ($clubs as $club) {
                /** @var Club $club */
                if ($this->checkIfFormFitsCondition($club->getCurrentForm()) && $round == $club->getFormRound()) {
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
        $fixtureToDecorateId = $lastFixtureId + 1;
        $fixtureToDecorate = $this->fixtureService->findByDbId($fixtureToDecorateId);
        $nrOfStoredOdds = 0;
        while (!is_null($fixtureToDecorate) && $nrOfStoredOdds <= $this->fixtureDecorateLimit) {
            // getOddsForFixture will return false if no api calls were left
            $oddsStored = $this->updateService->storeOddsForFixture($fixtureToDecorate->getApiId());
            if ($oddsStored) {
                // set Fixture flag to decorated
                $fixtureUpdateData = (new FixtureData())->initFrom($fixtureToDecorate);
                $fixtureUpdateData->setIsBetDecorated(true);
                $fixtureUpdateData->setOddDecorationDate(new DateTime());
                $this->fixtureService->updateFixture($fixtureToDecorate, $fixtureUpdateData);
                // update settings
                $this->automaticUpdateSettingService->setLastOddDecoratedFixtureId($fixtureToDecorate->getId());
                // go on with next one
                $fixtureToDecorateId = $fixtureToDecorateId + 1;
                $fixtureToDecorate = $this->fixtureService->findByDbId($fixtureToDecorateId);
                $nrOfStoredOdds++;
            } else {
                $fixtureToDecorate = null;
            }
        }
        return $nrOfStoredOdds;
    }

    public function updateResultsOfAlreadyFinishedFixtures()
    {
        // get all undecorated fixtures
        $fixtureRoundsWithoutResults = $this->fixtureService->getFixturesWithoutResult();

        foreach($fixtureRoundsWithoutResults as $fixtureRound){
            // call round for league round
            $this->updateService->getFixturesForRound($fixtureRound['apiId'], $fixtureRound['startYear'], $fixtureRound['matchDay']);
        }
//        $settings = $this->automaticUpdateSettingService->getSettings();
//        foreach ($settings->getCompletedRounds() as $leagueIdent => $round) {
//            $league = $this->leagueService->findByIdent($leagueIdent);
//            $updatedFixtures = $this->updateService->updateFixtureForLeagueAndRound($league->getApiId(), 2021, $round + 1);
//            if ($updatedFixtures) {
//                $this->automaticUpdateSettingService->setCompletedRoundByLeague($leagueIdent, $round + 1);
//            }
//        }
    }


    public function getCurrentRoundMatchesForAllLeagues()
    {
        $settings = $this->automaticUpdateSettingService->getSettings();
        foreach ($settings->getCurrentRounds() as $leagueIdent => $round) {
            $league = $this->leagueService->findByIdent($leagueIdent);
            $this->updateService->storeFixtureForLeagueAndRound($league->getApiId(), 2021, $round);
        }
    }

    public function getLastRoundMatchesForAllLeagues()
    {
        $settings = $this->automaticUpdateSettingService->getSettings();
        foreach ($settings->getCurrentRounds() as $leagueIdent => $round) {
            $league = $this->leagueService->findByIdent($leagueIdent);
            $this->updateService->storeFixtureForLeagueAndRound($league->getApiId(), 2021, $round - 1);
        }
    }

    public function getCurrentMatchDays()
    {
        $this->automaticUpdateSettingService->refreshCurrentRounds();
    }

    private function checkIfFormFitsCondition(string $currentForm)
    {
        // form is read from right to left: LWLLL == 5. L, 4. L, 3. L, 2. W, 1. L
        $form = str_split($currentForm);
        if (count($form) > 1) {
            $lastMatch = $form[count($form) - 1];
            $matchBeforeLastMatch = $form[count($form) - 2];
            if (($matchBeforeLastMatch == 'D' || $matchBeforeLastMatch == 'L') && $lastMatch == 'W') {
                return true;
            }
        }
        return false;
    }

    public function goOnWithBetDecorationTimestampVariant()
    {
        $fixtures = $this->fixtureService->getUndecoratedFixturesTimeStampVariant();
dump($fixtures);
        usort(
            $fixtures,
            function (Fixture $a, Fixture $b) {
                if ($a->getTimeStamp() == $b->getTimeStamp()) {
                    return 0;
                }
                return ($a->getTimeStamp() > $b->getTimeStamp()) ? -1 : 1;
            }
        );

        $currentTimeStamp = (new DateTime('+5 days'))->getTimestamp();

        $nrOfStoredOdds = 0;
        while ($nrOfStoredOdds <= $this->fixtureDecorateLimit) {
            // skip if array end is reached or fixture is to far in the future
            if (!array_key_exists($nrOfStoredOdds, $fixtures) || $fixtures[$nrOfStoredOdds]->getTimeStamp() > $currentTimeStamp) {
                $nrOfStoredOdds++;
                continue;
            }
            // getOddsForFixture will return false if no api calls were left
            $oddsStored = $this->updateService->storeOddsForFixture($fixtures[$nrOfStoredOdds]->getApiId());
            if ($oddsStored) {
                // set Fixture flag to decorated
                $fixtureUpdateData = (new FixtureData())->initFrom($fixtures[$nrOfStoredOdds]);
                $fixtureUpdateData->setIsBetDecorated(true);
                $fixtureUpdateData->setOddDecorationDate(new DateTime());
                $this->fixtureService->updateFixture($fixtures[$nrOfStoredOdds], $fixtureUpdateData);
                $this->logger->info(sprintf("Stored bets for fixture %s", (string) $fixtures[$nrOfStoredOdds]));

                $nrOfStoredOdds++;
            }
        }
    }

    public function updateSeedings()
    {
        $settings = $this->automaticUpdateSettingService->getSettings();
        $currentRounds = $settings->getCurrentRounds();

        // search fixture without seeding and sort by newest ones
        $fixtures = $this->fixtureService->getNonSeededFixtures($this->seedingDecorateLimit);
        foreach ($fixtures as $fixture){
            // check if fixture still needs decoration (maybe another did it before)
            if($this->updateService->checkIfFixtureHaveSeedings($fixture)){
                continue;
            }

            // check if fixture is part of current round => get update for current round
            $currentRound = $currentRounds[$fixture->getLeague()->getIdent()];
            if ($currentRound === $fixture->getMatchDay()){
                $this->updateService->updateLeague($fixture->getLeague()->getIdent(), $fixture->getLeague()->getApiId(), 2021);
            }

            // check if fixture still needs decoration (maybe another did it before)
            if($this->updateService->checkIfFixtureHaveSeedings($fixture)){
                continue;
            }

            // else get latest form for club and calculate seedings for all older fixtures of it
            $this->updateService->updateSeedingFormsForFixture($fixture);
        }
    }

    public function updateSeedingsForAllOldOne()
    {
        // get all clubs
        $counter = 0;
        foreach (UpdateService::LEAGUES as $leagueIdent => $leagueApiKey){
            $league = $this->leagueService->findByApiKey($leagueApiKey);
            $clubs = $this->clubService->findByLeagueAndSeason($league, 2021);
            foreach ($clubs as $club){
                if ($counter < $this->seedingDecorateLimit){
                    $seedingsCreated = $this->updateService->getSeedingsForClubTillCurrentRound($league, 2021, $club);
                    if ($seedingsCreated){
                        $counter++;
                    }
                }
            }
        }
    }
}
