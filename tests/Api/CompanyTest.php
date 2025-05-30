<?php

declare(strict_types=1);

namespace Compwright\HubspotSearchPhp\Api;

use Compwright\HubspotSearchPhp\ApiTestTrait;
use Compwright\HubspotSearchPhp\RequestBody\SearchRequestBody;
use PHPUnit\Framework\TestCase;

class CompanyTest extends TestCase
{
    use ApiTestTrait;

    public function testSearch(): void
    {
        $expectedRequest = $this->getExpectedRequest(__DIR__ . '/Company/SearchRequest.txt');
        $expectedResponse = $this->getExpectedResponse(200, __DIR__ . '/Company/SearchResponse.json');
        $this->rootHandler->append($expectedResponse);
        $result = $this->api->company->search(SearchRequestBody::create([
            'query' => 'bar',
        ]));
        $this->assertRequestMatchesExpected($expectedRequest, $this->rootHandler->getLastRequest());
        $this->assertSame($expectedResponse, $result->getResponse());
    }
}
