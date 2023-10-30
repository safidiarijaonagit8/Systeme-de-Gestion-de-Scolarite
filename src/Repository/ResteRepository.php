<?php

namespace App\Repository;

use App\Entity\Reste;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reste>
 *
 * @method Reste|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reste|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reste[]    findAll()
 * @method Reste[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reste::class);
    }

//    /**
//     * @return Reste[] Returns an array of Reste objects
//     */
    public function findRestesEcolageEtudiant($idetudiant): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.idetudiant = :val')
            ->setParameter('val', $idetudiant)
           ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Reste
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function findResteExistante($idetudiant,$semestre): ?Reste
{
    return $this->createQueryBuilder('p')
       ->andWhere('p.idetudiant = :idetudiant')
      ->setParameter('idetudiant', $idetudiant)
      ->andWhere('p.semestre = :semestre')
      ->setParameter('semestre', $semestre)
      ->getQuery()
       ->getOneOrNullResult()
   ;
}
public function updateReste($idreste,$nouveaureste): ?int
{
    return $this->createQueryBuilder('p')
      ->update()
       ->set('p.montant', ':nouveaureste')
       ->andWhere('p.id = :idreste')
      ->setParameter('nouveaureste', $nouveaureste)
      ->setParameter('idreste', $idreste)
      ->getQuery()
      ->execute();
   ;
}
}
