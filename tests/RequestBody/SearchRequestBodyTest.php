<?php

declare(strict_types=1);

namespace Compwright\HubspotSearchPhp\RequestBody;

use PHPUnit\Framework\TestCase;

class SearchRequestBodyTest extends TestCase
{
    public function testCreateJsonSerialize(): void
    {
        $json = file_get_contents(__DIR__ . '/SearchRequestBody.json') ?: '';

        $actual = SearchRequestBody::create([
            'query' => 'foo',
            'properties' => ['foo', 'bar', 'baz']
        ])
            ->setIdFilters(['foo', 'bar']);

        $this->assertSame($json, json_encode($actual));
    }
}
