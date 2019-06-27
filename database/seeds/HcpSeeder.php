<?php

use Illuminate\Database\Seeder;
use App\Models\Hcp;

class HcpSeeder extends Seeder
{

  public function run()
  {
    $params = [
      'code' => 'HCP-028478',
      'name' => 'Chidon Clinic',
      'email' => 'chidon_hcp@nhis.com',
      'phone' => '08068535544',
      'account_number' => '1838375622',
      'cbn_code' => '070',
      'account_name' => 'mbaebie paulcollins o',
      'account_type' => '2',
      'hcp_type_id' => 1,
      'country_id' => 1,
      'state_id' => 4,
      'lga_id' => 71,
      'town_id' => 772
    ];
    $hcp = new Hcp($params);
    $hcp->save();

    $params = [
      'code' => 'HCP-706302',
      'name' => 'Gariki Hospital',
      'email' => 'gariki_hcp@nhis.com',
      'phone' => '08068535539',
      'account_number' => '3018383756',
      'cbn_code' => '011',
      'account_name' => 'mbaebie paulcollins o',
      'account_type' => '2',
      'hcp_type_id' => 1,
      'country_id' => 1,
      'state_id' => 4,
      'lga_id' => 71,
      'town_id' => 772
    ];
    $hcp = new Hcp($params);
    $hcp->save();

  }
}
