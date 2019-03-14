<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HcpTypeSeeder extends Seeder
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
          'hcp_type' => 'Secondary'
      ],
      [
          'id' => 2,
          'hcp_type' => 'Primary'
      ]
    ];

    DB::table('hcp_types')->insert($data);
  }
}
