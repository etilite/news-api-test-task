<?php

namespace App\Controller;

use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    #[Route('/news', name: 'news')]
    public function index(Request $request, NewsRepository $newsRepository): Response
    {
        $page = (int) $request->query->get('page', 1);
        $allNews = $newsRepository->findByYearMonthAndTags($page, '', '', ['#политика']);

        return $this->json($allNews)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
