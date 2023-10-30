<?php

namespace App\Repository;

use App\Entity\VFicheEtudiant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<VFicheEtudiant>
 *
 * @method VFicheEtudiant|null find($id, $lockMode = null, $lockVersion = null)
 * @method VFicheEtudiant|null findOneBy(array $criteria, array $orderBy = null)
 * @method VFicheEtudiant[]    findAll()
 * @method VFicheEtudiant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VFicheEtudiantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VFicheEtudiant::class);
    }

//    /**
//     * @return VFicheEtudiant[] Returns an array of VFicheEtudiant objects
//     */
//   public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

  public function findInfoEtudiant($idetudiant): ?VFicheEtudiant
 {
     return $this->createQueryBuilder('v')
      ->andWhere('v.id = :val')
        ->setParameter('val', $idetudiant)
          ->getQuery()
         ->getOneOrNullResult()
       ;
}
public function findAllEtudiantQuery(): Query
{
    return $this->createQueryBuilder('v')
    ->orderBy('v.nom', 'ASC')
    ->getQuery();
}

public function verifLoginEtudiant($idetudiant,$datedenaissance): ?VFicheEtudiant
{
    return $this->createQueryBuilder('v')
     ->andWhere('v.id = :val')
      ->setParameter('val', $idetudiant)
       ->andWhere('v.datedenaissance = :daty')
       ->setParameter('daty', $datedenaissance)
         ->getQuery()
        ->getOneOrNullResult()
      ;
}

}
