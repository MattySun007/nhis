<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenotypeSeeder extends Seeder
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
          'genotype' => 'AA'
      ],
      [
          'id' => 2,
          'genotype' => 'AC'
      ],
      [
          'id' => 3,
          'genotype' => 'AS'
      ],
      [
          'id' => 4,
          'genotype' => 'SS'
      ]
    ];

    DB::table('genotypes')->insert($data);
  }
}
