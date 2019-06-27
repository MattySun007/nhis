<?php

use Illuminate\Database\Seeder;
use App\Models\Institution;
use App\Models\HcpInstitution;

class InstitutionSeeder extends Seeder
{

  public function run()
  {
    $params = [
      'code' => 'INST-375441',
      'name' => 'Test Institution',
      'email' => 'test@nhis.com',
      'phone' => '08068535544',
      'address' => '4th floor, churchgate towers ,central business district abuja,appmart office',
      'rcc_number' => 'RC-432434443',
      'country_id' => 1,
      'state_id' => 4,
      'lga_id' => 72,
      'town_id' => 788
    ];
    ( new Institution($params))->save();


    $params = [
      'code' => 'INST-375556',
      'name' => 'Appmart Int. Ltd',
      'email' => 'appmart@nhis.com',
      'phone' => '08068535539',
      'address' => '4th floor, churchgate towers ,central business district abuja,appmart office',
      'rcc_number' => 'RC-43243442',
      'country_id' => 1,
      'state_id' => 4,
      'lga_id' => 72,
      'town_id' => 788
    ];

    ( new Institution($params))->save();

    HcpInstitution::create(array('institution_id' => 1, 'hcp_id' => 1));

  }
}
