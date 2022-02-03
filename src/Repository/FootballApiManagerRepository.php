<?php

namespace App\Repository;

use App\Entity\FootballApiManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FootballApiManager|null find($id, $lockMode = null, $lockVersion = null)
 * @method FootballApiManager|null findOneBy(array $criteria, array $orderBy = null)
 * @method FootballApiManager[]    findAll()
 * @method FootballApiManager[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FootballApiManagerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FootballApiManager::class);
    }

    // /**
    //  * @return FootballApiManager[] Returns an array of FootballApiManager objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FootballApiManager
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param FootballApiManager $apiManager
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persist(FootballApiManager $apiManager)
    {
        $this->_em->persist($apiManager);
        $this->_em->flush();
    }
}
