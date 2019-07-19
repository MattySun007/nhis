<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
  /**
   * The event listener mappings for the application.
   *
   * @var array
   */
  protected $listen = [
    Registered::class => [
      SendEmailVerificationNotification::class,
    ],
    'Illuminate\Auth\Events\Logout' => [
      'App\Listeners\Auth\onUserLogout',
    ],
    /*'Illuminate\Auth\Events\Registered' => [
      'App\Listeners\Auth\LogRegisteredUser',
    ],
    'Illuminate\Auth\Events\Attempting' => [
      'App\Listeners\Auth\LogAuthenticationAttempt',
    ],

    'Illuminate\Auth\Events\Authenticated' => [
      'App\Listeners\Auth\LogAuthenticated',
    ],

    'Illuminate\Auth\Events\Login' => [
      'App\Listeners\Auth\LogSuccessfulLogin',
    ],

    'Illuminate\Auth\Events\Failed' => [
      'App\Listeners\Auth\LogFailedLogin',
    ],

    'Illuminate\Auth\Events\Lockout' => [
      'App\Listeners\Auth\LogLockout',
    ],*/
  ];

  /**
   * Register any events for your application.
   *
   * @return void
   */
  public function boot()
  {
    parent::boot();

    //
  }
}
