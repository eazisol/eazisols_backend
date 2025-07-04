<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Check if user has permission to manage permissions
            if (!auth()->user()->hasPermission('dash_permissions')) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }
    
    /**
     * Display a listing of permissions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Group permissions by module for easier management
        $permissions = Permission::all()->groupBy(function ($permission) {
            $parts = explode('_', $permission->key);
            return $parts[0] . '_' . $parts[1]; // Group by module_section
        });
        
        return view('permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created permission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|unique:permissions,key',
        ]);

        try {
            Permission::create(['key' => $request->key]);
            return redirect()->route('permissions.index')->with('success', 'Permission created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified permission.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update the specified permission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'key' => 'required|unique:permissions,key,' . $id,
        ]);

        try {
            $permission = Permission::findOrFail($id);
            $permission->update(['key' => $request->key]);
            
            return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            
            // Check if any roles are using this permission
            if ($permission->roles()->count() > 0) {
                return redirect()->back()->with('error', 'Cannot delete permission because it is assigned to roles');
            }
            
            $permission->delete();
            
            return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
} 