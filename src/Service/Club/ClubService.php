<?php


namespace App\Service\Club;

use App\Entity\Club;
use App\Entity\League;
use App\Repository\ClubRepository;

class ClubService
{
    /**
     * @var ClubRepository
     */
    private $clubRepository;

    /**
     * @var ClubFactory
     */
    private $clubFactory;

    /**
     * ClubService constructor.
     * @param ClubRepository $clubRepository
     * @param ClubFactory $clubFactory
     */
    public function __construct(ClubRepository $clubRepository, ClubFactory $clubFactory)
    {
        $this->clubRepository = $clubRepository;
        $this->clubFactory = $clubFactory;
    }

    /**
     * @param ClubData $data
     * @return Club
     * @throws \Doctrine\ORM\ORMException
     */
    public function createByData(ClubData $data)
    {
        $club = $this->clubFactory->createByData($data);
        $this->clubRepository->persist($club);
        return $club;
    }

    /**
     * @param string $clubName
     * @return Club|null
     */
    public function findClubByName(string $clubName): ?Club
    {
        $result = $this->clubRepository->findOneBy(['name' => $clubName]);
        if (is_null($result)){
            $allClubs = $this->clubRepository->findAll();
            $maxSimilarity = 0.0;
            $maxSimilarClub = null;
            foreach ($allClubs as $candidate){
                if (similar_text($clubName, $candidate->getName()) > $maxSimilarity){
                    $maxSimilarity = similar_text($clubName, $candidate->getName());
                    $maxSimilarClub = $candidate;
                }
            }
            return $maxSimilarClub;
        }
        return $this->clubRepository->findOneBy(['name' => $clubName]);
    }

    public function findClubById(int $id)
    {
        return $this->clubRepository->findOneBy(['id' => $id]);
    }

    /**
     * @param int $apiKey
     * @return Club|null
     */
    public function findByApiKey(int $apiKey): ?Club
    {
        return $this->clubRepository->findOneBy(['apiId' => $apiKey]);
    }

    /**
     * @param Club $club
     * @param ClubData $clubData
     * @return Club
     * @throws \Doctrine\ORM\ORMException
     */
    public function updateClub(Club $club, ClubData $clubData): Club
    {
        $club = $this->clubFactory->mapData($clubData, $club);
        $this->clubRepository->persist($club);
        return $club;
    }

    public function findByLeagueAndSeason(League $league, int $seasonYear)
    {
        return $this->clubRepository->findByLeagueAndSeason($league, $seasonYear);
    }
}