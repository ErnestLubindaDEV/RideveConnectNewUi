<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\HRM;
use Illuminate\Support\ServiceProvider;
use App\Models\HRM as HrmModel;

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
    if (Auth::check()) {
        // We use the alias 'HrmModel' to avoid the naming conflict
        $employeeData = HrmModel::where('user_id', Auth::id())->first(); 
        
        // Change 'sidebarEmployee' to 'employee' to match your Blade file
        $view->with('employee', $employeeData);
    }
});
    }
    
    
}
