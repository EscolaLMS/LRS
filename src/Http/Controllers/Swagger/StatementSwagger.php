<?php

namespace EscolaLms\Lrs\Http\Controllers\Swagger;

use EscolaLms\Lrs\Http\Requests\StatementListRequest;
use Illuminate\Http\JsonResponse;

interface StatementSwagger
{
    /**
     * @OA\Get(
     *     path="/api/admin/cmi5/statements",
     *     summary="Lists available statements",
     *     tags={"cmi5"},
     *     security={
     *         {"passport": {}},
     *     },
     *      @OA\Parameter(
     *          name="order_by",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              enum={"created_at", "id"}
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="order",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              enum={"ASC", "DESC"}
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          description="Pagination Page Number",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="number",
     *               default=1,
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="per_page",
     *          description="Pagination Per Page",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="number",
     *              default=15,
     *          ),
     *      ),
     *     @OA\Parameter(
     *         description="Verb display name or id",
     *         in="query",
     *         name="verb",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Account name or homePage",
     *         in="query",
     *         name="account",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Registration uuid",
     *         in="query",
     *         name="registration",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Object type",
     *         in="query",
     *         name="object",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Version",
     *         in="query",
     *         name="version",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Date from",
     *         in="query",
     *         name="date_from",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Date to",
     *         in="query",
     *         name="date_to",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of available statements",
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="Endpoint requires authentication",
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="User doesn't have required access rights",
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="Server-side error",
     *      ),
     * )
     *
     * @param StatementListRequest $request
     * @return JsonResponse
     */
    public function statements(StatementListRequest $request): JsonResponse;
}
