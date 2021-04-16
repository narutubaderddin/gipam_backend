<?php


namespace App\Model;

use JMS\Serializer\Annotation as JMS;

class ApiResponse
{
    /**
     * @JMS\Type(name="int")
     */
    protected $page;

    /**
     * @JMS\Type(name="int")
     */
    protected $size;

    /**
     * @JMS\Type(name="int")
     */
    protected $filteredQuantity;

    /**
     * @JMS\Type(name="int")
     */
    protected $totalQuantity;

    /**
     * @JMS\Type(name="array")
     */
    protected $results;

    /**
     * Response constructor.
     * @param int $page
     * @param int $size
     * @param int $filteredQuantity
     * @param int $totalQuantity
     * @param array $results
     */
    public function __construct(int $page, int $size, int $filteredQuantity, int $totalQuantity, array $results)
    {
        $this->page = $page;
        $this->size = $size;
        $this->filteredQuantity = $filteredQuantity;
        $this->totalQuantity = $totalQuantity;
        $this->results = $results;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     * @return $this
     */
    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     * @return $this
     */
    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return int
     */
    public function getFilteredQuantity(): int
    {
        return $this->filteredQuantity;
    }

    /**
     * @param int $filteredQuantity
     * @return $this
     */
    public function setFilteredQuantity(int $filteredQuantity): self
    {
        $this->filteredQuantity = $filteredQuantity;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalQuantity(): int
    {
        return $this->totalQuantity;
    }

    /**
     * @param int $totalQuantity
     * @return $this
     */
    public function setTotalQuantity(int $totalQuantity): self
    {
        $this->totalQuantity = $totalQuantity;

        return $this;
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @param array $results
     * @return $this
     */
    public function setResults(array $results): self
    {
        $this->results = $results;

        return $this;
    }

}