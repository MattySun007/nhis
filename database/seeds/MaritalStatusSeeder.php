<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaritalStatusSeeder extends Seeder
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
          'marital_status' => 'divorced'
      ],
      [
          'id' => 2,
          'marital_status' => 'married'
      ],
      [
          'id' => 3,
          'marital_status' => 'single'
      ],
      [
          'id' => 4,
          'marital_status' => 'widowed'
      ]
    ];

    DB::table('marital_statuses')->insert($data);
  }
}
