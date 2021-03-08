<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Services\Users\ShowUserProfilesService;
use Illuminate\Http\JsonResponse;
use Throwable;

class UserProfileController extends Controller
{
    protected $showUserProfilesService;

    public function __construct(ShowUserProfilesService $showUserProfilesService)
    {
        $this->showUserProfilesService = $showUserProfilesService;
    }

    /**
     * @OA\Get(
     *     path="/api/users-profiles",
     *     tags={"users"},
     *     summary="Search user profiles",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     * )
     */
    /**
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        try {
            $userProfiles = $this->showUserProfilesService->execute();
        } catch (Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }
        return response()->json($userProfiles);
    }
}
