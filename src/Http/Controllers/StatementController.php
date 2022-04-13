<?php

namespace EscolaLms\Lrs\Http\Controllers;

use EscolaLms\Core\Http\Controllers\EscolaLmsBaseController;
use EscolaLms\Lrs\Dto\StatementSearchDto;
use EscolaLms\Lrs\Http\Controllers\Swagger\StatementSwagger;
use EscolaLms\Lrs\Http\Requests\StatementListRequest;
use EscolaLms\Lrs\Http\Resources\StatementResource;
use EscolaLms\Lrs\Services\Contracts\StatementServiceContract;
use Illuminate\Http\JsonResponse;

class StatementController extends EscolaLmsBaseController implements StatementSwagger
{
    private StatementServiceContract $statementService;

    public function __construct(StatementServiceContract $statementService)
    {
        $this->statementService = $statementService;
    }

    public function statements(StatementListRequest $request): JsonResponse
    {
        $results = $this->statementService->searchAndPaginate(
            StatementSearchDto::instantiateFromRequest($request),
            $request->get('per_page') ?? 15
        );

        return $this->sendResponseForResource(StatementResource::make($results), 'Statements retrieved successfully');
    }
}
