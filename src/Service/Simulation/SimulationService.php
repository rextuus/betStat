<?php

namespace App\Service\Simulation;

use App\Entity\FixtureOdd;
use App\Entity\Simulator;
use App\Repository\FixtureRepository;
use App\Service\Evaluation\EvaluationService;
use App\Service\Fixture\FixtureTransportFactory;
use App\Service\FixtureOdd\FixtureOddService;

class SimulationService
{
    /**
     * @var FixtureTransportFactory
     */
    private $fixtureTransportFactory;

    /**
     * @var EvaluationService
     */
    private $evaluationService;

    /**
     * @var FixtureRepository
     */
    private $fixtureRepository;

    /**
     * @var FixtureOddService
     */
    private $fixtureOddService;

    /**
     * @param FixtureTransportFactory $fixtureTransportFactory
     * @param EvaluationService $evaluationService
     * @param FixtureRepository $fixtureRepository
     * @param FixtureOddService $fixtureOddService
     */
    public function __construct(FixtureTransportFactory $fixtureTransportFactory, EvaluationService $evaluationService, FixtureRepository $fixtureRepository, FixtureOddService $fixtureOddService)
    {
        $this->fixtureTransportFactory = $fixtureTransportFactory;
        $this->evaluationService = $evaluationService;
        $this->fixtureRepository = $fixtureRepository;
        $this->fixtureOddService = $fixtureOddService;
    }


    public function simulateBetSeries(Simulation $simulation, array $leagues){
        $allFixtures =  $this->fixtureRepository->findAllSortedByFilter(
            [
                'maxResults' => 10000,
                'oddDecorated' => true,
                'leagues' => $leagues
            ]
        );

        $oddAverage = [];
        $commitmentChange = $simulation->getCommitmentChange();
        $commitmentChanges = [$simulation->getCommitment()];
        if ($commitmentChange !== '-'){
            $commitmentChanges = explode(';', $commitmentChange);
        }

        $commitmentChanger = 0;
        $longestSeries = 0;
        foreach ($allFixtures as $fixture){
            $toBetOn = $this->evaluationService->getCandidateForFixture($fixture);
            $odds = $this->fixtureOddService->findByFixture($fixture);
            if (!empty($odds) && $toBetOn != -1 && $fixture->isPlayed()){

                $singleHome = array();
                $singleDraw = array();
                $singleAway = array();
                $doubleHome = array();
                $doubleAway = array();

                foreach ($odds as $odd){
                    // classic
                    if ($odd->getType() === 'classic'){
                        $singleHome[] = $odd->getHomeOdd();
                        $singleDraw[] = $odd->getDrawOdd();
                        $singleAway[] = $odd->getAwayOdd();
                    }
                    if ($odd->getType() === 'doubleChance'){
                        $doubleHome[] = $odd->getHomeOdd();
                        $doubleAway[] = $odd->getAwayOdd();
                    }
                }

                // to betOn have to loose or draw: Beton = 2 if winner == 2 => false
                $winCondition = $fixture->getWinner() != $toBetOn && $fixture->getWinner() !== 0;

                // if home is candidate to loose => choose away double odd
                $odd = $this->calculateAverageForSet($singleAway);
                // if away is candidate to loose => choose home double odd
                if ($toBetOn == 2){
                    $odd = $this->calculateAverageForSet($singleHome);
                }
                // dont place bets in invalid range
                if ($odd < $simulation->getOddBorderLow() || $odd > $simulation->getOddBorderHigh()){
                    continue;
                }

                if ($winCondition){
                    $simulation->setWonPlacements($simulation->getWonPlacements() + 1);
                }else{
                    $simulation->setLoosePlacements($simulation->getLoosePlacements() + 1);
                }
                $simulation->setMadePlacements($simulation->getMadePlacements()+1);

                $this->placeBet(
                    $simulation,
                    $odd,
                    $winCondition
                );

                // create archive placement
                if ($winCondition){
                    $placement = sprintf(
                        "Set %d on fixture %s with odd %f: Wished result %s \nWon!!! New cash register is: %f",
                        $simulation->getCurrentCommitment(),
                        (string) $fixture,
                        $odd,
                        $toBetOn == 1 ? "Away" : "Home",
                        $simulation->getCashRegister()
                    );
                }else{
                    $placement = sprintf(
                        "Set %d on fixture %s with odd %f: Wished result %s \nLoose!!! New cash register is: %f",
                        $simulation->getCurrentCommitment(),
                        (string) $fixture,
                        $odd,
                        $toBetOn == 1 ? "Away" : "Home",
                        $simulation->getCashRegister(),
                    );
                }
                $simulation->addPlacement($placement);

                $oddAverage[] = $odd;

                if ($winCondition){
                    $commitmentChanger = 0;
                }else{
                    $commitmentChanger++;
                    if ($commitmentChanger == count($commitmentChanges)){
                        $commitmentChanger = 0;
                    }
                }
                if ($commitmentChanger > $longestSeries){
                    $longestSeries = $commitmentChanger;
                }

                $simulation->setCurrentCommitment($commitmentChanges[$commitmentChanger]);
            }
        }
        $simulation->setLongestLoosingSeries($longestSeries);
        $simulation->setOddAverage(array_sum($oddAverage)/count($oddAverage));
    }

    private function placeBet(Simulation $simulation, float $doubleAway, bool $betWon)
    {
        // reduce by commitment
        $simulation->setCashRegister($simulation->getCashRegister() - $simulation->getCurrentCommitment());
        // add possible win
        if ($betWon){
            $win = $doubleAway * $simulation->getCurrentCommitment();
            $tax = 0.05 * $win;
            $simulation->setCashRegister($simulation->getCashRegister() + $win - $tax);
        }
    }

    private function calculateAverageForSet(array $set){
        return !empty($set) ? array_sum($set) / count($set) : 0;
    }
}
