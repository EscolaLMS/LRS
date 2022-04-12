<?php

namespace EscolaLms\Lrs\Repositories\Criteria;

use EscolaLms\Core\Repositories\Criteria\Criterion;
use Illuminate\Database\Eloquent\Builder;

class JsonCriteria extends Criterion
{
    public function apply(Builder $query): Builder
    {
        return $query->whereJsonContains($this->key, $this->value);
    }
}
