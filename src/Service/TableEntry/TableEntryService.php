<?php


namespace App\Service\TableEntry;


use App\Entity\SeasonTable;
use App\Entity\TableEntry;
use App\Repository\TableEntryRepository;
use Doctrine\ORM\ORMException;

class TableEntryService
{
    /**
     * @var TableEntryRepository
     */
    private $tableEntryRepository;

    /**
     * @var TableEntryFactory
     */
    private $tableEntryFactory;

    /**
     * SeasonService constructor.
     * @param TableEntryRepository $tableEntryRepository
     * @param TableEntryFactory $tableEntryFactory
     */
    public function __construct(TableEntryRepository $tableEntryRepository, TableEntryFactory $tableEntryFactory)
    {
        $this->tableEntryRepository = $tableEntryRepository;
        $this->tableEntryFactory = $tableEntryFactory;
    }

    /**
     * @param TableEntryData $data
     * @return TableEntry
     * @throws ORMException
     */
    public function createByData(TableEntryData $data)
    {
        $season = $this->tableEntryFactory->createByData($data);
        $this->tableEntryRepository->persist($season);
        return $season;
    }

    public function getSortedEntriesForTable(SeasonTable $seasonTable)
    {
        $tableEntries = $this->getEntriesForTable($seasonTable);

        usort(
            $tableEntries,
            function (TableEntry $a, TableEntry $b) {
                if ($a->getPosition() == $b->getPosition()) {
                    return 0;
                }
                return ($a->getPosition() < $b->getPosition()) ? -1 : 1;
            }
        );
        return $tableEntries;
    }

    public function getEntriesForTable(SeasonTable $seasonTable)
    {
        return $this->tableEntryRepository->findBy(['seasonTable' => $seasonTable]);
    }

    public function update(TableEntry $tableEntry, TableEntryData $updateData)
    {
        $this->tableEntryFactory->mapData($updateData, $tableEntry);
        $this->tableEntryRepository->persist($tableEntry);
    }
}