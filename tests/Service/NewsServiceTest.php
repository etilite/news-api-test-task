<?php
namespace App\Tests\Service;

use App\Entity\News;
use App\Model\FilterParamsModel;
use App\Service\NewsService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NewsServiceTest extends KernelTestCase
{

    private ?EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testGetNewsWithBlankFilterParamsModel()
    {
        $newsRepository = $this->entityManager->getRepository(News::class);
        $newsService = new NewsService($newsRepository);
        $filterParamsModel = new FilterParamsModel();

        $pageModel = $newsService->getNews($filterParamsModel);

        $this->assertCount(5, $pageModel->getContent());
        $this->assertEquals(7, $pageModel->getTotalElements());
        $this->assertEquals(0, $pageModel->getOffset());

        $filterParamsModel->setPage(2);
        $pageModel = $newsService->getNews($filterParamsModel);
        $this->assertCount(2, $pageModel->getContent());
    }

    public function testGetNewsWithZeroResults()
    {
        $newsRepository = $this->entityManager->getRepository(News::class);
        $newsService = new NewsService($newsRepository);
        $filterParamsModel = new FilterParamsModel();
        $filterParamsModel->setTags(['there_is_no_this_tag']);

        $pageModel = $newsService->getNews($filterParamsModel);

        $this->assertCount(0, $pageModel->getContent());
        $this->assertEquals(0, $pageModel->getTotalElements());
        $this->assertEquals(0, $pageModel->getOffset());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
