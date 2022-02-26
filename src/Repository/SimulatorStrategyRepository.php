<?php

namespace App\Repository;

use App\Entity\Simulator;
use App\Entity\SimulatorStrategy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SimulatorStrategy|null find($id, $lockMode = null, $lockVersion = null)
 * @method SimulatorStrategy|null findOneBy(array $criteria, array $orderBy = null)
 * @method SimulatorStrategy[]    findAll()
 * @method SimulatorStrategy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SimulatorStrategyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SimulatorStrategy::class);
    }

    // /**
    //  * @return SimulatorStrategy[] Returns an array of SimulatorStrategy objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SimulatorStrategy
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @param SimulatorStrategy $simulatorStrategy
     * @throws ORMException
     */
    public function persist(SimulatorStrategy $simulatorStrategy)
    {
        $this->_em->persist($simulatorStrategy);
        $this->_em->flush();
    }
}
