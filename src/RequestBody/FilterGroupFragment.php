<?php

declare(strict_types=1);

namespace Compwright\HubspotSearchPhp\RequestBody;

use Compwright\EasyApi\RequestBody;

class FilterGroupFragment extends RequestBody
{
    /**
     * @var array<FilterFragment|array<string, string>>
     */
    public array $filters {
        set(array $filters) {
            $this->filters = FilterFragment::createList($filters);
        }
    }
}
