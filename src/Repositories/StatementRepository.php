<?php

namespace EscolaLms\Lrs\Repositories;

use EscolaLms\Core\Repositories\BaseRepository;
use EscolaLms\Lrs\Models\Statement;
use EscolaLms\Lrs\Repositories\Contracts\StatementRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StatementRepository extends BaseRepository implements StatementRepositoryContract
{
    public function getFieldsSearchable(): array
    {
        return [];
    }

    public function model(): string
    {
        return Statement::class;
    }

    public function searchAndPaginateByCriteria(array $criteria, ?int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        $query->whereLike('data', 'initialized');
        dd();
        $query = $this->applyCriteria($query, $criteria);

        return $query
            ->paginate($perPage);
    }
}
