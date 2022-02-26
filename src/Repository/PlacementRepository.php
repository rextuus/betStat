<?php

namespace App\Repository;

use App\Entity\Placement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Placement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Placement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Placement[]    findAll()
 * @method Placement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlacementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Placement::class);
    }

    /**
     * @param Placement $placement
     * @throws ORMException
     */
    public function persist(Placement $placement)
    {
        $this->_em->persist($placement);
        $this->_em->flush();
    }


    // /**
    //  * @return Placement[] Returns an array of Placement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Placement
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
