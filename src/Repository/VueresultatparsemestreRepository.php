<?php

namespace App\Repository;

use App\Entity\Vueresultatparsemestre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vueresultatparsemestre>
 *
 * @method Vueresultatparsemestre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vueresultatparsemestre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vueresultatparsemestre[]    findAll()
 * @method Vueresultatparsemestre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VueresultatparsemestreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vueresultatparsemestre::class);
    }

//    /**
//     * @return Vueresultatparsemestre[] Returns an array of Vueresultatparsemestre objects
//     */
    public function findResultatParSemestre($semestre): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.semestre = :val')
            ->setParameter('val', $semestre)
            ->orderBy('v.moyennegenerale', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Vueresultatparsemestre
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
