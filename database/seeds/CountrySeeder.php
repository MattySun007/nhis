<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
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
          'country' => 'NIGERIA',
          'abbreviation' => 'NG'
      ],
      [
          'id' => 2,
          'country' => 'GHANA',
          'abbreviation' => 'GH'
      ],
      [
          'id' => 3,
          'country' => 'KENYA',
          'abbreviation' => 'KE'
      ]
    ];

    DB::table('countries')->insert($data);
  }
}
