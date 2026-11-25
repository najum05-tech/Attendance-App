<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index() {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    public function create() {
        return view('admin.roles.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|string'
        ]);
        $role = new Role();
        $role->name = $request->name;
        // We wrap the text in an array and encode it to JSON to satisfy the database constraint
        $role->permissions = json_encode([$request->permissions]); 
        $role->save();

      //  Role::create($request->all());
        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role) {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role) {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|string'
        ]);
        $role->name = $request->name;
        
        if ($request->has('permissions')) {
             $role->permissions = json_encode([$request->permissions]);
        }
        
        $role->save();
       // $role->update($request->all());
        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role) {
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
