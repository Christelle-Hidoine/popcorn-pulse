<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 *
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function add(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Method to retrieve data from Movie Entity according to search->get()
     *
     * @param [string] $search
     * @return Movie[] Returns an array of Movie objects
     */
    public function findByMovieTitle($search): array
    {
        return $this->createQueryBuilder('movie')
            ->andWhere('movie.title LIKE :title')
            ->setParameter('title', "%$search%")
            ->orderBy('movie.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Method to retrieve data from Movie Entity according to search->get()
     *
     * @param [string] $search
     * @return Movie[] Returns an array of Movie objects
     */
    public function findByGenre($search): array
    {
        return $this->createQueryBuilder('movie')
            ->andWhere('genre.name LIKE :name')
            ->setParameter('name', "%$search%")
            ->orderBy('movie.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Method to retrieve rating from Movie Entity
     * 
     * @param [int] $id
     * @return Movie[] Returns an array of Movie objects
     */
    public function findRatingByMovie($id): array
    {
        return $this->createQueryBuilder('movie')
            ->select("COUNT(movie.rating)")
            ->andWhere('movie.id LIKE :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Movie[] Returns an array of Movie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Movie
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
