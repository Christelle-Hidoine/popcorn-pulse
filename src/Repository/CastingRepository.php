<?php

namespace App\Repository;

use App\Entity\Casting;
use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Casting>
 *
 * @method Casting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Casting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Casting[]    findAll()
 * @method Casting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CastingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Casting::class);
    }

    public function add(Casting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Casting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByMovieOrderByCreditOrderWithPerson(Movie $movie)
    {
        $em = $this->getEntityManager();
        $movieId = $movie->getId();
        
        $query = $em->createQuery("
                SELECT casting, person
                FROM App\Entity\Casting casting 
                JOIN casting.persons person
                WHERE casting.movies = $movieId
                ORDER BY casting.creditOrder ASC
            ");

        $queryNamedParam = $em->createQuery("
                SELECT casting, person
                FROM App\Entity\Casting casting 
                JOIN casting.persons person
                WHERE casting.movies = :movieobject
                ORDER BY casting.creditOrder ASC
            ");
        $queryNamedParam->setParameter('movieobject', $movie);

        $result = $query->getResult();

        return $result;
    }

    /**
    * @return Casting[] Returns an array of Casting objects
    */
    public function findByMovieOrderByCreditOrderQB(Movie $movie): array
    {
        return $this->createQueryBuilder('c') 

            ->andWhere('c.movies = :movieobject')
            ->setParameter('movieobject', $movie)
            ->orderBy('c.creditOrder', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }


}
