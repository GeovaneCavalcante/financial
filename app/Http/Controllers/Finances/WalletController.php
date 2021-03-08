<?php

namespace App\Http\Controllers\Finances;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShowWalletRequest;
use App\Services\Finances\ShowUserWalletService;
use Illuminate\Http\JsonResponse;
use Throwable;

class WalletController extends Controller
{
    protected $showUserWalletService;

    public function __construct(ShowUserWalletService $showUserWalletService)
    {
        $this->showUserWalletService = $showUserWalletService;
    }

    /**
     * @OA\Get(
     *     path="/api/wallets/{id}",
     *     tags={"wallets"},
     *     summary="Fetch a user's wallet",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Desired user id",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     * )
     */
    /**
     * @param ShowWalletRequest $request
     *
     * @return JsonResponse
     * @throws \App\Exceptions\CustomErrors\AppException
     */
    public function show(ShowWalletRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $wallet = $this->showUserWalletService->execute($data);
        } catch (Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }

        return response()->json($wallet);
    }
}
