<?php

namespace EscolaLms\Lrs\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        $params = $this->service->launchParams($token, $id);

        $putUrl = $params['endpoint'] . '/activities/state?' . http_build_query($params['state']);

        $putRequest = Request::create($putUrl, "PUT", $params['state']);
        $putRequest->headers->set('Authorization', $token);
        $putRequest->headers->set('X-Experience-API-Version', '1.0.3');

        $params['response'] = app()->handle($putRequest);

        return $this->sendResponse($params, "cmi5 Params fetched");
    }
}
