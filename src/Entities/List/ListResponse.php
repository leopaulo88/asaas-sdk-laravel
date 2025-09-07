<?php

namespace Leopaulo88\Asaas\Entities\List;

use Illuminate\Support\Collection;
use Leopaulo88\Asaas\Concerns\HasPagination;
use Leopaulo88\Asaas\Entities\BaseResponse;
use Leopaulo88\Asaas\Support\EntityFactory;

class ListResponse extends BaseResponse
{
    use HasPagination;

    // Pagination attributes
    public ?bool $hasMore;

    public ?int $totalCount;

    public ?int $limit;

    public ?int $offset;

    // List data
    public array $data;

    // Object info
    public ?string $object;

    /**
     * Get the raw data array
     */
    public function getRawData(): array
    {
        return $this->data;
    }

    /**
     * Get the data automatically converted to appropriate entity instances
     * based on the object type of each item
     */
    public function getData(): Collection
    {
        return EntityFactory::createCollectionFromArray($this->data);
    }

    /**
     * Get the data mapped to specific entity instances
     *
     * @param  string  $entityClass  The entity class to map data to
     */
    public function getDataAs(string $entityClass): Collection
    {
        return EntityFactory::createCollectionAs($this->data, $entityClass);
    }

    /**
     * Check if the list is empty
     */
    public function isEmpty(): bool
    {
        return empty($this->data);
    }

    /**
     * Get the count of items in the current page
     */
    public function count(): int
    {
        return count($this->data);
    }
}
