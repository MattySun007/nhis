<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserSeeder extends Seeder
{

  public function run()
  {
    $params = [
      'first_name' => 'Agency',
      'last_name' => 'User',
      'email' => 'agency@user.com',
      'phone' => '08012223333'
    ];
    $user = factory(User::class)->create($params)->all()[0];
    $user->assignAgencyUserPermissions();
  }
}
