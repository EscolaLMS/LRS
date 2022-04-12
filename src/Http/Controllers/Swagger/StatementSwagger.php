<?php

namespace EscolaLms\Lrs\Http\Controllers\Swagger;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface StatementSwagger
{
    public function statements(Request $request): JsonResponse;
}
