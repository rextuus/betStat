<?php

namespace App\Repository;

use App\Entity\Club;
use App\Entity\Fixture;
use App\Entity\FixtureOdd;
use App\Entity\League;
use App\Entity\Season;
use App\Entity\Seeding;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
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
    public function persist(Fixture $fixture, bool $flush = true)
    {
        $this->_em->persist($fixture);
        if ($flush){
            $this->_em->flush();
        }
    }

    /**
     * @param Fixture $fixture
     * @throws \Doctrine\ORM\ORMException
     */
    public function flush()
    {
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

    public function findByLeagueAndSeasonAndRound(int $league, int $startYear, int $round, bool $useApiKey = true)
    {
        $qb = $this->createQueryBuilder('f');
        $qb->select('f');
        $qb->innerJoin(League::class, 'l', 'WITH', 'f.league = l.id');
        $qb->innerJoin(Season::class, 's', 'WITH', 'f.season = s.id')
            ->where($qb->expr()->eq('s.startYear', ':startYear'))
            ->andWhere($qb->expr()->eq('f.matchDay', ':round'))
            ->orderBy('f.timeStamp', 'DESC');
        if ($useApiKey){
            $qb ->andWhere($qb->expr()->eq('l.apiId', ':league'));
        }else{
            $qb ->andWhere($qb->expr()->eq('l.sportmonksApiId', ':league'));
        }
        $qb->setParameter('startYear', $startYear);
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

    public function findUndecorated(int $fromTimestamp = null)
    {
        $qb = $this->createQueryBuilder('f');
        $qb->select('f')
            ->where($qb->expr()->isNull('f.oddDecorationDate'));
        if (!is_null($fromTimestamp)){
            $qb->andWhere($qb->expr()->gt('f.timeStamp', ':from'));
            $qb->setParameter('from', $fromTimestamp);
            $qb->orderBy('f.timeStamp', 'ASC');
        }

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
     * @return Paginator|iterable|mixed[]
     */
    public function findAllSortedByFilter(array $filter, $page)
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
            if (isset($filter['from']) && $filter['from']){
                $qb->andWhere($qb->expr()->gt('f.timeStamp', ':from'));
                $qb->setParameter('from', $filter['from']);
            }
            if (isset($filter['until']) && $filter['until']){
                $qb->andWhere($qb->expr()->gt('f.timeStamp', ':until'));
                $qb->setParameter('until', $filter['until']);
            }
            if (isset($filter['round']) && $filter['round']){
                $qb->andWhere($qb->expr()->eq('f.matchDay', ':round'));
                $qb->setParameter('round', $filter['round']);
            }
            if (isset($filter['season']) && $filter['season']){
                $qb->innerJoin(Season::class, 's', 'WITH', 'f.season = s.id');
                $qb->andWhere($qb->expr()->eq('s.startYear', ':season'));
                $qb->setParameter('season', $filter['season']);
            }
            if (isset($filter['leagues']) && $filter['leagues']){
                $qb->innerJoin(League::class, 'l', 'WITH', 'f.league = l.id');
                $qb->andWhere($qb->expr()->in('l.id', ':leagues'));

                $qb->setParameter('leagues', $filter['leagues']);
            }
        }
        $qb->setFirstResult(0)->setMaxResults($filter['maxResults']);
//        $qb->setParameter('maxResults', $filter['maxResults']);


        // get entity manager
        $em = $this->getDoctrine()->getManager();

        //set page size
        $pageSize = '100';

        // load doctrine Paginator
        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($qb);

        // you can get total items
        $totalItems = count($paginator);

        // get total pages
        $pagesCount = ceil($totalItems / $pageSize);

        // now get one page's items:
        // now get one page's items:
        $paginator
            ->getQuery()
            ->setFirstResult($pageSize * ($page-1)) // set the offset
            ->setMaxResults($pageSize); // set the limit

        return ['count' => $paginator->count(), 'paginator' => $paginator];
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
