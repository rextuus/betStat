<?php


namespace App\Service\UrlResponseBackup;


use App\Entity\League;
use App\Entity\UrlResponseBackup;
use App\Repository\UrlResponseBackupRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class UrlResponseBackupService
{
    /**
     * @var UrlResponseBackupRepository
     */
    private $urlResponseBackupRepository;

    /**
     * @var UrlResponseBackupFactory
     */
    private $urlResponseBackupFactory;

    /**
     * SeasonService constructor.
     * @param UrlResponseBackupRepository $tableEntryRepository
     * @param UrlResponseBackupFactory $urlResponseBackupFactory
     */
    public function __construct(UrlResponseBackupRepository $tableEntryRepository, UrlResponseBackupFactory $urlResponseBackupFactory)
    {
        $this->urlResponseBackupRepository = $tableEntryRepository;
        $this->urlResponseBackupFactory = $urlResponseBackupFactory;
    }

    /**
     * @param UrlResponseBackupData $data
     * @return UrlResponseBackup
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createByData(UrlResponseBackupData $data)
    {
        $urlResponseBackup = $this->urlResponseBackupFactory->createByData($data);
        $this->urlResponseBackupRepository->persist($urlResponseBackup);
        return $urlResponseBackup;
    }

    public function findByLeague(League $league, int $matchDay)
    {
        return $this->urlResponseBackupRepository->findBy(['league' => $league->getId(), 'matchDay' => $matchDay]);
    }
}