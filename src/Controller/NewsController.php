<?php

namespace App\Controller;

use App\Model\FilterParamsModel;
use App\Service\NewsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    #[Route('/news', name: 'news')]
    public function index(Request $request, NewsService $newsService): Response
    {
        $newsPage = $newsService->getNews(FilterParamsModel::create($request));

        return $this->json($newsPage)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
