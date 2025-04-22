<?php

namespace Compwright\HubspotSearchPhp\Model;

use PHPUnit\Framework\TestCase;

class PropertyTest extends TestCase
{
    public function testGetAttribute(): void
    {
        $p = new Property(['foo' => 'bar']);
        $this->assertEquals('bar', $p->getAttribute('foo'));
    }

    public function testStringify(): void
    {
        $p = new Property(['name' => 'foo']);
        $this->assertEquals('foo', (string) $p);
    }

    public function testHasNoOptions(): void
    {
        $p = new Property([]);
        $this->assertFalse($p->hasOptions());
    }

    public function testHasOptions(): void
    {
        /** @var array<string, mixed> $response */
        $response = json_decode(
            file_get_contents(__DIR__ . '/property_company_account_associations.json') ?: '',
            true
        );
        $p = new Property($response);
        $this->assertTrue($p->hasOptions());
        $this->assertEquals('ACCA', $p->getOptionLabel('1'));
    }
}
