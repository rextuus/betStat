<?php

namespace App\Repository;

use App\Entity\FixtureOdd;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FixtureOdd|null find($id, $lockMode = null, $lockVersion = null)
 * @method FixtureOdd|null findOneBy(array $criteria, array $orderBy = null)
 * @method FixtureOdd[]    findAll()
 * @method FixtureOdd[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FixtureOddRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FixtureOdd::class);
    }

    /**
     * @param FixtureOdd $fixtureOdd
     * @throws \Doctrine\ORM\ORMException
     */
    public function persist(FixtureOdd $fixtureOdd)
    {
        $this->_em->persist($fixtureOdd);
        $this->_em->flush();
    }

    /**
     * @param FixtureOdd[] $fixtureOdds
     */
    public function persistMultiple(array $fixtureOdds)
    {
        foreach ($fixtureOdds as $fixtureOdd){
            $this->_em->persist($fixtureOdd);
        }
        $this->_em->flush();
        $this->_em->clear();
    }

    // /**
    //  * @return FixtureOdd[] Returns an array of FixtureOdd objects
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
    public function findOneBySomeField($value): ?FixtureOdd
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
