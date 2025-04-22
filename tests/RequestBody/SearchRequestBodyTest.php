<?php

declare(strict_types=1);

namespace Compwright\HubspotSearchPhp\RequestBody;

use PHPUnit\Framework\TestCase;

class SearchRequestBodyTest extends TestCase
{
    public function testCreateJsonSerialize(): void
    {
        $expected = json_decode(
            file_get_contents(__DIR__ . '/SearchRequestBody.json') ?: '',
            true,
            512,
            JSON_THROW_ON_ERROR | JSON_BIGINT_AS_STRING
        );

        $actual = SearchRequestBody::create([
            'query' => 'foo',
            'properties' => ['foo', 'bar', 'baz']
        ])
            ->setIdFilters(['foo', 'bar'])
            ->jsonSerialize();

        $this->assertEquals($expected, $actual);
    }
}
