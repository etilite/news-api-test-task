<?php
namespace App\Tests;

use App\Entity\News;
use App\Model\FilterParamsModel;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NewsRepositoryTest extends KernelTestCase
{

    private ?EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testFindAll()
    {
        $allNews = $this->entityManager
            ->getRepository(News::class)
            ->findAll();

        $this->assertCount(7, $allNews);
    }

    public function testFindByYearMonthAndTags()
    {
        $filterParamsModel = new FilterParamsModel();
        $filterParamsModel->setDateYearMonthFromParts('2021', '5')
            ->setTags(['экономика'])
        ;
        $allNews = $this->entityManager
            ->getRepository(News::class)
            ->findByYearMonthAndTags($filterParamsModel);

        $this->assertCount(2, $allNews);
        $iterator = $allNews->getIterator();
        $this->assertEquals('Новость 3', $iterator->current()->getTitle());
    }

    public function testFindByYearMonth()
    {
        $filterParamsModel = new FilterParamsModel();
        $filterParamsModel->setDateYearMonthFromParts('2020', '1');
        $allNews = $this->entityManager
            ->getRepository(News::class)
            ->findByYearMonthAndTags($filterParamsModel);

        $this->assertCount(1, $allNews);
    }

    public function testFindByYearMonthAndTagsWithBlankConditions()
    {
        $filterParamsModel = new FilterParamsModel();
        $allNews = $this->entityManager
            ->getRepository(News::class)
            ->findByYearMonthAndTags($filterParamsModel);

        $this->assertCount(7, $allNews);
    }

    public function testFindByTags()
    {
        $filterParamsModel = new FilterParamsModel();
        $filterParamsModel->setTags(["экономика", "наука"]);
        $allNews = $this->entityManager
            ->getRepository(News::class)
            ->findByYearMonthAndTags($filterParamsModel);

        //$allNews = iterator_to_array($allNews->getIterator());
        $allNews = $allNews->getIterator()->getArrayCopy();
        $this->assertCount(3, $allNews);
        $this->assertEquals('Новость 3', $allNews[0]->getTitle());
        $this->assertEquals('Новость 2', $allNews[1]->getTitle());
        $this->assertEquals('Новость 1', $allNews[2]->getTitle());
    }

    public function testFindHavingTwoTags()
    {
        $tagNames = ["экономика", "наука"];
        $allNews = $this->entityManager
            ->getRepository(News::class)
            ->findHavingTags($tagNames);

        $this->assertCount(3, $allNews);
        $this->assertEquals('Новость 1', $allNews[0]->getTitle());
        $this->assertEquals('Новость 2', $allNews[1]->getTitle());
        $this->assertEquals('Новость 3', $allNews[2]->getTitle());


    }

    public function testFindHavingThreeTags()
    {
        $tagNames = ["экономика", "наука", "политика"];
        $allNews = $this->entityManager
            ->getRepository(News::class)
            ->findHavingTags($tagNames);
        $this->assertCount(1, $allNews);
        $this->assertEquals('Новость 1', $allNews[0]->getTitle());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}