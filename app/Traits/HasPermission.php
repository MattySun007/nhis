<?php
namespace App\Traits;

use App\Models\Permission;

trait HasPermission
{
  public function permissions()
  {
    return $this->belongsToMany(Permission::class);
  }

  public function hasPermission($permission)
  {
    $permission = ! is_string($permission) ?
      (is_object($permission) ? $permission->name : $permission['name']) : $permission;

    return (bool) $this->permissions()->where('name', $permission)->count();
  }

  public function getAllPermissions(array $permissions)
  {
    return Permission::whereIn('name', $permissions)->get();
  }

  public function givePermissionsTo(... $permissions)
  {
    $permissions = $this->getAllPermissions($permissions);

    if($permissions !== null)
    {
      $this->permissions()->saveMany($permissions);
    }

    return $this;
  }

  public function deletePermissions(... $permissions)
  {
    $permissions = $this->getAllPermissions($permissions);
    $this->permissions()->detach($permissions);
    return $this;
  }
}
