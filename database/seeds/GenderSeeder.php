<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenderSeeder extends Seeder
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
          'gender' => 'MALE'
      ],
      [
          'id' => 2,
          'gender' => 'FEMALE'
      ]
    ];

    DB::table('genders')->insert($data);
  }
}
