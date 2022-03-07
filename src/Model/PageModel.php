<?php

namespace App\Model;


use Doctrine\Common\Collections\Collection;


class PageModel
{
    public function __construct(private Collection $content, private int $totalElements, private int $offset)
    {
    }

    /**
     * @param Collection $content
     * @return PageModel
     */
    public function setContent(Collection $content): PageModel
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @param int $totalElements
     * @return PageModel
     */
    public function setTotalElements(int $totalElements): PageModel
    {
        $this->totalElements = $totalElements;
        return $this;
    }

    /**
     * @param int $offset
     * @return PageModel
     */
    public function setOffset(int $offset): PageModel
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getContent(): Collection
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getTotalElements(): int
    {
        return $this->totalElements;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }
}