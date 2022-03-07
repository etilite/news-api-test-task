<?php

namespace App\Repository;

use App\Entity\News;
use App\Model\FilterParamsModel;
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

    public function findByYearMonthAndTags(FilterParamsModel $filterParamsModel)
    {
        $qb = $this->createQueryBuilder('n')
            ->leftJoin('n.tags', 'tag')
            ->addSelect('tag')
            ->orderBy('n.publishedAt', 'DESC')
        ;
        if ($dateYearMonth = $filterParamsModel->getDateYearMonth()) {
            $qb->andWhere('SUBSTRING(n.publishedAt, 1, 7) = :yearMonth')
                ->setParameter('yearMonth', $dateYearMonth->format('Y-m'))
            ;
        }
        $tags = $filterParamsModel->getTags();
        if (!empty($tags)) {
            $this->filterByTags($qb, $tags);
        }

        return $this->paginate($qb, $filterParamsModel->getPage());
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
