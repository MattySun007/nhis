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
          'name' => 'institutions:create'
      ],
      [
          'id' => 3,
          'name' => 'institutions:update'
      ],
      [
          'id' => 4,
          'name' => 'institutions:read'
      ],
      [
          'id' => 5,
          'name' => 'institutions:delete'
      ],
      [
          'id' => 6,
          'name' => 'hcps:create'
      ],
      [
          'id' => 7,
          'name' => 'hcps:read'
      ],
      [
          'id' => 8,
          'name' => 'hcp-user:create'
      ]
    ];

    DB::table('permissions')->insert($data);
  }
}
