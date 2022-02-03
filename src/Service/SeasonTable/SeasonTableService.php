<?php


namespace App\Service\SeasonTable;


use App\Entity\League;
use App\Entity\Season;
use App\Entity\SeasonTable;
use App\Repository\SeasonRepository;
use App\Repository\SeasonTableRepository;
use Doctrine\ORM\ORMException;

class SeasonTableService
{
    /**
     * @var SeasonTableRepository
     */
    private $seasonTableRepository;

    /**
     * @var SeasonTableFactory
     */
    private $seasonTableFactory;

    /**
     * SeasonService constructor.
     * @param SeasonTableRepository $seasonTableRepository
     * @param SeasonTableFactory $seasonTableFactory
     */
    public function __construct(SeasonTableRepository $seasonTableRepository, SeasonTableFactory $seasonTableFactory)
    {
        $this->seasonTableRepository = $seasonTableRepository;
        $this->seasonTableFactory = $seasonTableFactory;
    }

    /**
     * @param SeasonTableData $data
     * @return SeasonTable
     * @throws ORMException
     */
    public function createByData(SeasonTableData $data)
    {
        $season = $this->seasonTableFactory->createByData($data);
        $this->seasonTableRepository->persist($season);
        return $season;
    }

    public function getPreviousSeason(SeasonTable $seasonTable)
    {
        return $this->seasonTableRepository->getPreviousTable($seasonTable);
    }

    public function getTableBySeasonAndMatchDay(Season $season, int $matchDay)
    {
        return $this->seasonTableRepository->findOneBy(['season' => $season, 'matchDay' => $matchDay]);
    }
}