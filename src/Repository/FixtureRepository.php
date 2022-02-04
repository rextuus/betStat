<?php

namespace App\Repository;

use App\Entity\Club;
use App\Entity\Fixture;
use App\Entity\League;
use App\Entity\Season;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Fixture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fixture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fixture[]    findAll()
 * @method Fixture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FixtureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fixture::class);
    }

    /**
     * @param Fixture $fixture
     * @throws \Doctrine\ORM\ORMException
     */
    public function persist(Fixture $fixture)
    {
        $this->_em->persist($fixture);
        $this->_em->flush();
    }

    // /**
    //  * @return Fixture[] Returns an array of Fixture objects
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
    public function findOneBySomeField($value): ?Fixture
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function findByClubAndSeasonAndRound(Club $club, int $season, int $round)
    {
        $qb = $this->createQueryBuilder('f');
        $qb->select('f');
        $qb->innerJoin(Club::class, 'c', 'WITH', 'f.homeTeam = c OR f.awayTeam = c');
        $qb->innerJoin(Season::class, 's', 'WITH', 'f.season = s.id')
            ->where($qb->expr()->eq('s.startYear', ':startYear'))
            ->andWhere($qb->expr()->eq('f.matchDay', ':round'))
            ->andWhere($qb->expr()->eq('c.id', ':clubId'))
            ->orderBy('s.endYear', 'DESC')
            ->setMaxResults(1);
        $qb->setParameter('startYear', $season);
        $qb->setParameter('round', $round);
        $qb->setParameter('clubId', $club);
        return $qb->getQuery()->getResult();
    }

    public function findByLeagueAndSeasonAndRound(int $league, int $season, int $round)
    {
        $qb = $this->createQueryBuilder('f');
        $qb->select('f');
        $qb->innerJoin(League::class, 'l', 'WITH', 'f.league = l.id');
        $qb->innerJoin(Season::class, 's', 'WITH', 'f.season = s.id')
            ->where($qb->expr()->eq('s.startYear', ':startYear'))
            ->andWhere($qb->expr()->eq('f.matchDay', ':round'))
            ->andWhere($qb->expr()->eq('l.apiId', ':league'))
            ->orderBy('f.timeStamp', 'DESC');
        $qb->setParameter('startYear', $season);
        $qb->setParameter('round', $round);
        $qb->setParameter('league', $league);
        return $qb->getQuery()->getResult();
    }

    public function findUndecorated()
    {
        $qb = $this->createQueryBuilder('f');
        $qb->select('f')
            ->where($qb->expr()->isNull('f.scoreHomeHalf'))
            ->orWhere($qb->expr()->isNull('f.scoreAwayHalf'))
            ->orWhere($qb->expr()->isNull('f.scoreHomeFull'))
            ->orWhere($qb->expr()->isNull('f.scoreAwayFull'))
            ->orderBy('f.timeStamp', 'DESC');
        return $qb->getQuery()->getResult();
    }
}
