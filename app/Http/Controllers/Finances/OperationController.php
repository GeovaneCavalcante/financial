<?php

namespace App\Http\Controllers\Finances;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterOperationRequest;
use App\Http\Requests\ReversalOperationRequest;
use App\Services\Finances\CreateOperationService;
use App\Services\Finances\ReversalOperationService;
use Illuminate\Http\JsonResponse;
use Throwable;

class OperationController extends Controller
{
    protected $createOperationService;
    protected $reversalOperationService;

    public function __construct(
        CreateOperationService $createOperationService,
        ReversalOperationService $reversalOperationService
    ) {
        $this->createOperationService   = $createOperationService;
        $this->reversalOperationService = $reversalOperationService;
    }


    /**
     * @OA\Post(
     *     path="/api/transaction",
     *     tags={"transactions"},
     *     summary="Performs transaction between portfolios",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="value",
     *                     type="number",
     *                 ),
     *                 @OA\Property(
     *                     property="payer",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="payee",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    /**
     * @param RegisterOperationRequest $request
     *
     * @return JsonResponse
     * @throws \App\Exceptions\CustomErrors\AppException
     */
    public function register(RegisterOperationRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $transaction = $this->createOperationService->execute($data);
        } catch (Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }


        return response()->json($transaction);
    }

    /**
     * @OA\Post(
     *     path="/api/transaction/{id}/reversal",
     *     tags={"transactions"},
     *     summary="Reverse a transaction",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Transaction id to roll back",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     * )
     */
    /**
     * @param ReversalOperationRequest $request
     *
     * @return JsonResponse
     * @throws \App\Exceptions\CustomErrors\AppException
     */
    public function reversal(ReversalOperationRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $transaction = $this->reversalOperationService->execute($data);
        } catch (Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }

        return response()->json($transaction);
    }
}
