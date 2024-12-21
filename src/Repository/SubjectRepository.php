<?php

namespace App\Repository;

use App\Entity\Subject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subject>
 */
class SubjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subject::class);
    }

    public function searchByName(string $query)
    {
        return $this->createQueryBuilder('s')
            ->where('LOWER(s.name) LIKE LOWER(:query)')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('s.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function createQueryBuilderBySearch(?string $query)
    {
        $qb = $this->createQueryBuilder('s')
            ->orderBy('s.name', 'ASC');
        
        if ($query) {
            $qb->where('LOWER(s.name) LIKE LOWER(:query)')
               ->setParameter('query', '%' . $query . '%');
        }
        
        return $qb;
    }

//    /**
//     * @return Subject[] Returns an array of Subject objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Subject
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
