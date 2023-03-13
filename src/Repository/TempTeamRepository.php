<?php

namespace App\Repository;

use App\Entity\TempTeam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TempTeam>
 *
 * @method TempTeam|null find($id, $lockMode = null, $lockVersion = null)
 * @method TempTeam|null findOneBy(array $criteria, array $orderBy = null)
 * @method TempTeam[]    findAll()
 * @method TempTeam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TempTeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TempTeam::class);
    }

    public function save(TempTeam $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TempTeam $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return TempTeam[] Returns an array of TempTeam objects
    */
   public function findByNotId($id): array
   {
       return $this->createQueryBuilder('t')
           ->andWhere('t.id != :val')
           ->setParameter('val', $id)
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?TempTeam
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
