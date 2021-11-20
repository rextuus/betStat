<?php


namespace App\Service\Api\Odd;


use App\Entity\Odd;
use App\Repository\OddRepository;
use Doctrine\ORM\ORMException;

class OddService
{
    /**
     * @var OddRepository
     */
    private $oddRepository;

    /**
     * @var OddFactory
     */
    private $oddFactory;

    /**
     * SeasonService constructor.
     * @param OddRepository $oddRepository
     * @param OddFactory $oddFactory
     */
    public function __construct(OddRepository $oddRepository, OddFactory $oddFactory)
    {
        $this->oddRepository = $oddRepository;
        $this->oddFactory = $oddFactory;
    }

    /**
     * @param OddData $data
     * @return Odd
     * @throws ORMException
     */
    public function createByData(OddData $data): Odd
    {
        $odd = $this->oddFactory->createByData($data);
        $this->oddRepository->persist($odd);
        return $odd;
    }
}