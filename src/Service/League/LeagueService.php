<?php


namespace App\Service\League;


use App\Entity\League;
use App\Repository\LeagueRepository;

class LeagueService
{
    /**
     * @var LeagueRepository
     */
    private $leagueRepository;

    /**
     * @var LeagueFactory
     */
    private $leagueFactory;

    public function __construct(LeagueRepository $leagueRepository, LeagueFactory $leagueFactory)
    {
        $this->leagueRepository = $leagueRepository;
        $this->leagueFactory = $leagueFactory;
    }

    /**
     * @param LeagueData $data
     * @return League
     * @throws \Doctrine\ORM\ORMException
     */
    public function createByData(LeagueData $data)
    {
        $league = $this->leagueFactory->createByData($data);
        $this->leagueRepository->persist($league);
        return $league;
    }

    /**
     * @param League $league
     * @param LeagueData $leagueData
     * @return League
     */
    public function updateLeague(League $league, LeagueData $leagueData): League
    {
        $league = $this->leagueFactory->mapData($leagueData, $league);
        $this->leagueRepository->persist($league);
        return $league;
    }

    /**
     * @param string $ident
     * @return League|null
     */
    public function getLeagueByIdent(string $ident): ?League
    {
        return $this->leagueRepository->findOneBy(['ident' => $ident]);
    }

    public function getNumberOfTeamsOfLastSeason(string $leagueIdent)
    {
        return $this->leagueRepository->getNumberOfTeamsOfLastSeason($leagueIdent)[0]->getNumberOfClubs();
    }

    /**
     * @param int $apiKey
     * @return League|null
     */
    public function findByApiKey(int $apiKey): ?League
    {
        return $this->leagueRepository->findOneBy(['apiId' => $apiKey]);
    }

    /**
     * @param int $apiKey
     * @return League|null
     */
    public function findBySportsmonksApiKey(int $apiKey): ?League
    {
        return $this->leagueRepository->findOneBy(['sportmonksApiId' => $apiKey]);
    }

    public function findByIdent(string $ident)
    {
        return $this->leagueRepository->findOneBy(['ident' => $ident]);
    }

    /**
     * @return League[]
     */
    public function getAll(): array
    {
        return $this->leagueRepository->findAll();
    }
}