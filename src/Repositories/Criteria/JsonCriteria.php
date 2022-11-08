<?php

namespace EscolaLms\Lrs\Repositories\Criteria;

use EscolaLms\Core\Repositories\Criteria\Criterion;
use Illuminate\Database\Eloquent\Builder;

class JsonCriteria extends Criterion
{
    private bool $and;

    public function __construct(?string $key = null, $value = null, $operator = null, $and = true)
    {
        parent::__construct($key, $value, $operator);
        $this->and = $and;
    }

    public function apply(Builder $query): Builder
    {
        return $this->and
            ? $query->where($this->key, $this->value)
            : $query->orWhereJsonContains($this->key, $this->value);
    }
}
