<?php

namespace App\Providers;

use App\Repositories\Finances\Contracts\ITransactionRepository;
use App\Repositories\Finances\Contracts\IWalletRepository;
use App\Repositories\Finances\TransactionRepository;
use App\Repositories\Finances\WalletRepository;
use App\Repositories\Users\Contracts\IUserProfileRepository;
use App\Repositories\Users\Contracts\IUserRepository;
use App\Repositories\Users\UserProfileRepository;
use App\Repositories\Users\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IUserProfileRepository::class, UserProfileRepository::class);
        $this->app->bind(ITransactionRepository::class, TransactionRepository::class);
        $this->app->bind(IWalletRepository::class, WalletRepository::class);
    }
}
