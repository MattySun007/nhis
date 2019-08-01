<?php

use Illuminate\Database\Seeder;
use App\Models\InstitutionPermission;

class InstitutionPermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    InstitutionPermission::create(['name' => 'institutions:update']);
    InstitutionPermission::create(['name' => 'institutions:read']);
    InstitutionPermission::create(['name' => 'institution-users:create']);
    InstitutionPermission::create(['name' => 'institution-users:delete']);
    InstitutionPermission::create(['name' => 'institution-users:update']);
    InstitutionPermission::create(['name' => 'institution-users:read']);
    InstitutionPermission::create(['name' => 'claims:read']);
    InstitutionPermission::create(['name' => 'adoption:create']);
    InstitutionPermission::create(['name' => 'adoption:update']);
    InstitutionPermission::create(['name' => 'adoption:delete']);
    InstitutionPermission::create(['name' => 'adoption:read']);
    
    InstitutionPermission::create(['name' => 'contributions:process']);
    InstitutionPermission::create(['name' => 'contributions:approve']);
    InstitutionPermission::create(['name' => 'contributions:pay']);
    InstitutionPermission::create(['name' => 'contributions:delete']);
    InstitutionPermission::create(['name' => 'contributions:read']);
    InstitutionPermission::create(['name' => 'permissions:manage']);
  }
}
