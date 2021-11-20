<?php


namespace App\Service\UrlResponseBackup;


use App\Entity\UrlResponseBackup;
use App\Repository\UrlResponseBackupRepository;

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
     */
    public function createByData(UrlResponseBackupData $data)
    {
        $urlResponseBackup = $this->urlResponseBackupFactory->createByData($data);
        $this->urlResponseBackupRepository->persist($urlResponseBackup);
        return $urlResponseBackup;
    }

    public function findByLeague($int)
    {
        return $this->urlResponseBackupRepository->find(21);
    }
}