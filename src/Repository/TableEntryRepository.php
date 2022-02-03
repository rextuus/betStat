<?php

namespace App\Repository;

use App\Entity\TableEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TableEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method TableEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method TableEntry[]    findAll()
 * @method TableEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TableEntry::class);
    }

    // /**
    //  * @return TableEntry[] Returns an array of TableEntry objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TableEntry
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @param TableEntry $tableEntry
     * @throws ORMException
     */
    public function persist(TableEntry $tableEntry)
    {
        $this->_em->persist($tableEntry);
        $this->_em->flush();
    }
}
