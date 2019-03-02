<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BloodGroupSeeder extends Seeder
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
          'blood_group' => 'A+'
      ],
      [
          'id' => 2,
          'blood_group' => 'AB+'
      ],
      [
          'id' => 3,
          'blood_group' => 'O+'
      ],
      [
          'id' => 4,
          'blood_group' => 'B+'
      ],
      [
          'id' => 5,
          'blood_group' => 'A-'
      ],
      [
          'id' => 6,
          'blood_group' => 'AB-'
      ],
      [
          'id' => 7,
          'blood_group' => 'O-'
      ],
      [
          'id' => 8,
          'blood_group' => 'B-'
      ]
    ];

    DB::table('blood_groups')->insert($data);
  }
}
