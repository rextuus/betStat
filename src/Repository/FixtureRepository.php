<?php

namespace App\Repository;

use App\Entity\Club;
use App\Entity\Fixture;
use App\Entity\FixtureOdd;
use App\Entity\League;
use App\Entity\Season;
use App\Entity\Seeding;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Util\Filter;

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

    public function findUnevaluated()
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

    public function findUndecorated()
    {
        $qb = $this->createQueryBuilder('f');
        $qb->select('f')
            ->where($qb->expr()->isNull('f.oddDecorationDate'));
        return $qb->getQuery()->getResult();
    }

    /**
     * Creates a list of all fixtures dont having a seeding
     *
     * @return array
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function findNonSeededFixtures()
    {
        $queryString = "
                SELECT fixture.id, fixture.api_id, fixture.time_stamp
                FROM fixture
                WHERE fixture.id NOT IN 
                (
                    SELECT fixture.id
                    FROM fixture
                    INNER JOIN club ON (fixture.home_team_id = club.id OR fixture.away_team_id = club.id)
                    INNER JOIN seeding ON (club.id = seeding.club_id)
                )
                ORDER BY fixture.time_stamp DESC
            ";

        $stmt = $this->getEntityManager()->getConnection()->executeQuery($queryString, []);
        return $stmt->fetchAllAssociative();
    }

    /**
     * @return Fixture[]
     */
    public function findAllSortedByFilter(array $filter): array
    {
        $qb = $this->createQueryBuilder('f');
        $qb->select('f');
        if (count($filter)){
            if (isset($filter['oddDecorated']) && $filter['oddDecorated']){
                $qb->where($qb->expr()->isNotNull('f.oddDecorationDate'));
            }
            if (isset($filter['played']) && $filter['played']){
                $qb->andWhere($qb->expr()->eq('f.played', ':played'));
                $qb->setParameter('played', true);
            }
        }
        $qb->orderBy('f.timeStamp', 'DESC');
        return $qb->getQuery()->getResult();
    }

    public function getFixturesWithoutResult()
    {
        $qb = $this->createQueryBuilder('f');
        $qb->select('f.matchDay, l.apiId, s.startYear');
        $qb->innerJoin(League::class, 'l', 'WITH', 'f.league = l.id');
        $qb->innerJoin(Season::class, 's', 'WITH', 'f.season = s.id')
            ->where($qb->expr()->isNull('f.scoreHomeFull'))
            ->orWhere($qb->expr()->isNull('f.scoreAwayFull'))
            ->andWhere($qb->expr()->eq('f.played', ':played'))
            ->andWhere($qb->expr()->lt('f.resultDecorationDate', ':currentDate'))
            ->groupBy('f.matchDay')
            ->addGroupBy('l.ident')
            ->addGroupBy('s.startYear');
        $qb->setParameter('played', false);
        $qb->setParameter('currentDate', new \DateTime('-5 days'));
        return $qb->getQuery()->getResult();
    }

}
