<?php


namespace App\Service\Seeding;


use App\Entity\Club;
use App\Entity\Fixture;
use App\Entity\League;
use App\Entity\Season;
use App\Entity\Seeding;
use App\Repository\SeedingRepository;
use Doctrine\ORM\ORMException;

class SeedingService
{
    /**
     * @var SeedingRepository
     */
    private $seedingRepository;

    /**
     * @var SeedingFactory
     */
    private $seedingFactory;

    /**
     * SeedingService constructor.
     * @param SeedingRepository $seedingRepository
     * @param SeedingFactory $seedingFactory
     */
    public function __construct(SeedingRepository $seedingRepository, SeedingFactory $seedingFactory)
    {
        $this->seedingRepository = $seedingRepository;
        $this->seedingFactory = $seedingFactory;
    }

    /**
     * @param SeedingData $data
     * @return Seeding
     * @throws ORMException
     */
    public function createByData(SeedingData $data)
    {
        $season = $this->seedingFactory->createByData($data);
        $this->seedingRepository->persist($season);
        return $season;
    }

    /**
     * @param Seeding $seeding
     * @param SeedingData $updateData
     */
    public function update(Seeding $seeding, SeedingData $updateData)
    {
        $this->seedingFactory->mapData($updateData, $seeding);
        $this->seedingRepository->persist($seeding);
    }

    public function findByClubAndSeasonAndLRound(Club $club, Season $season, int $round)
    {
        return $this->seedingRepository->findOneBy(['club' => $club, 'round' => $round, 'season' => $season]);

    }
}