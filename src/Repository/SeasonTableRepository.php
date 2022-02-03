<?php

namespace App\Repository;

use App\Entity\SeasonTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SeasonTable|null find($id, $lockMode = null, $lockVersion = null)
 * @method SeasonTable|null findOneBy(array $criteria, array $orderBy = null)
 * @method SeasonTable[]    findAll()
 * @method SeasonTable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeasonTableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SeasonTable::class);
    }

    // /**
    //  * @return SeasonTable[] Returns an array of SeasonTable objects
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
    public function findOneBySomeField($value): ?SeasonTable
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
     * @param SeasonTable $seasonTable
     * @throws ORMException
     */
    public function persist(SeasonTable $seasonTable)
    {
        $this->_em->persist($seasonTable);
        $this->_em->flush();
    }

    public function getPreviousTable(SeasonTable $seasonTable)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.season = :season')
            ->andWhere('t.matchDay = :endYear')
            ->setParameter('season', $seasonTable->getSeason())
            ->setParameter('endYear', $seasonTable->getMatchDay() - 1)
            ->getQuery()
            ->getResult();
    }
}
