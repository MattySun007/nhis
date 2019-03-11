<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Artisan;
use App\Models\Permission;
use App\Models\User;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
      parent::setUp();
    //   Artisan::call('db:seed', ['--class' => 'PermissionSeeder']);
    //   Artisan::call('db:seed', ['--class' => 'UserTableSeeder']);
    }

    public function testHasPermission()
    {
        $permission = factory(Permission::class)->create();
        $user = factory(User::class)->create();
        $user->givePermissions($permission->name);

        $this->assertTrue($user->hasPermission($permission));
        $this->assertTrue($user->hasPermission($permission->name));
        $this->assertFalse($user->hasPermission('random:delete'));
    }
}
