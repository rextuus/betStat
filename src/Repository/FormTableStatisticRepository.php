<?php

namespace App\Repository;

use App\Entity\Club;
use App\Entity\FormTableStatistic;
use App\Entity\League;
use App\Entity\Season;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormTableStatistic|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormTableStatistic|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormTableStatistic[]    findAll()
 * @method FormTableStatistic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormTableStatisticRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormTableStatistic::class);
    }

    // /**
    //  * @return FormTableStatistic[] Returns an array of FormTableStatistic objects
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
    public function findOneBySomeField($value): ?FormTableStatistic
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @param FormTableStatistic $formTableStatistic
     * @throws \Doctrine\ORM\ORMException
     */
    public function persist(FormTableStatistic $formTableStatistic)
    {
        $this->_em->persist($formTableStatistic);
        $this->_em->flush();
    }

    public function getAllMatchesBelongingToSeason(int $startYear, int $endYear)
    {

    }

    public function getAllStatisticsForLength(int $seriesLength, string $leagueIdent, int $start, int $end)
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select('count(s.id)');
        $qb->innerJoin(Season::class, 'season', 'WITH', 's.season = season.id')
            ->innerJoin(League::class, 'l', 'WITH', 'season.league = l.id')
            ->where($qb->expr()->eq('s.winSeries', ':winSeries'))
            ->andWhere($qb->expr()->gt('season.startYear', ':startYear'))
            ->andWhere($qb->expr()->eq('l.ident', ':leagueIdent'))
            ->andWhere($qb->expr()->eq('s.startMatchDay', ':start'))
            ->andWhere($qb->expr()->eq('s.endMatchDay', ':end'));
        $qb->setParameter('winSeries', $seriesLength)
            ->setParameter('startYear', 2000)
            ->setParameter('leagueIdent', $leagueIdent)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->groupBy('s.endedWith');
        return $qb->getQuery()->getResult();
    }
}
