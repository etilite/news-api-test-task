<?php
namespace App\Tests;

use App\Entity\News;
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

    public function testFindByYearAndMonth()
    {
        $allNews = $this->entityManager
            ->getRepository(News::class)
            ->findByYearAndMonth('2020', '01');

        $this->assertCount(1, $allNews);
    }

    public function testFindByNullYearAndMonth()
    {
        $allNews = $this->entityManager
            ->getRepository(News::class)
            ->findByYearAndMonth();

        $this->assertCount(7, $allNews);
    }

    public function testFindByTags()
    {
        $tags = ["#экономика", "#наука"];
        $allNews = $this->entityManager
            ->getRepository(News::class)
            ->findByYearAndMonth(null, null, $tags);

        $this->assertCount(3, $allNews);
        $this->assertEquals('Новость 3', $allNews[0]->getTitle());
        $this->assertEquals('Новость 2', $allNews[1]->getTitle());
        $this->assertEquals('Новость 1', $allNews[2]->getTitle());
    }

    public function testFindHavingTwoTags()
    {
        $tagNames = ["#экономика", "#наука"];
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
        $tagNames = ["#экономика", "#наука", "#политика"];
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