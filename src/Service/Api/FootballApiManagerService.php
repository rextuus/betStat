<?php


namespace App\Service\Api;


use App\Entity\FootballApiManager;
use App\Repository\FootballApiManagerRepository;
use Exception;

class FootballApiManagerService
{
    public const IDENT_STANDARD = 'standard';

    /**
     * @var FootballApiManagerRepository
     */
    private $footballApiManagerRepository;

    /**
     * FootballApiManagerService constructor.
     * @param FootballApiManagerRepository $footballApiManagerRepository
     */
    public function __construct(FootballApiManagerRepository $footballApiManagerRepository)
    {
        $this->footballApiManagerRepository = $footballApiManagerRepository;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function initializeApiManager(){
        if ($this->footballApiManagerRepository->count([]) === 0){
            $apiManager = new FootballApiManager();
            $apiManager->setIdent(self::IDENT_STANDARD);
            $apiManager->setDailyCalls(0);
            $apiManager->setDailyLimit(95);
            $apiManager->setIsActive(true);
            $this->footballApiManagerRepository->persist($apiManager);
        }
    }

    /**
     * @param int $callsToIncrease
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function increaseCallCounter(int $callsToIncrease = 1){
        /** @var FootballApiManager $manager */
        $manager = $this->footballApiManagerRepository->findBy(['ident' => self::IDENT_STANDARD])[0];
        if (is_null($manager)){
            throw new Exception("There is no standard api manager set");
        }
        $manager->setDailyCalls($manager->getDailyCalls() + $callsToIncrease);
        $this->footballApiManagerRepository->persist($manager);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isApiCallLimitReached(): bool
    {
        /** @var FootballApiManager $manager */
        $manager = $this->footballApiManagerRepository->findBy(['ident' => self::IDENT_STANDARD])[0];
        if (is_null($manager)){
            throw new Exception("There is no standard api manager set");
        }
        return $manager->getDailyCalls() >= $manager->getDailyLimit();
    }

    public function resetApiCallCounter(){
        /** @var FootballApiManager $manager */
        $manager = $this->footballApiManagerRepository->findBy(['ident' => self::IDENT_STANDARD])[0];
        if (is_null($manager)){
            throw new Exception("There is no standard api manager set");
        }
        $manager->setDailyCalls(0);
        $this->footballApiManagerRepository->persist($manager);
    }

    public function getApiCallLimit()
    {
        /** @var FootballApiManager $manager */
        $manager = $this->footballApiManagerRepository->findBy(['ident' => self::IDENT_STANDARD])[0];
        if (is_null($manager)){
            throw new Exception("There is no standard api manager set");
        }
        return $manager->getDailyCalls();
    }
}
