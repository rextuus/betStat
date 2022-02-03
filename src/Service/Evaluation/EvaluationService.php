<?php


namespace App\Service\Evaluation;


use App\Entity\Club;
use App\Entity\Fixture;
use App\Service\Club\ClubService;
use App\Service\Evaluation\Entity\Form;
use App\Service\Fixture\FixtureService;
use App\Service\FixtureOdd\FixtureOddService;
use App\Service\League\LeagueService;
use App\Service\Season\SeasonService;

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
     * EvaluationService constructor.
     * @param FixtureService $fixtureService
     * @param FixtureOddService $fixtureOddService
     * @param LeagueService $leagueService
     * @param SeasonService $seasonService
     * @param ClubService $clubService
     */
    public function __construct(FixtureService $fixtureService, FixtureOddService $fixtureOddService, LeagueService $leagueService, SeasonService $seasonService, ClubService $clubService)
    {
        $this->fixtureService = $fixtureService;
        $this->fixtureOddService = $fixtureOddService;
        $this->leagueService = $leagueService;
        $this->seasonService = $seasonService;
        $this->clubService = $clubService;
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

}
