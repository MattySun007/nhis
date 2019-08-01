<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use App\Models\Permission;

class PermissionController extends Controller
{

  public function index()
  {
    return view('permissions.list', Permission::listPermissions());
  }

  public function addUserPerms(){
    if (empty($this->request->all()['user_id'])) {
      return response()->json([
        'success' => false,
        'message' => 'User not found',
        'user_permissions' => []
      ]);
    }
    return response()->json(Permission::addUserPerms($this->request->all()), 200);
  }

  public function listUserPerms(){
    return response()->json(Permission::listUserPerms($this->request->all()), 200);
  }

  public function deleteUserPerms(){
    return response()->json(Permission::deleteUserPerms($this->request->all()), 200);
  }

  public function userPermsGet($id=''){
    return response()->json(Permission::userPermsGet($id), 200);
  }







}
