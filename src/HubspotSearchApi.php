<?php

declare(strict_types=1);

namespace Compwright\HubspotSearchPhp;

use Compwright\EasyApi\ApiClient;

class HubspotSearchApi
{
    public readonly Api\Company $company;
    public readonly Api\Contact $contact;

    public function __construct(ApiClient $client)
    {
        $this->company = new Api\Company($client);
        $this->contact = new Api\Contact($client);
    }
}
