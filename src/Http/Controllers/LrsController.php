<?php

namespace EscolaLms\Lrs\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use EscolaLms\Lrs\Services\Contracts\LrsServiceContract;
use EscolaLms\Lrs\Http\Controllers\Swagger\LrsSwagger;
use EscolaLms\Core\Http\Controllers\EscolaLmsBaseController;



class LrsController extends EscolaLmsBaseController implements LrsSwagger
{

    private LrsServiceContract $service;

    public function __construct(LrsServiceContract $service)
    {
        $this->service = $service;
    }

    public function fetch(Request $request): JsonResponse
    {
        return response()->json([
            'auth-token' => $request->input("token"),
        ]);
    }

    public function lunchParams(Request $request, $id): JsonResponse
    {
        $token = $request->header('Authorization');
        $params = $this->service->launchParams($id, $token);
        return $this->sendResponse($params, "cmi5 Params fetched");
    }
}
