<?php

namespace App\Repository;

use App\Entity\AutomaticUpdateSetting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AutomaticUpdateSetting|null find($id, $lockMode = null, $lockVersion = null)
 * @method AutomaticUpdateSetting|null findOneBy(array $criteria, array $orderBy = null)
 * @method AutomaticUpdateSetting[]    findAll()
 * @method AutomaticUpdateSetting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutomaticUpdateSettingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AutomaticUpdateSetting::class);
    }

    /**
     * @param AutomaticUpdateSetting $automaticUpdateSetting
     * @throws ORMException
     */
    public function persist(AutomaticUpdateSetting $automaticUpdateSetting)
    {
        $this->_em->persist($automaticUpdateSetting);
        $this->_em->flush();
    }

    // /**
    //  * @return AutomaticUpdateSetting[] Returns an array of AutomaticUpdateSetting objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AutomaticUpdateSetting
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
