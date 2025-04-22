<?php

declare(strict_types=1);

namespace Compwright\HubspotSearchPhp\Api;

use Compwright\EasyApi\ApiClient;
use Compwright\EasyApi\Operation;
use Compwright\EasyApi\OperationBody\JsonBody;
use Compwright\EasyApi\Result\Json\IterableResult;
use Compwright\HubspotSearchPhp\RequestBody\SearchRequestBody;
use Compwright\HubspotSearchPhp\PaginatedIterableResult;
use Compwright\HubspotSearchPhp\Model\PropertyCollection;

class Company
{
    public function __construct(private ApiClient $client)
    {
    }

    /**
     * @see https://www.shipstation.com/docs/api/orders/add-tag/
     */
    public function search(SearchRequestBody $body): PaginatedIterableResult
    {
        $op = Operation::fromSpec('POST /crm/v3/objects/company/search')
            ->setBody(new JsonBody($body));
        return $this->client->__invoke($op, new PaginatedIterableResult('results'));
    }

    public function listProperties(): PropertyCollection
    {
        $op = Operation::fromSpec('GET /crm/v3/properties/company');
        $result = $this->client->__invoke($op, new IterableResult('results'));
        return PropertyCollection::newFromArray(iterator_to_array($result));
    }
}
