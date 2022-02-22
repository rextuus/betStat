<?php


namespace App\Service\Evaluation;


use App\Entity\Club;
use App\Entity\Fixture;
use App\Entity\Seeding;
use App\Service\Club\ClubService;
use App\Service\Evaluation\Entity\Form;
use App\Service\Fixture\FixtureService;
use App\Service\FixtureOdd\FixtureOddService;
use App\Service\League\LeagueService;
use App\Service\Season\SeasonService;
use App\Service\Seeding\SeedingService;

class EvaluationService
{
    /**
     * @var FixtureService
     */
    private $fixtureService;

    /**
     * @var FixtureOddService
     */
    private $fixtureOddService;

    /**
     * @var LeagueService
     */
    private $leagueService;

    /**
     * @var SeasonService
     */
    private $seasonService;

    /**
     * @var ClubService
     */
    private $clubService;

    /**
     * @var SeedingService
     */
    private $seedingService;

    /**
     * EvaluationService constructor.
     * @param FixtureService $fixtureService
     * @param FixtureOddService $fixtureOddService
     * @param LeagueService $leagueService
     * @param SeasonService $seasonService
     * @param ClubService $clubService
     * @param SeedingService $seedingService
     */
    public function __construct(FixtureService $fixtureService, FixtureOddService $fixtureOddService, LeagueService $leagueService, SeasonService $seasonService, ClubService $clubService, SeedingService $seedingService)
    {
        $this->fixtureService = $fixtureService;
        $this->fixtureOddService = $fixtureOddService;
        $this->leagueService = $leagueService;
        $this->seasonService = $seasonService;
        $this->clubService = $clubService;
        $this->seedingService = $seedingService;
    }


    public function calculateFormForAllTeamsOfRound(int $apiLeagueId, int $seasonYear, int $round)
    {
        if ($round < 3){
            return;
        }
        $league = $this->leagueService->findByApiKey($apiLeagueId);

        $season = $this->seasonService->findByYears($seasonYear, $seasonYear+1, $league);

        $clubs = $season->getClubs();
        $forms = array();
        for ($roundNr = 1; $roundNr <= 2; $roundNr++){
            foreach($clubs as $club){
                    /** @var Fixture[] $fixture */
                    $fixture = $this->fixtureService->findByClubAndSeasonAndRound($club, $seasonYear, $round-$roundNr);

                    if (!array_key_exists($club->getName(), $forms)){
                        $form = new Form();
                        $form->addFixtureToSeries($this->getResultVariantForClub($club, $fixture[0]));
                        $forms[$club->getName()] = $form;
                    }else{
                        $form = $forms[$club->getName()];
                        $form->addFixtureToSeries($this->getResultVariantForClub($club, $fixture[0]));
                    }
                }
        }
        foreach ($forms as $club => $form){
            dump($club);
            dump($form->checkIfSeriesFitsCondition());
        }
    }

    private function getResultVariantForClub(Club $club, Fixture $fixture): string
    {
        if ($fixture->getScoreHomeFull() === $fixture->getScoreAwayFull()){
            return 'D';
        }
        if ($fixture->getScoreHomeFull() > $fixture->getScoreAwayFull() && $fixture->getHomeTeam() === $club){
            return 'W';
        }
        if ($fixture->getScoreAwayFull() > $fixture->getScoreHomeFull() && $fixture->getAwayTeam() === $club){
            return 'W';
        }
        return 'L';
    }

    /**
     * @param Fixture $fixture
     * @return Seeding[]
     */
    public function getSeedingsForFixture(Fixture $fixture): array
    {
        // ATTENTION. YOU got the seeding from before this round
        $homeSeeding = $this->seedingService->findByClubAndSeasonAndLRound($fixture->getHomeTeam(), $fixture->getSeason(), $fixture->getMatchDay() -1);
        $awaySeeding = $this->seedingService->findByClubAndSeasonAndLRound($fixture->getAwayTeam(), $fixture->getSeason(), $fixture->getMatchDay() -1);

        // be aware that we need only the last ones not the current game

        if (is_null($homeSeeding)){
            $homeSeeding = '-';
        }else{
            $homeSeeding = $homeSeeding->getForm();
            $homeSeeding = substr($homeSeeding, strlen($homeSeeding)-5, strlen($homeSeeding)-1);
        }

        if (is_null($awaySeeding)){
            $awaySeeding = '-';
        }else{
            $awaySeeding = $awaySeeding->getForm();
            $awaySeeding = substr($homeSeeding, strlen($homeSeeding)-5, strlen($homeSeeding)-1);
        }

        return ['homeSeeding' => $homeSeeding, 'awaySeeding' => $awaySeeding];
    }

    public function getCandidateForFixture(Fixture $fixture)
    {
        $homeSeeding = $this->seedingService->findByClubAndSeasonAndLRound($fixture->getHomeTeam(), $fixture->getSeason(), $fixture->getMatchDay() -1 );
        $awaySeeding = $this->seedingService->findByClubAndSeasonAndLRound($fixture->getAwayTeam(), $fixture->getSeason(), $fixture->getMatchDay()- 1);

        if (is_null($homeSeeding) || is_null($awaySeeding)){
            return -1;
        }

        if (is_null($homeSeeding->getForm()) || is_null($awaySeeding->getForm())){
            return -1;
        }

        $seedings = $this->getSeedingsForFixture($fixture);
        $homeIsCandidate = $this->checkIfFormFitsCondition($seedings['homeSeeding']);
        $awayIsCandidate = $this->checkIfFormFitsCondition(strrev($seedings['awaySeeding']));
        if ($homeIsCandidate && $awayIsCandidate){
            return 0;
        }
        if ($homeIsCandidate && !$awayIsCandidate){
            return 1;
        }
        if (!$homeIsCandidate && $awayIsCandidate){
            return 2;
        }
        return -1;
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
