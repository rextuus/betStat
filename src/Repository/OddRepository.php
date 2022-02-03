<?php

namespace App\Repository;

use App\Entity\Odd;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Odd|null find($id, $lockMode = null, $lockVersion = null)
 * @method Odd|null findOneBy(array $criteria, array $orderBy = null)
 * @method Odd[]    findAll()
 * @method Odd[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OddRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Odd::class);
    }

    /**
     * @param Odd $odd
     * @throws ORMException
     */
    public function persist(Odd $odd)
    {
        $this->_em->persist($odd);
        $this->_em->flush();
    }

    // /**
    //  * @return Odd[] Returns an array of Odd objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Odd
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
