<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmpPersonalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Location;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::with('empPersonalDetail')->whereHas('role', function ($q) {
            $q->where('name', '!=', 'admin');
        });

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhereHas('empPersonalDetail', function ($q) use ($search) {
                      $q->where('phone', 'LIKE', "%{$search}%");
                  });
            });
        }

        $employees = $query->paginate(10);

        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employees.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|string|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'current_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => 2, // Assuming 2 is the role for 'employee'
        ]);

        $user->empPersonalDetail()->create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'gender' => $validated['gender'],
            'date_of_birth' => $validated['date_of_birth'],
            'current_address' => $validated['current_address'],
            'permanent_address' => $validated['permanent_address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'country' => $validated['country'],
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = User::with('empPersonalDetail', 'emergencyContacts', 'jobInformation')->findOrFail($id);

        $managers = User::whereHas('role', function ($query) {
            $query->where('name', 'Project Manager');
        })->get();

        $teamLeads = User::whereHas('role', function ($query) {
            $query->where('name', 'Team Lead');
        })->get();

        $locations = Location::all();
        $departments = \App\Models\Department::all();
        $designations = \App\Models\Designation::all();

        return view('employees.edit', compact('employee', 'managers', 'teamLeads', 'locations', 'departments', 'designations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $employee = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->id,
            'password' => 'nullable|string|min:8|confirmed',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|string|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'current_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            // Emergency Contact Validation
            'contact_name' => 'nullable|string|max:255',
            'relationship' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            // Job Information Validation
            'department_id' => 'nullable|string|max:255',
            'designation_id' => 'nullable|string|max:255',
            'work_type' => 'nullable|string|in:full_time,part_time,contract,intern',
            'joining_date' => 'nullable|date',
            'probation_end_date' => 'nullable|date',
            'reporting_manager_id' => 'nullable|exists:users,id',
            'reporting_teamlead_id' => 'nullable|exists:users,id',
            'work_location' => 'nullable|string|max:255',
        ]);

        $employee->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($request->filled('password')) {
            $employee->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        $employee->empPersonalDetail()->updateOrCreate(
            ['user_id' => $employee->id],
            [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone' => $validated['phone'],
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date_of_birth'],
                'current_address' => $validated['current_address'],
                'permanent_address' => $validated['permanent_address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'country' => $validated['country'],
            ]
        );

        // Update or create emergency contact
        if ($request->filled('contact_name')) {
            $employee->emergencyContacts()->updateOrCreate(
                ['user_id' => $employee->id],
                [
                    'contact_name' => $validated['contact_name'],
                    'relationship' => $validated['relationship'],
                    'phone_number' => $validated['phone_number'],
                    'alternate_phone' => $validated['alternate_phone'],
                ]
            );
        }

        // Update or create job information
        $employee->jobInformation()->updateOrCreate(
            ['user_id' => $employee->id],
            [
                'department_id' => $validated['department_id'],
                'designation_id' => $validated['designation_id'],
                'work_type' => $validated['work_type'],
                'joining_date' => $validated['joining_date'],
                'probation_end_date' => $validated['probation_end_date'],
                'reporting_manager_id' => $validated['reporting_manager_id'],
                'reporting_teamlead_id' => $validated['reporting_teamlead_id'],
                'work_location' => $validated['work_location'],
            ]
        );


        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = User::findOrFail($id);

        // Delete personal details first
        $employee->empPersonalDetail()->delete();

        // Then delete the user
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}