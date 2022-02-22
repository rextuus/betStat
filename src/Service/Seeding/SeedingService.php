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
     */
    public function createByData(SeedingData $data)
    {
        $seeding = $this->findByClubAndSeasonAndLRound($data->getClub(), $data->getSeason(), $data->getRound());
        if (!is_null($seeding)){
            return $seeding;
        }

        $seeding = $this->seedingFactory->createByData($data);
        $this->seedingRepository->persist($seeding);
        return $seeding;
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

    /**
     * @param Club $club
     * @param Season $season
     * @param int $round
     *
     * @return Seeding|null
     */
    public function findByClubAndSeasonAndLRound(Club $club, Season $season, int $round): ?Seeding
    {
        return $this->seedingRepository->findOneBy(['club' => $club, 'round' => $round, 'season' => $season]);

    }

    public function findLastSeedingForClubAndSeason(Club $club, Season $season): ?Seeding
    {
        return $this->seedingRepository->findLastSeedingForClubAndSeason($club, $season)[0];
    }
}