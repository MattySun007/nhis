<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      Schema::defaultStringLength(191);

      view()->composer('*', function ($view) {
        if (auth()->user())
        {
          $name = auth()->user()->full_name;
          $user_type = auth()->user()->user_type;
          $permissions = auth()->user()->permissions()->get();
        } else
        {
          $name = 'Guest User';
          $permissions = [];
        }

        $view->with('currentUserName', $name);
        $view->with('currentUserType', $user_type);
        $view->with('currentPermissions', $permissions);
        $view->with('pageTitle', $view->pageTitle ? $view->pageTitle : 'Page');
      });
    }
}
