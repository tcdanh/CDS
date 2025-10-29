<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class AdminConfigController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get(); // eager load role
        $roles = Role::all(); // nếu bạn vẫn cần dropdown trong phân quyền

        return view('admin_setting.index', compact('users', 'roles'));
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'id_role' => 'required|exists:roles,id',
            'invisible' => 'required|boolean',
        ]);

        $user = \App\Models\User::findOrFail($id);
        $user->role_id = $request->id_role;
        $user->invisible = $request->invisible;
        $user->save();

        return redirect()->route('admin_setting.index')->with('success', 'Cập nhật người dùng thành công.');
    }
}
