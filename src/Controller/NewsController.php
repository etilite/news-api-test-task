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
        $allNews = $newsRepository->findAll();

        return $this->json($allNews)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
