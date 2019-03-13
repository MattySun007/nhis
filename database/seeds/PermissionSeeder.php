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
  }
}
