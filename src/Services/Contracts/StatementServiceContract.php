<?php

namespace EscolaLms\Lrs\Services\Contracts;

use EscolaLms\Lrs\Dto\StatementSearchDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface StatementServiceContract
{
    public function searchAndPaginate(StatementSearchDto $criteria, int $per_page): LengthAwarePaginator;
}
