<?php

namespace App\Service\MatchGame;

use App\Entity\Club;
use App\Entity\MatchDayGame;
use App\Entity\Season;
use App\Repository\MatchDayGameRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class MatchDayGameService
{
    /**
     * @var MatchDayGameRepository
     */
    private $matchDayGameRepository;

    /**
     * @var MatchDayGameFactory
     */
    private $matchDayGameFactory;

    /**
     * MatchDayGameService constructor.
     * @param MatchDayGameRepository $matchDayGameRepository
     * @param MatchDayGameFactory $matchDayGameFactory
     */
    public function __construct(MatchDayGameRepository $matchDayGameRepository, MatchDayGameFactory $matchDayGameFactory)
    {
        $this->matchDayGameRepository = $matchDayGameRepository;
        $this->matchDayGameFactory = $matchDayGameFactory;
    }

    /**
     * @param MatchDayGameData $data
     * @return \App\Entity\MatchDayGame
     * @throws \Doctrine\ORM\ORMException
     */
    public function createByData(MatchDayGameData $data)
    {
        $matchDayGame = $this->matchDayGameFactory->createByData($data);
        $this->matchDayGameRepository->persist($matchDayGame);
        return $matchDayGame;
    }

    public function getAllMatchesBelongingToMatchDayAndSeason(int $matchDay, Season $season)
    {
        return $this->matchDayGameRepository->findBy(['season' => $season, 'matchDay' => $matchDay]);
    }

    /**
     * @param Club $homeTeam
     * @param Club $awayTeam
     * @param Season $season
     * @return MatchDayGame
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getMatchByTeamsAndSeason(Club $homeTeam, Club $awayTeam, Season $season): MatchDayGame
    {
        return $this->matchDayGameRepository->findAllBySeasonAndClubs($season, $homeTeam, $awayTeam);
    }

    /**
     * @param MatchDayGameData $matchDayGameData
     *
     * @return bool
     */
    public function checkIfMatchAlreadyExists(MatchDayGameData $matchDayGameData): bool
    {
        $matches = $this->matchDayGameRepository->findBy(
            [
                'homeTeam' => $matchDayGameData->getHomeTeam(),
                'awayTeam' => $matchDayGameData->getAwayTeam(),
                'matchDay' => $matchDayGameData->getMatchDay(),
                'season' => $matchDayGameData->getSeason(),
            ]
        );
        if (count($matches) > 0){
            return true;
        }
        return false;
    }
}
