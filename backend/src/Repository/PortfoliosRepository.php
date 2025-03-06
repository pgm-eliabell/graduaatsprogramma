<?php

namespace App\Repository;

use App\Entity\Portfolios;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Portfolios>
 */
class PortfoliosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Portfolios::class);
    }
    public function findAllPortfolios(): array
    {
        return $this->createQueryBuilder('p')
            ->getQuery()
            ->getResult();
    }
    public function findPortfolioById(int $id): ?Portfolios
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function findPortfolioByTitle(string $title): ?Portfolios
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.title = :title')
            ->setParameter('title', $title)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function findPortfolioByDescription(string $description): ?Portfolios
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.description = :description')
            ->setParameter('description', $description)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function findPortfolioByImage(string $image): ?Portfolios
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.image = :image')
            ->setParameter('image', $image)
            ->getQuery()
            ->getOneOrNullResult();
    }
    

//    /**
//     * @return Portfolios[] Returns an array of Portfolios objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Portfolios
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
