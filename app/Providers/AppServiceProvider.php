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
          $user_institutions = auth()->user()->user_institutions;
          $contributor_hcps = auth()->user()->contributor_hcps; // for individual contributors only
          $user_hcps = auth()->user()->user_hcps; // for non-individual contributors or users
          $permissions = auth()->user()->permissions()->get();
        } else
        {
          $name = 'Guest User';
          $user_type = 'Unknown';
          $user_institutions = [];
          $user_hcps = [];
          $contributor_hcps = [];
          $permissions = [];
        }
        $defaultCurrency = 'NGN';

        $view->with('currentUserInstitutions', $user_institutions);
        $view->with('currentUserHcps', $user_hcps);
        $view->with('currentContributorHcps', $contributor_hcps);
        $view->with('currentUserName', $name);
        $view->with('currentUserType', $user_type);
        $view->with('currentPermissions', $permissions);
        $view->with('defaultCurrency', $defaultCurrency);
        $view->with('pageTitle', $view->pageTitle ? $view->pageTitle : 'Page');
      });
    }
}
