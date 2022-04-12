<?php

namespace EscolaLms\Lrs\Services;

use EscolaLms\Lrs\Dto\StatementSearchDto;
use EscolaLms\Lrs\Repositories\Contracts\StatementRepositoryContract;
use EscolaLms\Lrs\Services\Contracts\StatementServiceContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StatementService implements StatementServiceContract
{
    private StatementRepositoryContract $statementRepository;

    public function __construct(StatementRepositoryContract $statementRepository)
    {
        $this->statementRepository = $statementRepository;
    }

    public function searchAndPaginate(StatementSearchDto $criteria, int $per_page): LengthAwarePaginator
    {
        return $this->statementRepository->searchAndPaginateByCriteria($criteria->toArray(), $per_page);
    }
}
