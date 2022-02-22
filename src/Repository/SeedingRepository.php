<?php

namespace App\Repository;

use App\Entity\Club;
use App\Entity\League;
use App\Entity\Season;
use App\Entity\Seeding;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Seeding|null find($id, $lockMode = null, $lockVersion = null)
 * @method Seeding|null findOneBy(array $criteria, array $orderBy = null)
 * @method Seeding[]    findAll()
 * @method Seeding[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeedingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Seeding::class);
    }

    // /**
    //  * @return Seeding[] Returns an array of Seeding objects
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
    public function findOneBySomeField($value): ?Seeding
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function persist(Seeding $season)
    {
        $this->_em->persist($season);
        $this->_em->flush();
    }

    public function findLastSeedingForClubAndSeason(Club $club, Season $season): array
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select('s');
        $qb->innerJoin(Club::class, 'c', 'WITH', 's.club = c.id');
        $qb->innerJoin(Season::class, 'se', 'WITH', 's.season = se.id')
            ->where($qb->expr()->eq('s.startYear', ':startYear'))
            ->andWhere($qb->expr()->eq('c.id', ':clubId'))
            ->orderBy('s.round', 'DESC')
            ->setMaxResults(1);
        $qb->setParameter('startYear', $season);
        $qb->setParameter('clubId', $club);
        return $qb->getQuery()->getResult();
    }
}
