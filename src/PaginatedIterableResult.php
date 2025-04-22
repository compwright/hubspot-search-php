<?php

declare(strict_types=1);

namespace Compwright\HubspotSearchPhp;

use Compwright\EasyApi\Result\Json\IterableResult;

class PaginatedIterableResult extends IterableResult
{
    public function totalCount(): int
    {
        /** @var array<string, int> */
        $data = $this->data();
        return $data['total'] ?? 0;
    }

    public function currentPage(): int
    {
        /** @var array<string, int> */
        $data = $this->data();
        $offset = $data['offset'] ?? 0;
        $limit = $data['limit'] ?? 10;
        return 1 + (int) floor($offset / $limit);
    }

    public function pageLimit(): int
    {
        /** @var array<string, int> */
        $data = $this->data();
        $total = $data['total'] ?? 0;
        $limit = $data['limit'] ?? 10;
        return (int) ceil($total / $limit);
    }

    public function hasMore(): bool
    {
        /** @var array<string, int> */
        $data = $this->data();
        $offset = $data['offset'] ?? 0;
        $total = $data['total'] ?? 0;
        $limit = $data['limit'] ?? 10;
        return $total - $offset > $limit;
    }
}
