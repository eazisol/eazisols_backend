<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Check if user has permission to manage roles
            if (!auth()->user()->hasPermission('dash_roles')) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }
    
    /**
     * Display a listing of the roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::where('key', '!=', 'dash_dashboard')
            ->get()
            ->groupBy(function ($permission) {
                $parts = explode('_', $permission->key);
                return $parts[0] . '_' . $parts[1]; // Group by module
            });
        
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create(['name' => $request->name]);
            
            // Always include dashboard permission
            $dashboardPermission = Permission::where('key', 'dash_dashboard')->first();
            $permissionsToAttach = $request->permissions ?? [];
            
            if ($dashboardPermission) {
                if (!in_array($dashboardPermission->id, $permissionsToAttach)) {
                    $permissionsToAttach[] = $dashboardPermission->id;
                }
            }
            
            $role->permissions()->attach($permissionsToAttach);
            
            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Role created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::where('key', '!=', 'dash_dashboard')
            ->get()
            ->groupBy(function ($permission) {
                $parts = explode('_', $permission->key);
                return $parts[0] . '_' . $parts[1]; // Group by module
            });
        
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'array',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::findOrFail($id);
            $role->update(['name' => $request->name]);
            
            // Always include dashboard permission
            $dashboardPermission = Permission::where('key', 'dash_dashboard')->first();
            $permissionsToSync = $request->permissions ?? [];
            
            if ($dashboardPermission) {
                if (!in_array($dashboardPermission->id, $permissionsToSync)) {
                    $permissionsToSync[] = $dashboardPermission->id;
                }
            }
            
            $role->permissions()->sync($permissionsToSync);
            
            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Role updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            // Check if any users are using this role
            if ($role->users()->count() > 0) {
                return redirect()->back()->with('error', 'Cannot delete role because it is assigned to users');
            }
            
            $role->permissions()->detach();
            $role->delete();
            
            return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
} 