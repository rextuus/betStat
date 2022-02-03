<?php


namespace App\Service\Fixture;


use App\Entity\Club;
use App\Entity\Fixture;
use App\Repository\FixtureRepository;

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
     * FixtureService constructor.
     * @param FixtureRepository $fixtureRepository
     * @param FixtureFactory $fixtureFactory
     */
    public function __construct(FixtureRepository $fixtureRepository, FixtureFactory $fixtureFactory)
    {
        $this->fixtureRepository = $fixtureRepository;
        $this->fixtureFactory = $fixtureFactory;
    }

    /**
     * @param FixtureData $data
     * @return Fixture
     * @throws \Doctrine\ORM\ORMException
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
     * @throws \Doctrine\ORM\ORMException
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
}