<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasPermission;

class Permission extends Model
{
  use Notifiable, HasPermission;

  public static function listPermissions()
  {
    $perms = [];
    $title = $user = '';
    if(auth()->user()->user_type == 'Agency User'){
      $perms = AgencyPermission::all();
      $title = 'Agency User Permission';
    }
    if(auth()->user()->user_type == 'Hcp User'){
      $title = 'Hcp User Permission';
      $perms = HcpPermission::all();
    }
    if(auth()->user()->user_type == 'Institution User'){
      $title = 'Institution User Permission';
      $perms = InstitutionPermission::all();
    }
    return ['itemTitle' => $title, 'perms' => $perms];
  }

  public static function addUserPerms($data){
    $user = User::find($data['user_id']);
    if (empty($user)) {
      return [
        'success' => false,
        'message' => 'User not found',
        'user_permissions' => []
      ];
    }
    $message = $user->givePermissions($data['permissions']) ? 'User permissions successfully added' : 'Permission not added, possible duplicate entry!';
    return[
      'success' => true,
      'message' => $message,
      'user_permissions' => $user->permissions
    ];
  }

  public static function listUserPerms($data){
    $user = User::find($data['user_id']);//dd($data);
    if (empty($user)) {
      return [
        'success' => false,
        'message' => 'User not found',
        'user_permissions' => []
      ];
    }
    return[
      'success' => true,
      'message' => 'User Permissions Fetched',
      'user_permissions' => $user->permissions
    ];
  }

  public static function deleteUserPerms($data){
    $user = User::find($data['user_id']);
    if (empty($user)) {
      return [
        'success' => false,
        'message' => 'User not found',
        'user_permissions' => []
      ];
    }
    $user->deletePermissions($data['permissions']);
    return[
      'success' => true,
      'message' => 'User permissions successfully deleted',
      'user_permissions' => $user->permissions
    ];
  }

  public static function userPermsGet($id){
    $user = User::find($id);
    return [
      'user_permissions' => $user->permissions
    ];
  }

}
