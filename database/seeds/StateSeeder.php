<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
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
                'id' => '1',
                'name' =>  'ABIA',
                'abbreviation' =>  'AB',
                'country_id' => 1,

            ],
            [
            'id' => '2',
            'name' =>  'ADAMAWA',
            'abbreviation' =>  'ADM',
            'country_id' => 1,

            ],
            [
            'id' => '3',
            'name' =>  'AKWA IBOM',
            'abbreviation' =>  'AKW',
            'country_id' => 1,

            ],
            [
            'id' => '4',
            'name' =>  'ANAMBRA',
            'abbreviation' =>  '4',
            'country_id' => 1,

            ],
            [
            'id' => '5',
            'name' =>  'BAUCHI',
            'abbreviation' =>  '5',
            'country_id' => 1,

            ],
            [
            'id' => '6',
            'name' =>  'BAYELSA',
            'abbreviation' =>  '6',
            'country_id' => 1,

            ],
            [
            'id' => '7',
            'name' =>  'BENUE',
            'abbreviation' =>  '7',
            'country_id' => 1,

            ],
            [
            'id' => '8',
            'name' =>  'BORNO',
            'abbreviation' =>  '8',
            'country_id' => 1,

            ],
            [
            'id' => '9',
            'name' =>  'CROSS RIVER',
            'abbreviation' =>  '9',
            'country_id' => 1,

            ],
            [
            'id' => '10',
            'name' =>  'DELTA',
            'abbreviation' =>  '10',
            'country_id' => 1,

            ],
            [
            'id' => '11',
            'name' =>  'EBONYI',
            'abbreviation' =>  '11',
            'country_id' => 1,

            ],
            [
            'id' => '12',
            'name' =>  'EDO',
            'abbreviation' =>  '12',
            'country_id' => 1,

            ],
            [
            'id' => '13',
            'name' =>  'EKITI',
            'abbreviation' =>  '13',
            'country_id' => 1,

            ],
            [
            'id' => '14',
            'name' =>  'ENUGU',
            'abbreviation' =>  '14',
            'country_id' => 1,

            ],
            [
            'id' => '15',
            'name' =>  'FCT',
            'abbreviation' =>  '37',
            'country_id' => 1,

            ],
            [
            'id' => '16',
            'name' =>  'GOMBE',
            'abbreviation' =>  '15',
            'country_id' => 1,

            ],
            [
            'id' => '17',
            'name' =>  'IMO',
            'abbreviation' =>  '16',
            'country_id' => 1,

            ],
            [
            'id' => '18',
            'name' =>  'JIGAWA',
            'abbreviation' =>  '17',
            'country_id' => 1,

            ],
            [
            'id' => '19',
            'name' =>  'KADUNA',
            'abbreviation' =>  '18',
            'country_id' => 1,

            ],
            [
            'id' => '20',
            'name' =>  'KANO',
            'abbreviation' =>  '19',
            'country_id' => 1,

            ],
            [
            'id' => '21',
            'name' =>  'KATSINA',
            'abbreviation' =>  '20',
            'country_id' => 1,

            ],
            [
            'id' => '22',
            'name' =>  'KEBBI',
            'abbreviation' =>  '21',
            'country_id' => 1,

            ],
            [
            'id' => '23',
            'name' =>  'KOGI',
            'abbreviation' =>  '22',
            'country_id' => 1,

            ],
            [
            'id' => '24',
            'name' =>  'KWARA',
            'abbreviation' =>  '23',
            'country_id' => 1,

            ],
            [
            'id' => '25',
            'name' =>  'LAGOS',
            'abbreviation' =>  '24',
            'country_id' => 1,

            ],
            [
            'id' => '26',
            'name' =>  'NASARAWA',
            'abbreviation' =>  '25',
            'country_id' => 1,

            ],
            [
            'id' => '27',
            'name' =>  'NIGER',
            'abbreviation' =>  '26',
            'country_id' => 1,

            ],
            [
            'id' => '28',
            'name' =>  'OGUN',
            'abbreviation' =>  '27',
            'country_id' => 1,

            ],
            [
            'id' => '29',
            'name' =>  'ONDO',
            'abbreviation' =>  '28',
            'country_id' => 1,

            ],
            [
            'id' => '30',
            'name' =>  'OSUN',
            'abbreviation' =>  '29',
            'country_id' => 1,

            ],
            [
            'id' => '31',
            'name' =>  'OYO',
            'abbreviation' =>  '30',
            'country_id' => 1,

            ],
            [
            'id' => '32',
            'name' =>  'PLATEAU',
            'abbreviation' =>  '31',
            'country_id' => 1,

            ],
            [
            'id' => '33',
            'name' =>  'RIVERS',
            'abbreviation' =>  '32',
            'country_id' => 1,

            ],
            [
            'id' => '34',
            'name' =>  'SOKOTO',
            'abbreviation' =>  '33',
            'country_id' => 1,

            ],
            [
            'id' => '35',
            'name' =>  'TARABA',
            'abbreviation' =>  '34',
            'country_id' => 1,

            ],
            [
            'id' => '36',
            'name' =>  'YOBE',
            'abbreviation' =>  '35',
            'country_id' => 1,

            ],
            [
            'id' => '37',
            'name' =>  'ZAMFARA',
            'abbreviation' =>  '36',
            'country_id' => 1,

            ]
        ];

        DB::table('states')->insert($data);
    }
}
