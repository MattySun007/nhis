<?php

use Illuminate\Database\Seeder;
use App\Models\AgencyPermission;

class AgencyPermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {    
    AgencyPermission::create(['name' => 'institutions:create']);
    AgencyPermission::create(['name' => 'institutions:update']);
    AgencyPermission::create(['name' => 'institutions:read']);
    AgencyPermission::create(['name' => 'institutions:delete']);
    AgencyPermission::create(['name' => 'hcps:create']);
    AgencyPermission::create(['name' => 'hcps:update']);
    AgencyPermission::create(['name' => 'hcps:delete']);
    AgencyPermission::create(['name' => 'hcps:read']);
    AgencyPermission::create(['name' => 'hcp-users:delete']);
    AgencyPermission::create(['name' => 'hcp-users:create']);
    AgencyPermission::create(['name' => 'hcp-users:update']);
    AgencyPermission::create(['name' => 'hcp-users:read']);
    AgencyPermission::create(['name' => 'institution-users:create']);
    AgencyPermission::create(['name' => 'institution-users:delete']);
    AgencyPermission::create(['name' => 'institution-users:update']);
    AgencyPermission::create(['name' => 'institution-users:read']);
    AgencyPermission::create(['name' => 'individual-contributors:create']);
    AgencyPermission::create(['name' => 'individual-contributors:update']);
    AgencyPermission::create(['name' => 'individual-contributors:delete']);
    AgencyPermission::create(['name' => 'individual-contributors:read']);
    AgencyPermission::create(['name' => 'institution-hcp:read']);
    AgencyPermission::create(['name' => 'institution-hcp:create']);
    AgencyPermission::create(['name' => 'institution-hcp:delete']);
    AgencyPermission::create(['name' => 'claims:read']);
    AgencyPermission::create(['name' => 'claims:manage']);
    AgencyPermission::create(['name' => 'agency-users:create']);
    AgencyPermission::create(['name' => 'agency-users:update']);
    AgencyPermission::create(['name' => 'agency-users:read']);
    AgencyPermission::create(['name' => 'agency-users:delete']);
    AgencyPermission::create(['name' => 'adoption:create']);
    AgencyPermission::create(['name' => 'adoption:update']);
    AgencyPermission::create(['name' => 'adoption:delete']);
    AgencyPermission::create(['name' => 'adoption:read']);
    AgencyPermission::create(['name' => 'contributions:create']);
    AgencyPermission::create(['name' => 'contributions:update']);
    AgencyPermission::create(['name' => 'contributions:delete']);
    AgencyPermission::create(['name' => 'contributions:read']);
    AgencyPermission::create(['name' => 'permissions:manage']);
  }
}
