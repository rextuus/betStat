<?php

namespace App\Service\Simulation;

use App\Entity\FixtureOdd;
use App\Entity\SimulationResult;
use App\Entity\Simulator;
use App\Form\SimulationCreateData;
use App\Handler\SimulateBetRow;
use App\Repository\FixtureRepository;
use App\Service\Evaluation\EvaluationService;
use App\Service\Fixture\FixtureTransportFactory;
use App\Service\FixtureOdd\FixtureOddService;
use App\Service\SimulationResult\SimulationResultService;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

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
     * @var SimulationResultService
     */
    private $simulationResultService;

    /**
         * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param FixtureTransportFactory $fixtureTransportFactory
     * @param EvaluationService $evaluationService
     * @param FixtureRepository $fixtureRepository
     * @param FixtureOddService $fixtureOddService
     * @param SimulationResultService $simulationResultService
     * @param MessageBusInterface $messageBus
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(FixtureTransportFactory $fixtureTransportFactory, EvaluationService $evaluationService, FixtureRepository $fixtureRepository, FixtureOddService $fixtureOddService, SimulationResultService $simulationResultService, MessageBusInterface $messageBus, EntityManagerInterface $entityManager)
    {
        $this->fixtureTransportFactory = $fixtureTransportFactory;
        $this->evaluationService = $evaluationService;
        $this->fixtureRepository = $fixtureRepository;
        $this->fixtureOddService = $fixtureOddService;
        $this->simulationResultService = $simulationResultService;
        $this->messageBus = $messageBus;
        $this->entityManager = $entityManager;
    }

    public function initSimulation(SimulationCreateData $data){
        $simulation = (new SimulationResult())->initFrom($data);

        $fromDate = new DateTime('2000-01-01');
        $untilDate = new DateTime('2030-01-01');

        $allFixtures =  $this->fixtureRepository->findAllSortedByFilter(
            [
                'maxResults' => 10000,
                'oddDecorated' => true,
                'from' => $data->getFrom() ? $data->getFrom()->getTimestamp() : $fromDate->getTimestamp(),
                'until' => $data->getUntil() ? $data->getUntil()->getTimestamp() : $untilDate->getTimestamp(),
                'leagues' => $data->getLeagues()
            ],
            1,
            'ASC'
        );

        $simulation->setTotalPages($allFixtures['count']);
        $simulation->setCurrentPage(1);

        if(!is_null($data->getFrom())){
            $simulation->setFromDate($data->getFrom());
        }else{
            $simulation->setFromDate($fromDate);
        }

        if(!is_null($data->getUntil())){
            $simulation->setUntilDate($data->getUntil());
        }else{
            $simulation->setUntilDate($untilDate);
        }

        $simulation->setLongestLoosingSeries(0);
        $simulation->setLongestLoosingSeries(0);
        $simulation->setCommitmentChanger(0);
        $simulation->setMadePlacements(0);
        $simulation->setWonPlacements(0);
        $simulation->setLoosePlacements(0);
        $simulation->setOddAverage(0.0);
        $simulation->setState(0);
        $simulation = $this->simulationResultService->persist($simulation);

        // set first message
        $message = new SimulateBetRow($simulation->getId(), $simulation->getIdent());
        $this->messageBus->dispatch($message);
    }

    public function simulatePage(SimulationResult $simulation){
//        $simulation = $this->simulationResultService->findById($simulationResultId);

        $fromDate = new DateTime('2000-01-01');
        $untilDate = new DateTime('2030-01-01');

        $allFixtures =  $this->fixtureRepository->findAllSortedByFilter(
            [
                'maxResults' => 10000,
                'oddDecorated' => true,
                'from' => $simulation->getFromDate() ? $simulation->getFromDate()->getTimestamp() : $fromDate->getTimestamp(),
                'until' => $simulation->getUntilDate() ? $simulation->getUntilDate()->getTimestamp() : $untilDate->getTimestamp(),
                'leagues' => $simulation->getLeagues()
            ],
            $simulation->getCurrentPage(),
            'ASC'
        );

        // create credentials
        $commitmentChange = $simulation->getCommitmentChange();
        $commitmentChanges = [$simulation->getCommitment()];
        if ($commitmentChange !== '-'){
            $commitmentChanges = explode(';', $commitmentChange);
        }

        $oddAverage = [];
        $commitmentChanger = $simulation->getCommitmentChanger();
        $longestSeries = 0;

        foreach ($allFixtures['paginator'] as $fixture){

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
                        $simulation->getCashRegister()
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
        $simulation->setCommitmentChanger($commitmentChanger);
        if ($longestSeries > $simulation->getLongestLoosingSeries()){
            $simulation->setLongestLoosingSeries($longestSeries);
        }

        if (count($oddAverage) > 0){
            $oddAverageValue = array_sum($oddAverage)/count($oddAverage);
            if($simulation->getCurrentPage() > 1){
                $simulation->setOddAverage(($oddAverageValue + $simulation->getOddAverage()) / 2);
            }else{
                $simulation->setOddAverage($oddAverageValue);
            }
        }

        $simulation->setCurrentPage($simulation->getCurrentPage()+1);

        // save changes
//        $this->entityManager->persist($simulation);
        $this->simulationResultService->update($simulation);
    }


    private function placeBet(SimulationResult $simulation, float $doubleAway, bool $betWon)
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
