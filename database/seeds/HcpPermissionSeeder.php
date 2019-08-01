<?php

use Illuminate\Database\Seeder;
use App\Models\HcpPermission;

class HcpPermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    HcpPermission::create(['name' => 'hcps:update']);
    HcpPermission::create(['name' => 'hcps:read']);
    HcpPermission::create(['name' => 'hcp-users:read']);
    HcpPermission::create(['name' => 'hcp-users:update']);
    HcpPermission::create(['name' => 'hcp-users:delete']);
    HcpPermission::create(['name' => 'hcp-users:create']);
    HcpPermission::create(['name' => 'treatments:read']);
    HcpPermission::create(['name' => 'treatments:delete']);
    HcpPermission::create(['name' => 'treatments:create']);
    HcpPermission::create(['name' => 'treatments:update']);
    HcpPermission::create(['name' => 'treatments:verify']);
    HcpPermission::create(['name' => 'treatments:verify-confirm']);
    HcpPermission::create(['name' => 'claims:read']);

    HcpPermission::create(['name' => 'adoption:create']);
    HcpPermission::create(['name' => 'adoption:update']);
    HcpPermission::create(['name' => 'adoption:delete']);
    HcpPermission::create(['name' => 'adoption:read']);
    HcpPermission::create(['name' => 'contributions:pay']);
    HcpPermission::create(['name' => 'contributions:process']);
    HcpPermission::create(['name' => 'contributions:approve']);
    HcpPermission::create(['name' => 'contributions:delete']);
    HcpPermission::create(['name' => 'contributions:read']);
    HcpPermission::create(['name' => 'permissions:manage']);
  }
}
