<?php


namespace App\Service\Season;


use App\Entity\League;
use App\Entity\Season;
use App\Repository\SeasonRepository;

class SeasonService
{
    /**
     * @var SeasonRepository
     */
    private $seasonRepository;

    /**
     * @var SeasonFactory
     */
    private $seasonFactory;

    /**
     * SeasonService constructor.
     * @param SeasonRepository $seasonRepository
     * @param SeasonFactory $seasonFactory
     */
    public function __construct(SeasonRepository $seasonRepository, SeasonFactory $seasonFactory)
    {
        $this->seasonRepository = $seasonRepository;
        $this->seasonFactory = $seasonFactory;
    }

    /**
     * @param SeasonData $data
     * @return \App\Entity\Season
     * @throws \Doctrine\ORM\ORMException
     */
    public function createByData(SeasonData $data)
    {
        $season = $this->seasonFactory->createByData($data);
        $this->seasonRepository->persist($season);
        return $season;
    }

    /**
     * @param int $startYear
     * @param int $endYear
     * @param League $league
     * @return Season|null
     */
    public function findByYears(int $startYear, int $endYear, League $league): ?Season
    {
        return $this->seasonRepository->findOneBy(['startYear' => $startYear, 'endYear' => $endYear, 'league' => $league->getId()]);
    }

    public function getAllClubsBelongingToSeason(Season $season)
    {
        return $this->seasonRepository->getAllClubsBelongingToSeason($season);
    }

    public function getAllMatchesBelongingToSeasonAndMatchDay(Season $season, int $matchDay)
    {
        return $this->seasonRepository->getAllMatchesBelongingToSeasonAndMatchDay($season, $matchDay);
    }

    public function getNumberOfMatchDayBelongingToSeason(int $startYear, int $endYear, string $league)
    {
        return $this->seasonRepository->getNumberOfMatchDayBelongingToSeason($startYear, $endYear, $league);
    }

    public function getAllSeasonsBelongingToLeague(League $league)
    {
        return $this->seasonRepository->findBy(['league' => $league]);
    }

    public function getSeasonByLeagueAndStartYear(string $leagueIdent, int $startYear)
    {
        return $this->seasonRepository->findByLeagueIdentAndYear($leagueIdent, $startYear)[0];
    }

    public function findBySeasonAndRound(League $league, int $seasonYear, int $round)
    {
        return $this->seasonRepository->findBySeasonAndRound($league, $seasonYear, $round);
    }

}