<?php


namespace App\Model;


use Symfony\Component\HttpFoundation\Request;

class FilterParamsModel
{
    private int $page = 1;
    private ?\DateTimeInterface $dateYearMonth = null;
    private array $tags = [];

    public static function create(Request $request): static
    {
        $page = $request->query->getInt('page', 1);
        $year = $request->query->get('year');
        $month = $request->query->get('month');
        if ($request->query->has('tags')) {
            $tags = $request->query->all()['tags'];
        }
        $tags = $tags ?? [];
        $tags = is_array($tags) ? $tags : [$tags];

        $filterParamsModel = new static();
        $filterParamsModel->setPage($page)
            ->setDateYearMonthFromParts($year, $month)
            ->setTags($tags)
        ;

        return $filterParamsModel;
    }

    public function setDateYearMonthFromParts(?string $year, ?string $month): FilterParamsModel
    {
        if ($year && empty($month) || $month && empty($year)) {
            throw new \InvalidArgumentException(sprintf('Set both year and month to filter by date: year="%s", month="%s" was provided', $year, $month));
        }
        if ($year && $month) {
            try {
                $this->dateYearMonth = new \DateTime($year . '-' . $month);
            } catch (\Exception $e) {
                throw new \InvalidArgumentException(sprintf('Unsupported format of date: year="%s", month="%s" was provided', $year, $month));
            }
        }

        return $this;
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
     * @return FilterParamsModel
     */
    public function setPage(int $page): FilterParamsModel
    {
        $this->page = $page > 0 ? $page : 1;
        return $this;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     * @return FilterParamsModel
     */
    public function setTags(array $tags): FilterParamsModel
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateYearMonth(): ?\DateTimeInterface
    {
        return $this->dateYearMonth;
    }

    /**
     * @param \DateTimeInterface|null $dateYearMonth
     * @return FilterParamsModel
     */
    public function setDateYearMonth(?\DateTimeInterface $dateYearMonth): FilterParamsModel
    {
        $this->dateYearMonth = $dateYearMonth;
        return $this;
    }
}