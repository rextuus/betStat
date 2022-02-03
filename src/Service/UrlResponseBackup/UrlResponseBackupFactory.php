<?php


namespace App\Service\UrlResponseBackup;


use App\Entity\UrlResponseBackup;

class UrlResponseBackupFactory
{
    /**
     * @param UrlResponseBackupData $data
     * @return UrlResponseBackup
     */
    public function createByData(UrlResponseBackupData $data)
    {
        $urlResponseBackup = $this->createNewInstance();
        $this->mapData($data, $urlResponseBackup);
        return $urlResponseBackup;
    }

    /**
     * @param UrlResponseBackupData $data
     * @param UrlResponseBackup $urlResponseBackup
     * @return UrlResponseBackup
     */
    public function mapData(UrlResponseBackupData $data, UrlResponseBackup $urlResponseBackup)
    {
        $urlResponseBackup->setUrl($data->getUrl());
        $urlResponseBackup->setLeague($data->getLeague());
        $urlResponseBackup->setMatchDay($data->getMatchDay());
        $urlResponseBackup->setCollectionDate($data->getCollectionDate());
        $urlResponseBackup->setRawContent($data->getRawContent());
        return $urlResponseBackup;
    }

    /**
     * @return UrlResponseBackup
     */
    private function createNewInstance()
    {
        return new UrlResponseBackup();
    }
}