<?php

declare(strict_types=1);

namespace Compwright\HubspotSearchPhp;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class PaginatedIterableResultTest extends TestCase
{
    public function testValidJsonData(): void
    {
        $response = new Response(200, [], '{"results":["bar"],"total":10,"offset":0,"limit":2}');
        $result = (new PaginatedIterableResult('results'))->setResponse($response);
        $this->assertCount(1, $result);
        $this->assertEquals(10, $result->totalCount());
        $this->assertEquals(1, $result->currentPage());
        $this->assertEquals(5, $result->pageLimit());
        $this->assertTrue($result->hasMore());
        $this->assertEquals(['bar'], iterator_to_array($result));
    }

    public function testValidJsonDataEnd(): void
    {
        $response = new Response(200, [], '{"results":["bar"],"total":10,"offset":9,"limit":3}');
        $result = (new PaginatedIterableResult('results'))->setResponse($response);
        $this->assertCount(1, $result);
        $this->assertEquals(10, $result->totalCount());
        $this->assertEquals(4, $result->currentPage());
        $this->assertEquals(4, $result->pageLimit());
        $this->assertFalse($result->hasMore());
        $this->assertEquals(['bar'], iterator_to_array($result));
    }

    public function testBlankJsonData(): void
    {
        $result = (new PaginatedIterableResult('foo'))->setResponse(new Response(204));
        $this->assertCount(0, $result);
        $this->assertEquals(0, $result->totalCount());
        $this->assertEquals(1, $result->currentPage());
        $this->assertEquals(0, $result->pageLimit());
        $this->assertFalse($result->hasMore());
        $this->assertEquals([], iterator_to_array($result));
    }
}
