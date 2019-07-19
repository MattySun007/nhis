<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Logout;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class onUserLogout
{
  /**
   * Create the event listener.
   *
   * @return void
   */
  public function __construct()
  {
    //
  }

  /**
   * Handle the event.
   *
   * @param  Logout  $event
   * @return void
   */
  public function handle(Logout $event)
  {
    User::find(auth()->user()->id)->update(['last_url' => URL::previous()]);
  }
}
