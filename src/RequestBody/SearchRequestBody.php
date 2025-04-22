<?php

declare(strict_types=1);

namespace Compwright\HubspotSearchPhp\RequestBody;

use Compwright\EasyApi\RequestBody;

class SearchRequestBody extends RequestBody
{
    public string $query;

    /**
     * @var array<FilterGroupFragment|array<string, mixed>>
     */
    public array $filterGroups;

    public int $limit = 50;

    public int $after = 0;

    /**
     * @var string[]
     */
    public array $properties;

    /**
     * @param string[] $ids
     */
    public function setIdFilters(array $ids): self
    {
        $this->filterGroups = array_map(
            fn (string $id) => FilterGroupFragment::create([
                'filters' => [
                    FilterFragment::create([
                        'propertyName' => FilterFragment::PROPERTY_OBJECT_ID,
                        'operator' => FilterFragment::OPERATOR_EQUALS,
                        'value' => $id,
                    ])
                ],
            ]),
            $ids
        );

        return $this;
    }
}
