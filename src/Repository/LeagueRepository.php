<?php

namespace App\Repository;

use App\Entity\Club;
use App\Entity\League;
use App\Entity\Season;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method League|null find($id, $lockMode = null, $lockVersion = null)
 * @method League|null findOneBy(array $criteria, array $orderBy = null)
 * @method League[]    findAll()
 * @method League[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeagueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, League::class);
    }

    // /**
    //  * @return League[] Returns an array of League objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?League
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param League $league
     * @throws \Doctrine\ORM\ORMException
     */
    public function persist(League $league)
    {
        $this->_em->persist($league);
        $this->_em->flush();
    }

    public function getNumberOfTeamsOfLastSeason(string $leagueIdent)
    {

        $qb = $this->createQueryBuilder('l');
        $qb->select('s');
        $qb->innerJoin(Season::class, 's', 'WITH', 's.league = l.id')
            ->where($qb->expr()->eq('s.endYear', ':endYear'))
            ->orderBy('s.endYear', 'DESC')
            ->setMaxResults(1);
        $qb->setParameter('endYear', 2020);
        return $qb->getQuery()->getResult();
    }
}
