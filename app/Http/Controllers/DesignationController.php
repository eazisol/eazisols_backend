<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\Department;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index(Request $request)
    {
        $query = Designation::with('department');
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%");
        }
        $designations = $query->paginate(10);
        return view('department_designation.designation_index', compact('designations'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('department_designation.designation_create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);
        Designation::create($validated);
        return redirect()->route('departments.index')
            ->with('success', 'Designation created successfully.');
    }

    public function edit($id)
    {
        $designation = Designation::findOrFail($id);
        $departments = Department::all();
        return view('department_designation.designation_edit', compact('designation', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $designation = Designation::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);
        $designation->update($validated);
        return redirect()->route('departments.index')
            ->with('success', 'Designation updated successfully.');
    }

    public function destroy($id)
    {
        $designation = Designation::findOrFail($id);
        $designation->delete();
        return redirect()->route('departments.index')
            ->with('success', 'Designation deleted successfully.');
    }
} 