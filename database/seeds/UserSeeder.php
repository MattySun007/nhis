<?php

use Illuminate\Database\Seeder;
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

    User::createAgencyUser(factory(User::class)->make($params)->getAttributes());
  }
}
