<?php

declare(strict_types=1);

namespace Compwright\HubspotSearchPhp\Model;

class Property
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(private array $data)
    {
    }

    public function __toString(): string
    {
        // @phpstan-ignore-next-line argument.type
        return strval($this->getAttribute('name') ?? '');
    }

    /**
     * @return null|mixed
     */
    public function getAttribute(string $name)
    {
        return $this->data[$name] ?? null;
    }

    public function getOptionLabel(string $key): string
    {
        $options = $this->getAttribute('options') ?? [];

        if (!is_array($options)) {
            return '';
        }

        $map = array_combine(
            array_column($options, 'value'),
            array_column($options, 'label')
        );

        return $map[$key] ?? '';
    }

    public function hasOptions(): bool
    {
        $options = $this->getAttribute('options');
        return is_array($options) && count($options) > 0;
    }
}
