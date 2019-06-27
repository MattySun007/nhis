<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Institution;
use App\Models\Hcp;
use App\Models\Adoptee;

class UserSeeder extends Seeder
{

  public function run()
  {
    $params = [
      'first_name' => 'Agency',
      'last_name' => 'User',
      'email' => 'agency@nhis.com',
      'phone' => '08012223333',
      'verification_no' => 'USER-78667511',
      'password' => 'Password',
      'temp_password' => 'EXvLxqNRIyC2bcPKKhMvAqOH35rpuAGwaAg1jhjkv1p\/nzBTq96bAnKm4Iy4RmJbfatFthFyod4YiGqRQi8pPWCeDq\/\/JuUC2uqV9EEHByy+wzMCApPPjzGjdx5vHmo4',
      'colour' => 'Chocolate',
      'height' => 4,
      'marital_status_id' => 1,
      'genotype_id' => 1,
      'blood_group_id' => 1,
      'gender_id' => 1,
      'contribution_amount' => 2300,
    ];
    User::createAgencyUser(factory(User::class)->make($params)->getAttributes());

    $params = [
      'first_name' => 'Hcp',
      'last_name' => 'User',
      'email' => 'hcp@nhis.com',
      'phone' => '07054323412',
      'verification_no' => 'USER-68062329',
      'password' => 'Password',
      'temp_password' => 'EXvLxqNRIyC2bcPKKhMvAqOH35rpuAGwaAg1jhjkv1p\/nzBTq96bAnKm4Iy4RmJbfatFthFyod4YiGqRQi8pPWCeDq\/\/JuUC2uqV9EEHByy+wzMCApPPjzGjdx5vHmo4',
      'colour' => 'Chocolate',
      'height' => 4,
      'marital_status_id' => 1,
      'genotype_id' => 1,
      'blood_group_id' => 1,
      'gender_id' => 2,
      'contribution_amount' => 2300,
    ];
    User::createHcpUser(Hcp::where('id', 1)->first(), factory(User::class)->make($params)->getAttributes());

    $params = [
      'first_name' => 'Institution',
      'last_name' => 'User',
      'email' => 'institution@nhis.com',
      'phone' => '02033344455',
      'verification_no' => 'USER-22247450',
      'password' => 'Password',
      'temp_password' => 'EXvLxqNRIyC2bcPKKhMvAqOH35rpuAGwaAg1jhjkv1p\/nzBTq96bAnKm4Iy4RmJbfatFthFyod4YiGqRQi8pPWCeDq\/\/JuUC2uqV9EEHByy+wzMCApPPjzGjdx5vHmo4',
      'colour' => 'Chocolate',
      'height' => 4,
      'marital_status_id' => 1,
      'genotype_id' => 1,
      'blood_group_id' => 1,
      'gender_id' => 1,
      'contribution_amount' => 2300,
    ];
    User::createInstitutionUser(Institution::where('id', 1)->first(),factory(User::class)->make($params)->getAttributes());

    $params = [
      'first_name' => 'Individual',
      'last_name' => 'User',
      'email' => 'individual@nhis.com',
      'phone' => '08077766655',
      'verification_no' => 'USER-59710474',
      'password' => 'Password',
      'temp_password' => 'EXvLxqNRIyC2bcPKKhMvAqOH35rpuAGwaAg1jhjkv1p\/nzBTq96bAnKm4Iy4RmJbfatFthFyod4YiGqRQi8pPWCeDq\/\/JuUC2uqV9EEHByy+wzMCApPPjzGjdx5vHmo4',
      'colour' => 'Chocolate',
      'height' => 4,
      'marital_status_id' => 1,
      'genotype_id' => 1,
      'blood_group_id' => 1,
      'gender_id' => 1,
      'contribution_amount' => 2300,
    ];
    User::createIndividualContributor(factory(User::class)->make($params)->getAttributes());

    $params = [
      'first_name' => 'Adoption',
      'last_name' => 'User',
      'email' => 'adoption@nhis.com',
      'phone' => '01034254322',
      'verification_no' => 'USER-99933960',
      'password' => 'Password',
      'temp_password' => 'EXvLxqNRIyC2bcPKKhMvAqOH35rpuAGwaAg1jhjkv1p\/nzBTq96bAnKm4Iy4RmJbfatFthFyod4YiGqRQi8pPWCeDq\/\/JuUC2uqV9EEHByy+wzMCApPPjzGjdx5vHmo4',
      'colour' => 'Chocolate',
      'height' => 4,
      'marital_status_id' => 1,
      'genotype_id' => 1,
      'blood_group_id' => 1,
      'gender_id' => 2,
      'contribution_amount' => 2300,
    ];
    User::create($params);
    Adoptee::create(['user_id' => 3, 'adoptee_id' => 5]);
  }
}
