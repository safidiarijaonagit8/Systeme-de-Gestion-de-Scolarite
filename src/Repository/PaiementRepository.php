<?php

namespace App\Repository;

use App\Entity\Paiement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Paiement>
 *
 * @method Paiement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Paiement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Paiement[]    findAll()
 * @method Paiement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaiementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paiement::class);
    }

//    /**
//     * @return Paiement[] Returns an array of Paiement objects
//     */
  public function findPaiementsEtudiant($idetudiant): array
  {
     return $this->createQueryBuilder('p')
        ->andWhere('p.idetudiant = :val')
        ->setParameter('val', $idetudiant)
        ->getQuery()
       ->getResult()
     ;
  }

  public function findPaiementEffectue($idetudiant,$semestre): ?Paiement
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
  public function updatePaiementEffectue($idpaiement,$nouveaumontant): ?int
  {
      return $this->createQueryBuilder('p')
        ->update()
         ->set('p.montantpaye', ':nouveaumontant')
         ->andWhere('p.id = :idpaiement')
        ->setParameter('nouveaumontant', $nouveaumontant)
        ->setParameter('idpaiement', $idpaiement)
        ->getQuery()
        ->execute();
     ;
  }
}
