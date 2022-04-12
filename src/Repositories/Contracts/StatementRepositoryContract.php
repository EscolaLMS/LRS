<?php

namespace EscolaLms\Lrs\Repositories\Contracts;

use EscolaLms\Core\Repositories\Contracts\BaseRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface StatementRepositoryContract extends BaseRepositoryContract
{
    public function searchAndPaginateByCriteria(array $criteria, ?int $perPage = 15): LengthAwarePaginator;
}
