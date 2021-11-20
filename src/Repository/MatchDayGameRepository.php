<?php

namespace App\Repository;

use App\Entity\Club;
use App\Entity\MatchDayGame;
use App\Entity\Season;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MatchDayGame|null find($id, $lockMode = null, $lockVersion = null)
 * @method MatchDayGame|null findOneBy(array $criteria, array $orderBy = null)
 * @method MatchDayGame[]    findAll()
 * @method MatchDayGame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchDayGameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatchDayGame::class);
    }


    /**
     * @param Season $season
     * @param Club $homeClub
     * @param Club $awayClub
     * @return mixed
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findAllBySeasonAndClubs(Season $season, Club $homeClub, Club $awayClub)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->select('m')
            ->where($qb->expr()->eq('m.season', ':season'))
            ->andWhere($qb->expr()->eq('m.homeTeam', ':homeTeam'))
            ->andWhere($qb->expr()->eq('m.awayTeam', ':awayTeam'))
            ->setMaxResults(1);
        $qb->setParameter('season', $season->getId());
        $qb->setParameter('homeTeam', $homeClub->getId());
        $qb->setParameter('awayTeam', $awayClub->getId());
        return $qb->getQuery()->getSingleResult();
    }


    /*
    public function findOneBySomeField($value): ?MatchDayGame
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param MatchDayGame $matchDayGame
     * @throws \Doctrine\ORM\ORMException
     */
    public function persist(MatchDayGame $matchDayGame)
    {
        $this->_em->persist($matchDayGame);
        $this->_em->flush();
    }
}
