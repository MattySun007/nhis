<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Permission::create(['name' => 'institutions:create']);
    Permission::create(['name' => 'institutions:update']);
    Permission::create(['name' => 'institutions:read']);
    Permission::create(['name' => 'institutions:delete']);
    Permission::create(['name' => 'hcps:create']);
    Permission::create(['name' => 'hcps:update']);
    Permission::create(['name' => 'hcps:delete']);
    Permission::create(['name' => 'hcps:read']);
    Permission::create(['name' => 'hcp-users:delete']);
    Permission::create(['name' => 'hcp-users:create']);
    Permission::create(['name' => 'hcp-users:update']);
    Permission::create(['name' => 'hcp-users:read']);
    Permission::create(['name' => 'hcp-users:manage-permissions']);
    Permission::create(['name' => 'institution-users:manage-permissions']);
    Permission::create(['name' => 'institution-users:create']);
    Permission::create(['name' => 'institution-users:delete']);
    Permission::create(['name' => 'institution-users:update']);
    Permission::create(['name' => 'institution-users:read']);
    Permission::create(['name' => 'individual-contributors:create']);
    Permission::create(['name' => 'individual-contributors:update']);
    Permission::create(['name' => 'individual-contributors:delete']);
    Permission::create(['name' => 'individual-contributors:read']);
    Permission::create(['name' => 'institution-hcp:read']);
    Permission::create(['name' => 'institution-hcp:create']);
    Permission::create(['name' => 'institution-hcp:delete']);
    Permission::create(['name' => 'contributions:process']);
    Permission::create(['name' => 'contributions:approve']);
    Permission::create(['name' => 'contributions:delete']);
    Permission::create(['name' => 'contributions:read']);
    Permission::create(['name' => 'contributions:manage']);
    Permission::create(['name' => 'contributions:pay']);
    Permission::create(['name' => 'treatments:create']);
    Permission::create(['name' => 'treatments:update']);
    Permission::create(['name' => 'treatments:delete']);
    Permission::create(['name' => 'treatments:read']);
    Permission::create(['name' => 'treatments:verify']);
    Permission::create(['name' => 'treatments:verify-confirm']);
    Permission::create(['name' => 'claims:read']);
    Permission::create(['name' => 'claims:manage']);
    Permission::create(['name' => 'agency-users:create']);
    Permission::create(['name' => 'agency-users:update']);
    Permission::create(['name' => 'agency-users:read']);
    Permission::create(['name' => 'agency-users:delete']);
    Permission::create(['name' => 'adoption:create']);
    Permission::create(['name' => 'adoption:update']);
    Permission::create(['name' => 'adoption:delete']);
    Permission::create(['name' => 'adoption:read']);
    Permission::create(['name' => 'permissions:manage']);
  }
}
