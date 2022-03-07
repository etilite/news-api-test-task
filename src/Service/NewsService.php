<?php


namespace App\Service;


use App\Entity\News;
use App\Model\FilterParamsModel;
use App\Model\NewsModel;
use App\Model\PageModel;
use App\Repository\NewsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Tools\Pagination\Paginator;

class NewsService
{
    public function __construct(private NewsRepository $newsRepository)
    {
    }

    public function getNews(FilterParamsModel $filterParamsModel): PageModel
    {
       $paginator = $this->newsRepository->findByYearMonthAndTags($filterParamsModel);

       return new PageModel(
           $this->createPageModelContent($paginator),
           count($paginator),
           $paginator->getQuery()->getFirstResult()
       );
    }

    private function createPageModelContent(Paginator $paginator): Collection
    {
        $content = new ArrayCollection();
        foreach ($paginator as $item) {
            $content->add($this->mapNewsModel($item));
        }

        return $content;
    }

    private function mapNewsModel(News $news): NewsModel
    {
        $tags = $news->getTags();
        $tagNames = $tags->map(fn ($tag) => '#' . $tag->getName());

        return new NewsModel(
            $news->getTitle(),
            $news->getText(),
            $news->getPhoto(),
            $news->getPublishedAt(),
            $tagNames
        );
    }
}