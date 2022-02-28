<?php

namespace App\Controller;

use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    #[Route('/news', name: 'news')]
    public function index(NewsRepository $newsRepository): Response
    {
        //$allNews = $newsRepository->findAll();
        //$allNews = $newsRepository->findByYearAndMonth();
        $tagNames = ["#экономика", "#наука"];
        $allNews = $newsRepository->findHavingTags($tagNames);

        return $this->json($allNews)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
