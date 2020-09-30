<?php

namespace App\Repository;

use App\Entity\WishList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WishList|null find($id, $lockMode = null, $lockVersion = null)
 * @method WishList|null findOneBy(array $criteria, array $orderBy = null)
 * @method WishList[]    findAll()
 * @method WishList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WishListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WishList::class);
    }

    public function getStatistics(): array
    {
        return $this->createQueryBuilder('w')
            ->select('w.title, u.username, count(p) as products')
            ->leftJoin('w.products', 'p')
            ->groupBy('w.title, u.username')
            ->leftJoin('w.user', 'u')
            ->getQuery()
            ->getArrayResult();
    }
}
