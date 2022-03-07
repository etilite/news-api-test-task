<?php

namespace App\Tests\Model;

use App\Model\FilterParamsModel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class FilterParamsModelTest extends TestCase
{
    public function testSetPage()
    {
        $filterParamsModel = new FilterParamsModel();
        $filterParamsModel->setPage(2);

        $this->assertEquals(2, $filterParamsModel->getPage());
    }

    public function testSetPageNegativeValue()
    {
        $filterParamsModel = new FilterParamsModel();
        $filterParamsModel->setPage(-2);

        $this->assertEquals(1, $filterParamsModel->getPage());
    }

    public function testSetDateYearMonthFromParts()
    {
        $year = '2022';
        $month = '1';
        $filterParamsModel = new FilterParamsModel();
        $filterParamsModel->setDateYearMonthFromParts($year, $month);

        $this->assertEquals('2022-01', $filterParamsModel->getDateYearMonth()->format('Y-m'));
    }

    public function testSetDateYearMonthFromNullParts()
    {
        $year = null;
        $month = null;
        $filterParamsModel = new FilterParamsModel();
        $filterParamsModel->setDateYearMonthFromParts($year, $month);

        $this->assertNull($filterParamsModel->getDateYearMonth());
    }

    /**
     * @dataProvider provideYearAndMonthForException
     * @param $year
     * @param $month
     */
    public function testSetDateYearMonthFromPartsWithOnePartOnly($year, $month)
    {
        $filterParamsModel = new FilterParamsModel();

        $this->expectException(\InvalidArgumentException::class);
        $filterParamsModel->setDateYearMonthFromParts($year, $month);
    }


    public function testCreate()
    {
        $request = Request::create(
            '/news',
            'GET',
            ['page' => '2', 'year' => '2022', 'month' => '1', 'tags' => ['политика', 'наука']]
        );
        $filterParamsModel = FilterParamsModel::create($request);

        $this->assertEquals(2, $filterParamsModel->getPage());
        $this->assertEquals('2022-01', $filterParamsModel->getDateYearMonth()->format('Y-m'));
        $this->assertCount(2, $filterParamsModel->getTags());
        $this->assertEquals('наука', $filterParamsModel->getTags()[1]);
    }

    public function testCreateNoQueryArguments()
    {
        $request = Request::create(
            '/news',
            'GET',
        );
        $filterParamsModel = FilterParamsModel::create($request);

        $this->assertEquals(1, $filterParamsModel->getPage());
        $this->assertNull($filterParamsModel->getDateYearMonth());
        $this->assertIsArray($filterParamsModel->getTags());
        $this->assertCount(0, $filterParamsModel->getTags());
    }

    /**
     * @return \Generator
     */
    public function provideYearAndMonthForException(): iterable
    {
        yield ['year' => '2022', 'month' => ''];
        yield ['year' => '', 'month' => '1'];
        yield ['year' => 'abc', 'month' => '1'];
        yield ['year' => '2022', 'month' => '13'];
        yield ['year' => '1', 'month' => '13'];
    }

}
