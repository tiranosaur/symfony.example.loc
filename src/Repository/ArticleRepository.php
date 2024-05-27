<?php

namespace App\Repository;

use App\Entity\Article;
use App\Util\ArticleUtility;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Article::class);
    }

    public function create(Article $article): void {
        $entityManager = $this->getEntityManager();
        $entityManager->beginTransaction();

        try {
            $entityManager->persist($article);
            $entityManager->flush();
            $entityManager->commit();
        } catch (\Exception $exception) {
            $entityManager->rollback();
            throw new HttpException(Response::HTTP_BAD_REQUEST, "Article creation failed - {$exception->getMessage()}");
        }
    }

    public function getAll(int $limit = 10, int $offset = 0): array {
        return $this->createQueryBuilder('a')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function getById(int $id): Article {
        return $this->createQueryBuilder('a')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }

    public function update(Article $article): void {
        $entityManager = $this->getEntityManager();
        $entityManager->beginTransaction();

        try {
            $old = $this->getById($article->getId());
            ArticleUtility::map($article, $old);

            $entityManager->flush();
            $entityManager->commit();
        } catch (\Exception $exception) {
            $entityManager->rollback();
            throw new HttpException(Response::HTTP_BAD_REQUEST, "Article update failed - {$exception->getMessage()}");
        }
    }

    public function delete(int $id): void {
        $entityManager = $this->getEntityManager();
        $entityManager->beginTransaction();

        try {
            $article = $this->getById($id);
            $entityManager->remove($article);

            $entityManager->flush();
            $entityManager->commit();
        } catch (\Exception $exception) {
            $entityManager->rollback();
            throw new HttpException(Response::HTTP_BAD_REQUEST, "Article deletion failed - {$exception->getMessage()}");
        }
    }


//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}
