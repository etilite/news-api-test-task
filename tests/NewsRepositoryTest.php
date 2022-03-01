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

    public function testFindByYearMonthAndTags()
    {
        $allNews = $this->entityManager
            ->getRepository(News::class)
            ->findByYearMonthAndTags(1,'2020', '01');

        $this->assertCount(1, $allNews);
    }

    public function testFindByYearMonthAndTagsWithBlankConditions()
    {
        $allNews = $this->entityManager
            ->getRepository(News::class)
            ->findByYearMonthAndTags();

        $this->assertCount(7, $allNews);
    }

    public function testFindByTags()
    {
        $tags = ["#экономика", "#наука"];
        $allNews = $this->entityManager
            ->getRepository(News::class)
            ->findByYearMonthAndTags(1,'', '', $tags);

        $this->assertCount(3, $allNews);
        $iterator = $allNews->getIterator();
        $this->assertEquals('Новость 3',$iterator->current()->getTitle());
        $iterator->next();
        $this->assertEquals('Новость 2', $iterator->current()->getTitle());
        $iterator->next();
        $this->assertEquals('Новость 1', $iterator->current()->getTitle());
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