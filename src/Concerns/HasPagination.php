<?php

namespace Leopaulo88\Asaas\Concerns;

trait HasPagination
{
    /**
     * Check if there are more pages to fetch
     */
    public function hasMore(): bool
    {
        return $this->hasMore ?? false;
    }

    /**
     * Get the total count of items for the filters
     */
    public function getTotalCount(): int
    {
        return $this->totalCount ?? 0;
    }

    /**
     * Get the number of objects per page
     */
    public function getLimit(): int
    {
        return $this->limit ?? 0;
    }

    /**
     * Get the offset position from which the page should be loaded
     */
    public function getOffset(): int
    {
        return $this->offset ?? 0;
    }

    /**
     * Check if this is the first page
     */
    public function isFirstPage(): bool
    {
        return $this->getOffset() === 0;
    }

    /**
     * Check if this is the last page
     */
    public function isLastPage(): bool
    {
        return !$this->hasMore();
    }

    /**
     * Get the current page number (1-based)
     */
    public function getCurrentPage(): int
    {
        $limit = $this->getLimit();
        return $limit > 0 ? (int) floor($this->getOffset() / $limit) + 1 : 1;
    }

    /**
     * Get the total number of pages
     */
    public function getTotalPages(): int
    {
        $limit = $this->getLimit();
        $totalCount = $this->getTotalCount();

        if ($limit <= 0 || $totalCount <= 0) {
            return 1;
        }

        return (int) ceil($totalCount / $limit);
    }

    /**
     * Get pagination info as array
     */
    public function getPaginationInfo(): array
    {
        return [
            'hasMore' => $this->hasMore(),
            'totalCount' => $this->getTotalCount(),
            'limit' => $this->getLimit(),
            'offset' => $this->getOffset(),
            'currentPage' => $this->getCurrentPage(),
            'totalPages' => $this->getTotalPages(),
            'isFirstPage' => $this->isFirstPage(),
            'isLastPage' => $this->isLastPage(),
        ];
    }

    /**
     * Get parameters for the next page
     */
    public function getNextPageParams(): ?array
    {
        if (!$this->hasMore()) {
            return null;
        }

        return [
            'offset' => $this->getOffset() + $this->getLimit(),
            'limit' => $this->getLimit(),
        ];
    }

    /**
     * Get parameters for the previous page
     */
    public function getPreviousPageParams(): ?array
    {
        if ($this->isFirstPage()) {
            return null;
        }

        $previousOffset = max(0, $this->getOffset() - $this->getLimit());

        return [
            'offset' => $previousOffset,
            'limit' => $this->getLimit(),
        ];
    }
}
