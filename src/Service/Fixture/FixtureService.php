<?php


namespace App\Service\Fixture;


use App\Entity\Club;
use App\Entity\Fixture;
use App\Entity\FixtureOdd;
use App\Entity\Seeding;
use App\Repository\FixtureRepository;
use App\Service\Fixture\Transport\FixtureTransport;
use App\Service\FixtureOdd\FixtureOddService;
use App\Service\Seeding\SeedingService;
use DateTime;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class FixtureService
{
    /**
    * @var FixtureRepository
     */
    private $fixtureRepository;

    /**
     * @var FixtureFactory
     */
    private $fixtureFactory;

    /**
     * @var FixtureOddService
     */
    private $fixtureOddService;

    /**
     * FixtureService constructor.
     * @param FixtureRepository $fixtureRepository
     * @param FixtureFactory $fixtureFactory
     * @param FixtureOddService $fixtureOddService
     */
    public function __construct(FixtureRepository $fixtureRepository, FixtureFactory $fixtureFactory, FixtureOddService $fixtureOddService)
    {
        $this->fixtureRepository = $fixtureRepository;
        $this->fixtureFactory = $fixtureFactory;
        $this->fixtureOddService = $fixtureOddService;
    }


    /**
     * @param FixtureData $data
     * @return Fixture
     */
    public function createByData(FixtureData $data)
    {
        $fixture = $this->fixtureFactory->createByData($data);
        $this->fixtureRepository->persist($fixture);
        return $fixture;
    }

    /**
     * @param int $apiKey
     * @return Fixture|null
     */
    public function findByApiKey(int $apiKey): ?Fixture
    {
        return $this->fixtureRepository->findOneBy(['apiId' => $apiKey]);
    }

    /**
     * @param Fixture $fixture
     * @param FixtureData $data
     * @return Fixture
     */
    public function updateFixture(Fixture $fixture, FixtureData $data): Fixture
    {
        $fixture = $this->fixtureFactory->mapData($data, $fixture);
        $this->fixtureRepository->persist($fixture);
        return $fixture;
    }

    public function findByClubAndSeasonAndRound(Club $club, int $season, int $round)
    {
        return $this->fixtureRepository->findByClubAndSeasonAndRound($club, $season, $round);
    }

    /**
     * @param int $leagueApiId
     * @param int $season
     * @param int $round
     * @return Fixture[]
     */
    public function findByLeagueAndSeasonAndRound(int $leagueApiId, int $season, int $round): array
    {
        return $this->fixtureRepository->findByLeagueAndSeasonAndRound($leagueApiId, $season, $round);
    }

    public function findByDbId(int $dbId)
    {
        return $this->fixtureRepository->find($dbId);
    }

    public function getUnevaluatedFixtures()
    {
        return $this->fixtureRepository->findUnevaluated();
    }

    public function getUndecoratedFixtures()
    {
        return $this->fixtureRepository->findBy(['isBetDecorated' => false]);
    }

    /**
     * @return Fixture[]
     */
    public function getUndecoratedFixturesTimeStampVariant(): array
    {
        return $this->fixtureRepository->findUndecorated();
    }

    /**
     * @param int $fixturesToReturn
     * @return Fixture[]
     */
    public function getNonSeededFixtures(int $fixturesToReturn): array
    {
        $fixtureIds = $this->fixtureRepository->findNonSeededFixtures();

        $fixtures = array();

        $fixtureNr = 0;
        while ($fixtureNr < $fixturesToReturn && $fixtureNr < count($fixtureIds)){
            $fixtures[] = $this->fixtureRepository->find($fixtureIds[$fixtureNr]['id']);
            $fixtureNr++;
        }

        return $fixtures;
    }

    /**
     * @param int $leagueApiKey
     * @param int $round
     */
    public function getStatisticForLeagueAndRound(int $leagueApiKey, int $round)
    {
        $fixtures = $this->fixtureRepository->findByLeagueAndSeasonAndRound($leagueApiKey, 2021, $round);
        $currentDate = (new DateTime())->getTimestamp();
        foreach ($fixtures as $fixture){

        }
    }
}