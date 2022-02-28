<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    public function findByYearAndMonth(string $year = null, string $month = null, array $tags = null)
    {
        $qb = $this->createQueryBuilder('n')
            ->orderBy('n.publishedAt', 'DESC')
        ;
        if ($year && $month) {
            $qb->andWhere('SUBSTRING(n.publishedAt, 1, 4) = :year')
                ->andWhere('SUBSTRING(n.publishedAt, 6, 2) = :month')
                ->setParameter('year', $year)
                ->setParameter('month', $month)
            ;
        }
        if (empty($tags)) {
            $qb->leftJoin('n.tags', 'tag')
                ->addSelect('tag');
        } else {
            $this->joinTagsToQueryBuilder($qb, $tags);
        }

        return $qb->getQuery()->getResult();
    }

    public function findHavingTags(array $tags): ?array
    {
        $qb = $this->createQueryBuilder('n');
        $this->joinTagsToQueryBuilder($qb, $tags);

        return $qb->getQuery()->getResult();
    }

    private function joinTagsToQueryBuilder(QueryBuilder $queryBuilder, array $tagNames): void
    {
        for ($i = 0; $i < count($tagNames); $i++) {
            $tagAlias = 'tag' . $i;
            $queryBuilder->innerJoin('n.tags', $tagAlias, 'WITH', "$tagAlias.name = :$tagAlias")
                ->setParameter($tagAlias, $tagNames[$i]);
        }
    }

    // /**
    //  * @return News[] Returns an array of News objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?News
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
