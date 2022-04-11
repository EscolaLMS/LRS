<?php

namespace EscolaLms\Lrs\Http\Controllers\Swagger;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface LrsSwagger
{
    /**
     * @OA\Post(
     *     path="/api/cmi5/fetch",
     *     summary="cmi5 token fetch",
     *     tags={"cmi5"},
     *     @OA\Parameter(
     *          name="token",
     *          required=true,
     *          in="query"
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          ),
     *      ),
     * )
     */
    public function fetch(Request $request): JsonResponse;

    /**
     * @OA\Get(
     *      path="/api/cmi5/courses/{id}",
     *      summary="Get Course cmi5 launch params",
     *      tags={"cmi5"},
     *      description="Course cmi5 launch params",
     *      security={
     *         {"passport": {}},
     *      },
     *      @OA\Parameter(
     *          name="id",
     *          description="id of Course",
     *          @OA\Schema(
     *             type="integer",
     *         ),
     *          required=true,
     *          in="path"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *          ),
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="endpoint",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="fetch",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="actor",
     *                  type="object"
     *              ),
     *              @OA\Property(
     *                  property="registration",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="activityId",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="url",
     *                  type="string"
     *              ),
     *          )
     *      )
     * )
     */
    public function launchParams(Request $request, int $id): JsonResponse;
}
