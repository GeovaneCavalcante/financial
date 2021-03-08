<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Finances\OperationController;
use App\Http\Controllers\Finances\WalletController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Users\UserProfileController;


Route::group(
    ["prefix" => "/transaction"],
    function () {
        Route::post('', [OperationController::class, 'register']);
        Route::post('/{id}/reversal', [OperationController::class, 'reversal']);
    }
);

Route::group(
    ["prefix" => "/wallets/{id}"],
    function () {
        Route::get('', [WalletController::class, 'show']);
    }
);


Route::group(
    ["prefix" => "/users"],
    function () {
        Route::post('', [UserController::class, 'register']);
    }
);

Route::group(
    ["prefix" => "/users-profiles"],
    function () {
        Route::get('', [UserProfileController::class, 'list']);
    }
);


