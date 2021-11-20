<?php

namespace App\Repository;

use App\Entity\Club;
use App\Entity\League;
use App\Entity\MatchDayGame;
use App\Entity\Season;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Season|null find($id, $lockMode = null, $lockVersion = null)
 * @method Season|null findOneBy(array $criteria, array $orderBy = null)
 * @method Season[]    findAll()
 * @method Season[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Season::class);
    }

    // /**
    //  * @return Season[] Returns an array of Season objects
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
    public function findOneBySomeField($value): ?Season
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
     * @param Season $season
     * @throws ORMException
     */
    public function persist(Season $season)
    {
        $this->_em->persist($season);
        $this->_em->flush();
    }

    public function getAllClubsBelongingToSeason(Season $season)
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select('c');
        $qb->innerJoin(MatchDayGame::class, 'm', 'WITH', 'm.season = s.id')
            ->innerJoin(Club::class, 'c', 'WITH', 'm.homeTeam = c.id')
            ->where($qb->expr()->eq('m.season', ':season'));
        $qb->setParameter('season', $season)
            ->distinct();
        return $qb->getQuery()->getResult();

    }

    public function getAllMatchesBelongingToSeasonAndMatchDay(Season $season, int $matchDay)
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select('m');
        $qb->innerJoin(MatchDayGame::class, 'm', 'WITH', 'm.season = s.id')
            ->where($qb->expr()->eq('s.startYear', ':startYear'))
            ->andWhere($qb->expr()->eq('s.endYear', ':endYear'))
            ->andWhere($qb->expr()->eq('s.league', ':league'))
            ->andWhere($qb->expr()->eq('m.matchDay', ':matchDay'));
        $qb->setParameter('startYear', $startYear)
            ->setParameter('endYear', $endYear)
            ->setParameter('league', $league)
            ->setParameter('matchDay', $matchDay)
            ->distinct();
        return $qb->getQuery()->getResult();
    }

    public function getNumberOfMatchDayBelongingToSeason(int $startYear, int $endYear, string $league)
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select($qb->expr()->countDistinct('m.matchDay'));
        $qb->innerJoin(MatchDayGame::class, 'm', 'WITH', 'm.season = s.id')
            ->where($qb->expr()->eq('s.startYear', ':startYear'))
            ->andWhere($qb->expr()->eq('s.endYear', ':endYear'))
            ->andWhere($qb->expr()->eq('s.league', ':league'));
        $qb->setParameter('startYear', $startYear)
            ->setParameter('endYear', $endYear)
            ->setParameter('league', $league)
            ->distinct();
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param string $leagueIdent
     * @param int $seasonYear
     * @return mixed
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findByLeagueIdentAndYear(string $leagueIdent, int $seasonYear)
    {
        $qb = $this->createQueryBuilder('s');
        $qb->innerJoin(League::class, 'l', 'WITH', 's.league = l')
            ->where($qb->expr()->eq('l.ident', ':ident'))
            ->andWhere($qb->expr()->eq('s.startYear', ':startYear'));
        $qb->setParameter('ident', $leagueIdent)
            ->setParameter('startYear', $seasonYear)
            ->distinct();
        return $qb->getQuery()->getResult();
    }
}
