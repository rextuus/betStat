<?php

namespace App\Handler;

use App\Entity\Fixture;
use App\Entity\SimulationResult;
use App\Form\SimulationCreateData;
use App\Repository\FixtureRepository;
use App\Repository\SimulationResultRepository;
use App\Service\Evaluation\EvaluationService;
use App\Service\Fixture\FixtureService;
use App\Service\FixtureOdd\FixtureOddService;
use App\Service\Simulation\Simulation;
use App\Service\Simulation\SimulationService;
use App\Service\SimulationResult\SimulationResultService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SimulateBetRowHandler implements MessageHandlerInterface
{
    /**
     * @var SimulationService
     */
    private $simulationService;

    /**
     * @var SimulationResultRepository
     */
    private $simulationResultRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var FixtureRepository
     */
    private $fixtureRepository;

    /**
     * @var EvaluationService
     */
    private $evaluationService;

    /**
     * @var FixtureOddService
     */
    private $fixtureOddService;

    /**
     * @param SimulationService $simulationService
     * @param SimulationResultRepository $simulationResultRepository
     * @param EntityManagerInterface $entityManager
     * @param MessageBusInterface $messageBus
     * @param FixtureRepository $fixtureRepository
     * @param EvaluationService $evaluationService
     * @param FixtureOddService $fixtureOddService
     */
    public function __construct(SimulationService $simulationService, SimulationResultRepository $simulationResultRepository, EntityManagerInterface $entityManager, MessageBusInterface $messageBus, FixtureRepository $fixtureRepository, EvaluationService $evaluationService, FixtureOddService $fixtureOddService)
    {
        $this->simulationService = $simulationService;
        $this->simulationResultRepository = $simulationResultRepository;
        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;
        $this->fixtureRepository = $fixtureRepository;
        $this->evaluationService = $evaluationService;
        $this->fixtureOddService = $fixtureOddService;
    }


    public function __invoke(SimulateBetRow $simulateBetRow)
    {

        $id = $simulateBetRow->getSimulationResultId();
        $simulation = $this->simulationResultRepository->find($id);

//        $this->simulationService->simulatePage($id);

//        $this->simulatePage($simulationResult);

//        $fromDate = new DateTime('2000-01-01');
//        $untilDate = new DateTime('2030-01-01');
//
//        $allFixtures =  $this->fixtureRepository->findAllSortedByFilter(
//            [
//                'maxResults' => 10000,
//                'oddDecorated' => true,
//                'from' => $simulation->getFromDate() ? $simulation->getFromDate()->getTimestamp() : $fromDate->getTimestamp(),
//                'until' => $simulation->getUntilDate() ? $simulation->getUntilDate()->getTimestamp() : $untilDate->getTimestamp(),
//                'leagues' => $simulation->getLeagues()
//            ],
//            $simulation->getCurrentPage(),
//            'ASC'
//        );
//
//        // create credentials
//        $commitmentChange = $simulation->getCommitmentChange();
//        $commitmentChanges = [$simulation->getCommitment()];
//        if ($commitmentChange !== '-'){
//            $commitmentChanges = explode(';', $commitmentChange);
//        }
//
//        $oddAverage = [];
//        $commitmentChanger = $simulation->getCommitmentChanger();
//        $longestSeries = 0;
//
//        foreach ($allFixtures['paginator'] as $fixture){
//
//            $toBetOn = $this->evaluationService->getCandidateForFixture($fixture);
//            $odds = $this->fixtureOddService->findByFixture($fixture);
//            if (!empty($odds) && $toBetOn != -1 && $fixture->isPlayed()){
//
//                $singleHome = array();
//                $singleDraw = array();
//                $singleAway = array();
//                $doubleHome = array();
//                $doubleAway = array();
//
//                foreach ($odds as $odd){
//                    // classic
//                    if ($odd->getType() === 'classic'){
//                        $singleHome[] = $odd->getHomeOdd();
//                        $singleDraw[] = $odd->getDrawOdd();
//                        $singleAway[] = $odd->getAwayOdd();
//                    }
//                    if ($odd->getType() === 'doubleChance'){
//                        $doubleHome[] = $odd->getHomeOdd();
//                        $doubleAway[] = $odd->getAwayOdd();
//                    }
//                }
//
//                // to betOn have to loose or draw: Beton = 2 if winner == 2 => false
//                $winCondition = $fixture->getWinner() != $toBetOn && $fixture->getWinner() !== 0;
//
//                // if home is candidate to loose => choose away double odd
//                $odd = $this->calculateAverageForSet($singleAway);
//                // if away is candidate to loose => choose home double odd
//                if ($toBetOn == 2){
//                    $odd = $this->calculateAverageForSet($singleHome);
//                }
//                // dont place bets in invalid range
//                if ($odd < $simulation->getOddBorderLow() || $odd > $simulation->getOddBorderHigh()){
//                    continue;
//                }
//
//                if ($winCondition){
//                    $simulation->setWonPlacements($simulation->getWonPlacements() + 1);
//                }else{
//                    $simulation->setLoosePlacements($simulation->getLoosePlacements() + 1);
//                }
//
//                $simulation->setMadePlacements($simulation->getMadePlacements()+1);
//
//                $this->placeBet2(
//                    $simulation,
//                    $odd,
//                    $winCondition
//                );
//
//                // create archive placement
//                if ($winCondition){
//                    $placement = sprintf(
//                        "Set %d on fixture %s with odd %f: Wished result %s \nWon!!! New cash register is: %f",
//                        $simulation->getCurrentCommitment(),
//                        (string) $fixture,
//                        $odd,
//                        $toBetOn == 1 ? "Away" : "Home",
//                        $simulation->getCashRegister()
//                    );
//                }else{
//                    $placement = sprintf(
//                        "Set %d on fixture %s with odd %f: Wished result %s \nLoose!!! New cash register is: %f",
//                        $simulation->getCurrentCommitment(),
//                        (string) $fixture,
//                        $odd,
//                        $toBetOn == 1 ? "Away" : "Home",
//                        $simulation->getCashRegister()
//                    );
//                }
//                $simulation->addPlacement($placement);
//
//                $oddAverage[] = $odd;
//
//                if ($winCondition){
//                    $commitmentChanger = 0;
//                }else{
//                    $commitmentChanger++;
//                    if ($commitmentChanger == count($commitmentChanges)){
//                        $commitmentChanger = 0;
//                    }
//                }
//                if ($commitmentChanger > $longestSeries){
//                    $longestSeries = $commitmentChanger;
//                }
//
//                $simulation->setCurrentCommitment($commitmentChanges[$commitmentChanger]);
//            }
//        }
//        $simulation->setCommitmentChanger($commitmentChanger);

        $simulationProxy = new Simulation();
        $simulationProxy->setCashRegister($simulation->getCashRegister());
        $simulationProxy->setCommitment($simulation->getCommitment());
        $simulationProxy->setCurrentCommitment($simulation->getCommitment());
        $simulationProxy->setOddBorderHigh($simulation->getOddBorderHigh());
        $simulationProxy->setOddBorderLow($simulation->getOddBorderLow());
        $simulationProxy->setCommitmentChange($simulation->getCommitmentChange());
        $simulationProxy->setFromTimestamp($simulation->getFromDate()->getTimestamp());
        $simulationProxy->setUntilTimestamp($simulation->getUntilDate()->getTimestamp());
        $simulationProxy->setLeagues($simulation->getLeagues());
        $simulationProxy->setCommitmentChanger($simulation->getCommitmentChanger());
        $simulationProxy->setLongestLoosingSeries(0);
        $simulationProxy->setPlacements([]);
        $result = $this->testFunction($simulationProxy, $simulation->getCurrentPage());
        dump($result);

        $simulation->setIdent('Changed');
        $simulation->setCommitmentChanger('3');
        $simulation->setWonPlacements(88);
        $simulation->setLoosePlacements(77);
        $simulation->setTotalPages(65);
        $simulation->setCurrentPage($simulation->getCurrentPage()+1);
        $simulation->setCashRegister(4);
        $simulation->addPlacement('TestPlacement');

        $simulation->setParentId($id);
        $this->entityManager->persist($simulation);
        $this->entityManager->flush();
        $simulation = $this->simulationResultRepository->find($id);
        sleep(10);

        if ($simulation->getCurrentPage() <= $simulation->getTotalPages()){
            $message = new SimulateBetRow($simulation->getId());
            $this->messageBus->dispatch($message);
        }
    }

    private function testFunction(Simulation $simulation, int $page)
    {
        $allFixtures =  $this->fixtureRepository->findAllSortedByFilter(
            [
                'maxResults' => 10000,
                'oddDecorated' => true,
                'from' => $simulation->getFromTimestamp(),
                'until' => $simulation->getUntilTimestamp(),
                'leagues' => $simulation->getLeagues()
            ],
            $page,
            'ASC'
        );

//        // create credentials
//        $commitmentChange = $simulation->getCommitmentChange();
//        $commitmentChanges = [$simulation->getCommitment()];
//        if ($commitmentChange !== '-'){
//            $commitmentChanges = explode(';', $commitmentChange);
//        }
//
//        $oddAverage = [];
//        $commitmentChanger = $simulation->getCommitmentChanger();
//        $longestSeries = 0;
//
//        foreach ($allFixtures['paginator'] as $fixture){
//
//            $toBetOn = $this->evaluationService->getCandidateForFixture($fixture);
//            $odds = $this->fixtureOddService->findByFixture($fixture);
//            if (!empty($odds) && $toBetOn != -1 && $fixture->isPlayed()){
//
//                $singleHome = array();
//                $singleDraw = array();
//                $singleAway = array();
//                $doubleHome = array();
//                $doubleAway = array();
//
//                foreach ($odds as $odd){
//                    // classic
//                    if ($odd->getType() === 'classic'){
//                        $singleHome[] = $odd->getHomeOdd();
//                        $singleDraw[] = $odd->getDrawOdd();
//                        $singleAway[] = $odd->getAwayOdd();
//                    }
//                    if ($odd->getType() === 'doubleChance'){
//                        $doubleHome[] = $odd->getHomeOdd();
//                        $doubleAway[] = $odd->getAwayOdd();
//                    }
//                }
//
//                // to betOn have to loose or draw: Beton = 2 if winner == 2 => false
//                $winCondition = $fixture->getWinner() != $toBetOn && $fixture->getWinner() !== 0;
//
//                // if home is candidate to loose => choose away double odd
//                $odd = $this->calculateAverageForSet($singleAway);
//                // if away is candidate to loose => choose home double odd
//                if ($toBetOn == 2){
//                    $odd = $this->calculateAverageForSet($singleHome);
//                }
//                // dont place bets in invalid range
//                if ($odd < $simulation->getOddBorderLow() || $odd > $simulation->getOddBorderHigh()){
//                    continue;
//                }
//
//                if ($winCondition){
//                    $simulation->setWonPlacements($simulation->getWonPlacements() + 1);
//                }else{
//                    $simulation->setLoosePlacements($simulation->getLoosePlacements() + 1);
//                }
//                $simulation->setMadePlacements($simulation->getMadePlacements()+1);
//
//                $this->placeBet(
//                    $simulation,
//                    $odd,
//                    $winCondition
//                );
//
//                // create archive placement
//                if ($winCondition){
//                    $placement = sprintf(
//                        "Set %d on fixture %s with odd %f: Wished result %s \nWon!!! New cash register is: %f",
//                        $simulation->getCurrentCommitment(),
//                        (string) $fixture,
//                        $odd,
//                        $toBetOn == 1 ? "Away" : "Home",
//                        $simulation->getCashRegister()
//                    );
//                }else{
//                    $placement = sprintf(
//                        "Set %d on fixture %s with odd %f: Wished result %s \nLoose!!! New cash register is: %f",
//                        $simulation->getCurrentCommitment(),
//                        (string) $fixture,
//                        $odd,
//                        $toBetOn == 1 ? "Away" : "Home",
//                        $simulation->getCashRegister()
//                    );
//                }
//                $simulation->addPlacement($placement);
//
//                $oddAverage[] = $odd;
//
//                if ($winCondition){
//                    $commitmentChanger = 0;
//                }else{
//                    $commitmentChanger++;
//                    if ($commitmentChanger == count($commitmentChanges)){
//                        $commitmentChanger = 0;
//                    }
//                }
//                if ($commitmentChanger > $longestSeries){
//                    $longestSeries = $commitmentChanger;
//                }
//
//                $simulation->setCurrentCommitment($commitmentChanges[$commitmentChanger]);
//            }
//        }
//        $simulation->setCommitmentChanger($commitmentChanger);
//        if ($longestSeries > $simulation->getLongestLoosingSeries()){
//            $simulation->setLongestLoosingSeries($longestSeries);
//        }
//
//        if (count($oddAverage) > 0){
//            $oddAverageValue = array_sum($oddAverage)/count($oddAverage);
//            $simulation->setOddAverage(($oddAverageValue + $simulation->getOddAverage()) / 2);
//            $simulation->setOddAverage($oddAverageValue);
//        }

        return $simulation;
    }

    public function simulatePage(SimulationResult $simulation): SimulationResult
    {

        return $simulation;
        // save changes
//        $this->simulationResultService->update($simulation);

//        if ($simulation->getCurrentPage() <= $simulation->getTotalPages()) {
//            $message = new SimulateBetRow($simulation->getId() + 1);
//            $this->messageBus->dispatch($message);
//        }
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
