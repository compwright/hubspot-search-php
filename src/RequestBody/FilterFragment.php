<?php

declare(strict_types=1);

namespace Compwright\HubspotSearchPhp\RequestBody;

use Compwright\EasyApi\RequestBody;

class FilterFragment extends RequestBody
{
    public const PROPERTY_OBJECT_ID = 'hs_object_id';

    public const OPERATOR_EQUALS = 'EQ';

    public string $propertyName;

    /**
     * @var string|self::OPERATOR_*
     */
    public string $operator;

    public string $value;
}
