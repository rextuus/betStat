<?php


namespace App\Service\Setting;


use App\Entity\FootballApiManager;
use App\Repository\FootballApiManagerRepository;
use DateTime;
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
            $apiManager->setResetDate(new DateTime());
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
        $manager->setResetDate(new DateTime());
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

    /**
     * @return ApiManagerDto[]
     */
    public function getManagerDtos(): array
    {
        $accounts = $this->footballApiManagerRepository->findAll();
        $dtos = array();
        foreach ($accounts as $account){
            $dto = new ApiManagerDto();
            $dto->setIdent($account->getIdent());
            $dto->setLimit($account->getDailyLimit());
            $dto->setCurrent($account->getDailyCalls());
            $dto->setActive($account->getIsActive());
            $dto->setResetDate($account->getResetDate()->format('Y-m-d H:i:s'));
            $currentDateTime = new DateTime();
            $dto->setLastResetColor('#20A734FF');
            if ($account->getResetDate() < $currentDateTime){
                $dto->setLastResetColor('#184d00');
            }
            $dtos[] = $dto;
        }
        return $dtos;
    }
}
