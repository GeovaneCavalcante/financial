<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Services\Users\CreateUserService;
use App\Services\Finances\CreateWalletService;
use Illuminate\Http\JsonResponse;
use Throwable;

class UserController extends Controller
{
    protected $userService;
    protected $createWalletService;

    public function __construct(
        CreateUserService $createUserService,
        CreateWalletService $createWalletService
    ) {
        $this->userService         = $createUserService;
        $this->createWalletService = $createWalletService;
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"users"},
     *     summary="User registration in the application",
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
     *                     property="full_name",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="cpf",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="profile_id",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    /**
     * @param RegisterUserRequest $request
     *
     * @return JsonResponse
     * @throws \App\Exceptions\CustomErrors\AppException
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $user = $this->userService->execute($data);

            $dtoWallet = array("user_id" => $user->id);

            $this->createWalletService->execute($dtoWallet);
        } catch (Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }

        return response()->json($user);
    }
}
