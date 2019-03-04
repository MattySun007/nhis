<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $data = [
      [
          'id' => 1,
          'name' => 'permissions:manage'
      ],
      [
          'id' => 2,
          'name' => 'institutions:write'
      ],
      [
          'id' => 3,
          'name' => 'institutions:read'
      ],
      [
          'id' => 4,
          'name' => 'institutions:delete'
      ]
    ];

    DB::table('permissions')->insert($data);
  }
}
