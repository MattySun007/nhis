<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TitleSeeder extends Seeder
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
          'title' => 'Alhaji'
      ],
      [
          'id' => 2,
          'title' => 'Alhaja'
      ],
      [
          'id' => 3,
          'title' => 'Barrister'
      ],
      [
          'id' => 4,
          'title' => 'Chief'
      ],
      [
          'id' => 5,
          'title' => 'Dr'
      ],
      [
          'id' => 6,
          'title' => 'Engr'
      ],
      [
          'id' => 7,
          'title' => 'Miss'
      ],
      [
          'id' => 8,
          'title' => 'Mr'
      ],
      [
          'id' => 9,
          'title' => 'Mrs'
      ],
      [
          'id' => 10,
          'title' => 'Pastor'
      ],
      [
          'id' => 11,
          'title' => 'Rev'
      ]
    ];

    DB::table('titles')->insert($data);
  }
}
