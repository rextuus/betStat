<?php

namespace App\Repository;

use App\Entity\Seeding;
use App\Entity\SimulationResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SimulationResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method SimulationResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method SimulationResult[]    findAll()
 * @method SimulationResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SimulationResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SimulationResult::class);
    }

    public function persist(SimulationResult $simulationResult)
    {
        $this->_em->persist($simulationResult);
        $this->_em->flush();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(SimulationResult $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(SimulationResult $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return SimulationResult[] Returns an array of SimulationResult objects
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
    public function findOneBySomeField($value): ?SimulationResult
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function flush()
    {
        $this->_em->flush();
    }

    public function findLatestVersionByIdent(string $getIdent): ?int
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select('s.id');
        $qb->where($qb->expr()->eq('s.ident', ':ident'))
            ->orderBy('s.id', 'DESC');
        $qb->setParameter('ident', $getIdent);
        $qb->setMaxResults(1);
        if (array_key_exists(0, $qb->getQuery()->getResult())){
            return $qb->getQuery()->getResult()[0]['id'];
        }
        return null;
    }

    public function deleteAllExpectLastOneForIdent(string $ident, int $simulationResultId)
    {

        $qb = $this->createQueryBuilder('s');
        $qb->delete(SimulationResult::class, 's');
        $qb->where($qb->expr()->eq('s.ident', ':ident'))
            ->andWhere($qb->expr()->neq('s.id', ':id'));
        $qb->setParameter('ident', $ident);
        $qb->setParameter('id', $simulationResultId);
    }

    public function findAllLimited()
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select('s, MAX(s.id) AS HIDDEN max_score');
        $qb->groupBy('s.ident');
        return $qb->getQuery()->getResult();
    }
}
