<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    public const ITEMS_PER_PAGE = 5;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    public function findByYearMonthAndTags(int $page = 1, string $year = '', string $month = '', array $tags = [])
    {
        $qb = $this->createQueryBuilder('n')
            ->leftJoin('n.tags', 'tag')
            ->addSelect('tag')
            ->orderBy('n.publishedAt', 'DESC')
        ;
        if ($year && $month) {
            $qb->andWhere('SUBSTRING(n.publishedAt, 1, 4) = :year')
                ->andWhere('SUBSTRING(n.publishedAt, 6, 2) = :month')
                ->setParameter('year', $year)
                ->setParameter('month', $month)
            ;
        }
        if (!empty($tags)) {
            $this->filterByTags($qb, $tags);
        }

        return $this->paginate($qb, $page);
    }

    public function findHavingTags(array $tags): ?array
    {
        $qb = $this->createQueryBuilder('n');
        $this->filterByTags($qb, $tags);

        return $qb->getQuery()->getResult();
    }

    private function filterByTags(QueryBuilder $queryBuilder, array $tagNames): void
    {
        for ($i = 0; $i < count($tagNames); $i++) {
            $tagAlias = 'tag' . $i;
            $queryBuilder->innerJoin('n.tags', $tagAlias, 'WITH', "$tagAlias.name = :$tagAlias")
                ->setParameter($tagAlias, $tagNames[$i]);
        }
    }

    private function paginate(QueryBuilder $queryBuilder, int $page): Paginator
    {
        $offset = self::ITEMS_PER_PAGE * ($page - 1);
        $query = $queryBuilder->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults(self::ITEMS_PER_PAGE);

        return new Paginator($query);
    }
}
