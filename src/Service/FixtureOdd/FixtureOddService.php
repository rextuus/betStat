<?php


namespace App\Service\FixtureOdd;


use App\Entity\Fixture;
use App\Entity\FixtureOdd;
use App\Repository\FixtureOddRepository;
use App\Service\Fixture\FixtureData;

class FixtureOddService
{
    /**
     * @var FixtureOddRepository
     */
    private $fixtureOddRepository;

    /**
     * @var FixtureOddFactory
     */
    private $fixtureOddFactory;

    /**
     * FixtureOddService constructor.
     * @param FixtureOddRepository $fixtureOddRepository
     * @param FixtureOddFactory $fixtureOddFactory
     */
    public function __construct(FixtureOddRepository $fixtureOddRepository, FixtureOddFactory $fixtureOddFactory)
    {
        $this->fixtureOddRepository = $fixtureOddRepository;
        $this->fixtureOddFactory = $fixtureOddFactory;
    }

    /**
     * @param FixtureOddData $data
     * @return FixtureOdd
     * @throws \Doctrine\ORM\ORMException
     */
    public function createByData(FixtureOddData $data)
    {
        $fixtureOdd = $this->fixtureOddFactory->createByData($data);
        $this->fixtureOddRepository->persist($fixtureOdd);
        return $fixtureOdd;
    }

    /**
     * @param Fixture $fixture
     * @return FixtureOdd[]
     */
    public function findByFixture(Fixture $fixture): array
    {
        return $this->fixtureOddRepository->findBy(['fixture' => $fixture]);
    }

    /**
     * @param FixtureOdd $fixtureOdd
     * @param FixtureData $data
     * @return FixtureOdd
     * @throws \Doctrine\ORM\ORMException
     */
    public function updateClub(FixtureOdd $fixtureOdd, FixtureOddData $data): FixtureOdd
    {
        $fixtureOdd = $this->fixtureOddFactory->mapData($data, $fixtureOdd);
        $this->fixtureOddRepository->persist($fixtureOdd);
        return $fixtureOdd;
    }
}