<?php


namespace Hellovoid\Gdax;

class Pagination
{
    private $before;
    private $after;
    private $limit;

    public static function create($before = null, $after = null, $limit = 100)
    {
        return new static(
            $before,
            $after,
            $limit
        );
    }

    function __construct($before = null, $after = null, $limit = 100)
    {
        $this->setEndingBefore($before);
        $this->setStartingAfter($after);
        $this->setLimit($limit);
    }
    
    public function setEndingBefore($before)
    {
        $this->before = $before;
    }

    public function setStartingAfter($after)
    {
        $this->after = $after;
    }

    public function setLimit($limit)
    {
        $this->limit = min(100, intval($limit));
    }

    public function getEndingBefore()
    {
        return $this->before;
    }

    public function getStartingAfter()
    {
        return $this->after;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function prepareParams()
    {
        return array_filter([
            'before' => $this->getEndingBefore(),
            'after' => $this->getStartingAfter(),
            'limit' => $this->getLimit(),
        ]);
    }
}