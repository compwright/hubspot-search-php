<?php

namespace Compwright\HubspotSearchPhp\Model;

use PHPUnit\Framework\TestCase;

class PropertyCollectionTest extends TestCase
{
    public function testAll(): void
    {
        /** @var array{results:array<mixed>} $response */
        $response = json_decode(
            file_get_contents(__DIR__ . '/properties_companies.json') ?: '',
            true
        );
        $properties = PropertyCollection::newFromArray($response['results']);

        $this->assertCount(196, $properties->getIterator());

        $this->assertTrue($properties->has('account_associations'));

        $accountAssociations = $properties->get('account_associations');
        $this->assertInstanceOf(Property::class, $accountAssociations);
        $this->assertEquals('account_associations', (string) $accountAssociations);

        $optionProperties = $properties->getOptionProperties();
        $this->assertInstanceOf(OptionPropertyCollection::class, $optionProperties);
        $this->assertCount(23, $optionProperties->getIterator());
    }
}
