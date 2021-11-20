<?php

namespace App\Repository;

use App\Entity\UrlResponseBackup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UrlResponseBackup|null find($id, $lockMode = null, $lockVersion = null)
 * @method UrlResponseBackup|null findOneBy(array $criteria, array $orderBy = null)
 * @method UrlResponseBackup[]    findAll()
 * @method UrlResponseBackup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UrlResponseBackupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UrlResponseBackup::class);
    }

    // /**
    //  * @return UrlResponseBackup[] Returns an array of UrlResponseBackup objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UrlResponseBackup
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param UrlResponseBackup $urlResponseBackup
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function persist(UrlResponseBackup $urlResponseBackup)
    {
        $this->_em->persist($urlResponseBackup);
        $this->_em->flush();
    }
}
