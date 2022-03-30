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
    public function createFixtureTransports(array $filter): array
    {

        $allFixtures = $this->fixtureRepository->findAllSortedByFilter($filter, 1);
        $transports = array();
        dump($allFixtures);
        foreach($allFixtures['paginator'] as $fixture){
            $fixtureDto =  new FixtureTransport();
            $fixtureDto->setFixtureId($fixture->getId());
            $fixtureDto->setRound($fixture->getMatchDay());
            $fixtureDto->setDate($fixture->getDate()->format('Y-m-d H:i:s'));
            $fixtureDto->setPlayed($fixture->isPlayed());
            $fixtureDto->setResult($fixture->getResult());
            $fixtureDto->setDescription($fixture->getDescription());
            $fixtureDto->setBetDecorated($fixture->getIsBetDecorated());
            $fixtureDto->setIsCandidate($fixture->getIsDoubleChanceCandidate());
            $fixtureDto->setToBetOn($this->evaluationService->getCandidateForFixture($fixture));
            $fixtureDto->setHomeGoals($fixture->getScoreHomeFull());
            $fixtureDto->setAwayGoals($fixture->getScoreAwayFull());
            $fixtureDto->setLeague($fixture->getLeague()->getIdent());

            $seedings = $this->evaluationService->getSeedingsForFixture($fixture);
            $fixtureDto->setHomeForm($seedings['homeSeeding']);
            $fixtureDto->setAwayForm($seedings['awaySeeding']);

            $wishedResult = 0;
            if (isset($filter['useDraws']) && !$filter['useDraws'] && $fixtureDto->getToBetOn() == 0){
                $fixtureDto->setToBetOn(-1);
            }
            if ($fixtureDto->getToBetOn() !== -1 && $fixture->isPlayed()){
                if ($fixture->getWinner() !== $fixtureDto->getToBetOn() && $fixture->getWinner() !== 0){
                    $wishedResult = 1;
                }
                else{
                    $wishedResult = -1;
                }
            }
            $fixtureDto->setWishedResult($wishedResult);

            // check if real odds exists
            $fixtureDto->setRealBetDecorated(false);
            $odds = $this->fixtureOddService->findByFixture($fixture);

            if (!empty($odds)){
                $fixtureDto->setRealBetDecorated(true);

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

                $fixtureDto->setSingleHome($this->calculateAverageForSet($singleHome));
                $fixtureDto->setSingleDraw($this->calculateAverageForSet($singleDraw));
                $fixtureDto->setSingleAway($this->calculateAverageForSet($singleAway));
                $fixtureDto->setHomeDouble($this->calculateAverageForSet($doubleHome));
                $fixtureDto->setAwayDouble($this->calculateAverageForSet($doubleAway));

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
                    $fixtureDto->setHighlighted($highlighting);
                }
            }

            $transports[] = $fixtureDto;
        }
        return $transports;
    }

    private function calculateAverageForSet(array $set){
        return !empty($set) ? array_sum($set) / count($set) : 0;
    }
}
