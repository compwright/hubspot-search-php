<?php

namespace Compwright\HubspotSearchPhp\Model;

use ArrayIterator;
use InvalidArgumentException;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<string, Property>
 */
class PropertyCollection implements IteratorAggregate
{
    /**
     * @param array<string, Property> $properties
     */
    protected function __construct(protected array $properties)
    {
    }

    /**
     * @return ArrayIterator<string, Property>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->properties);
    }

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->properties);
    }

    public function get(string $name): Property
    {
        if ($this->has($name)) {
            return $this->properties[$name];
        }

        throw new InvalidArgumentException('Property not found: ' . $name);
    }

    /**
     * @return OptionPropertyCollection<string, Property>
     */
    public function getOptionProperties(): OptionPropertyCollection
    {
        return new OptionPropertyCollection(
            array_filter(
                $this->properties,
                fn (Property $property) => $property->hasOptions()
            )
        );
    }

    /**
     * @param array<mixed> $array
     *
     * @return self<string, Property>
     */
    public static function newFromArray(array $array): self
    {
        /** @var Property[] $properties */
        $properties = array_map(
            // @phpstan-ignore-next-line argument.type
            fn (array $property): Property => new Property($property),
            $array
        );

        /** @var string[] $names */
        $names = array_map(
            // @phpstan-ignore-next-line argument.type
            fn (array $property) => $property['name'] ?? '',
            $array
        );

        return new self(
            array_combine($names, $properties)
        );
    }
}
