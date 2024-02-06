<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
    /** @var Collection<User> */
    public function findAll()
    {
        $queryBuilder = $this->createQueryBuilder('u');
        return $queryBuilder
            ->andWhere('u.deletedAt is null')
            ->getQuery()
            ->getResult();
    }

    public function findOneOrNull(int $id)
    {
        $qb = $this->createQueryBuilder('user')
            ->leftJoin('user.books', 'b')
            ->andWhere('user.id = :userId')
            ->andWhere('user.deletedAt is null')
            ->setParameter('userId', $id)
            ->getQuery();
        // Retrieve the state from somewhere
        return $qb->getOneOrNullResult();
    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
