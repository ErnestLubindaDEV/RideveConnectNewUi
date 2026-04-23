<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\HRM;
use Illuminate\Support\ServiceProvider;

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
        View::composer('partials.sidebar', function ($view) {
        // Fetch the HRM record for the logged-in user
        $employee = HRM::where('user_id', Auth::id())->first();
        
        // Pass it to the sidebar view
        $view->with('sidebarEmployee', $employee);
    });
    }
}
