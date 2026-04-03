<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::role('Staff')->with('permissions')->get();
        return view('staff.index', compact('staff'));
    }

    public function create()
    {
        $sections = $this->getSections();
        return view('staff.create', compact('sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:6|confirmed',
            'permissions'           => 'nullable|array',
            'permissions.*'         => 'string',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('Staff');

        if ($request->filled('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        return redirect()->route('staff.index')
            ->with('success', 'Staff member "' . $user->name . '" created successfully.');
    }

    public function edit(User $staff)
    {
        abort_if(!$staff->hasRole('Staff'), 404);
        $sections = $this->getSections();
        $userPermissions = $staff->getPermissionNames()->toArray();
        return view('staff.edit', compact('staff', 'sections', 'userPermissions'));
    }

    public function update(Request $request, User $staff)
    {
        abort_if(!$staff->hasRole('Staff'), 404);
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $staff->id,
            'password'      => 'nullable|string|min:6|confirmed',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        $staff->update(['name' => $request->name, 'email' => $request->email]);

        if ($request->filled('password')) {
            $staff->update(['password' => Hash::make($request->password)]);
        }

        $staff->syncPermissions($request->permissions ?? []);

        return redirect()->route('staff.index')
            ->with('success', 'Staff member "' . $staff->name . '" updated successfully.');
    }

    public function destroy(User $staff)
    {
        abort_if(!$staff->hasRole('Staff'), 404);
        $name = $staff->name;
        $staff->delete();
        return redirect()->route('staff.index')
            ->with('success', 'Staff member "' . $name . '" deleted successfully.');
    }

    private function getSections(): array
    {
        return [
            ['permission' => 'view products',   'label' => 'Products',   'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
            ['permission' => 'view clients',    'label' => 'Parties',    'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['permission' => 'view purchases',  'label' => 'Purchases',  'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
            ['permission' => 'view sales',      'label' => 'Sales',      'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
            ['permission' => 'view services',   'label' => 'Services',   'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
            ['permission' => 'view expenses',   'label' => 'Expenses',   'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
            ['permission' => 'view accounts',   'label' => 'Bank/Cash',  'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
            ['permission' => 'view transfers',  'label' => 'Transfers',  'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
            ['permission' => 'view reports',    'label' => 'Reports',    'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ];
    }
}
