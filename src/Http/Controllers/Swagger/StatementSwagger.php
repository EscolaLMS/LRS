<?php

namespace EscolaLms\Lrs\Http\Controllers\Swagger;

use EscolaLms\Lrs\Http\Requests\StatementListRequest;
use Illuminate\Http\JsonResponse;

interface StatementSwagger
{
    public function statements(StatementListRequest $request): JsonResponse;
}
