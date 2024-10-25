<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Customer;
use App\Models\Director;
use App\Models\Movie;
use App\Policies\CustomerPolicy;
use App\Policies\DirectorPolicy;
use App\Policies\MoviePolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Customer::class, CustomerPolicy::class);
        Gate::policy(Director::class, DirectorPolicy::class);
        Gate::policy(Movie::class, MoviePolicy::class);
    }
}
