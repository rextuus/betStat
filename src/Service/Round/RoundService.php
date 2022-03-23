<?php

namespace App\Service\Round;

use App\Entity\Round;
use App\Repository\RoundRepository;
use Doctrine\ORM\ORMException;

class RoundService
{
    /**
     * @var RoundRepository
     */
    private $roundRepository;

    /**
     * @var RoundFactory
     */
    private $roundFactory;

    /**
     * SeasonService constructor.
     * @param RoundRepository $roundRepository
     * @param RoundFactory $roundFactory
     */
    public function __construct(RoundRepository $roundRepository, RoundFactory $roundFactory)
    {
        $this->roundRepository = $roundRepository;
        $this->roundFactory = $roundFactory;
    }

    /**
     * @param RoundData $data
     * @return Round
     * @throws ORMException
     */
    public function createByData(RoundData $data)
    {
        $round = $this->roundFactory->createByData($data);
        $this->roundRepository->persist($round);
        return $round;
    }

    /**
     * @param $id
     * @return Round|null
     */
    public function findBySportsmonkApiId($id): ?Round
    {
        $round = $this->roundRepository->findOneBy(['sportmonksApiId' => $id]);
        if (empty($round)){
            return null;
        }
        return $round;
    }

    public function updateRound(Round $round, RoundData $roundData)
    {
        $round = $this->roundFactory->mapData($roundData, $round);
        $this->roundRepository->persist($round);
        return $round;
    }
}