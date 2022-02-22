<?php


namespace App\Service\Fixture;


use App\Entity\FixtureOdd;
use App\Repository\FixtureRepository;
use App\Service\Evaluation\EvaluationService;
use App\Service\Fixture\Transport\FixtureTransport;
use App\Service\FixtureOdd\FixtureOddService;
use function PHPUnit\Framework\isEmpty;

class FixtureTransportFactory
{

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
     * FixtureTransportFactory constructor.
     * @param FixtureRepository $fixtureRepository
     * @param EvaluationService $evaluationService
     * @param FixtureOddService $fixtureOddService
     */
    public function __construct(FixtureRepository $fixtureRepository, EvaluationService $evaluationService, FixtureOddService $fixtureOddService)
    {
        $this->fixtureRepository = $fixtureRepository;
        $this->evaluationService = $evaluationService;
        $this->fixtureOddService = $fixtureOddService;
    }

    /**
     * @return FixtureTransport[]
     */
    public function createFixtureTransports(): array
    {
        $allFixtures = $this->fixtureRepository->findAllSortedByFilter();
        $transports = array();
        foreach($allFixtures as $fixture){
            $fixtureTransport =  new FixtureTransport();
            $fixtureTransport->setFixtureId($fixture->getId());
            $fixtureTransport->setRound($fixture->getMatchDay());
            $fixtureTransport->setDate($fixture->getDate()->format('Y-m-d H:i:s'));
            $fixtureTransport->setPlayed($fixture->isPlayed());
            $fixtureTransport->setResult($fixture->getResult());
            $fixtureTransport->setDescription($fixture->getDescription());
            $fixtureTransport->setBetDecorated($fixture->getIsBetDecorated());
            $fixtureTransport->setIsCandidate($fixture->getIsDoubleChanceCandidate());
            $fixtureTransport->setToBetOn($this->evaluationService->getCandidateForFixture($fixture));
            $fixtureTransport->setHomeGoals($fixture->getScoreHomeFull());
            $fixtureTransport->setAwayGoals($fixture->getScoreAwayFull());
            $fixtureTransport->setLeague($fixture->getLeague()->getIdent());

            $seedings = $this->evaluationService->getSeedingsForFixture($fixture);
            $fixtureTransport->setHomeForm($seedings['homeSeeding']);
            $fixtureTransport->setAwayForm($seedings['awaySeeding']);

            // check if real odds exists
            $fixtureTransport->setRealBetDecorated(false);
            $odds = $this->fixtureOddService->findByFixture($fixture);

            if (!empty($odds)){
                $fixtureTransport->setRealBetDecorated(true);

                $singleHome = array();
                $singleDraw = array();
                $singleAway = array();
                $doubleHome = array();
                $doubleAway = array();

                foreach ($odds as $odd){
                    /** @var FixtureOdd $odd */
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

                $fixtureTransport->setSingleHome($this->calculateAverageForSet($singleHome));
                $fixtureTransport->setSingleDraw($this->calculateAverageForSet($singleDraw));
                $fixtureTransport->setSingleAway($this->calculateAverageForSet($singleAway));
                $fixtureTransport->setHomeDouble($this->calculateAverageForSet($doubleHome));
                $fixtureTransport->setAwayDouble($this->calculateAverageForSet($doubleAway));

                if ($fixture->getIsBetDecorated() && $fixture->getIsDoubleChanceCandidate()){
                    $highlighting = [false, false, false, false, false];
                    switch ($this->evaluationService->getCandidateForFixture($fixture)){
                        case 1:
                            $highlighting = [true, true, false, true, false];
                            break;
                        case 2:
                            $highlighting = [false, true, true, false, true];
                            break;
                    }
                    $fixtureTransport->setHighlighted($highlighting);
                }
            }

            $transports[] = $fixtureTransport;
        }
        return $transports;
    }

    private function calculateAverageForSet(array $set){
        return !empty($set) ? array_sum($set) / count($set) : 0;
    }
}
