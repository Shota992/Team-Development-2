<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; 

class SettingController extends Controller
{
    public function employeeList(Request $request)
    {
        $officeId = Auth::user()->office_id;
        $departmentId = $request->input('department_id');
        $positionId = $request->input('position_id');
        $keyword = $request->input('keyword');
        $perPage = $request->input('per_page', 10); // デフォルト10件

        $query = User::where('office_id', $officeId)
            ->with(['department', 'position']);

        if (!empty($departmentId)) {
            $query->where('department_id', $departmentId);
        }

        if (!empty($positionId)) {
            $query->where('position_id', $positionId);
        }

        if (!empty($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        $employees = $query->paginate($perPage);
        $departments = Department::where('office_id', $officeId)->get();
        $positions = Position::where('office_id', $officeId)->get();

        return view('set.employee_list', compact('employees', 'departments', 'positions'));
    }

    public function deleteEmployee($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', '従業員を削除しました。');
    }

    public function createEmployee()
    {
        $officeId = Auth::user()->office_id;
        $departments = Department::where('office_id', $officeId)->get();
        $positions = Position::where('office_id', $officeId)->get();
    
        return view('set.employee_registration', compact('departments', 'positions'));
    }
    
    public function storeEmployee(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:1,2,3',
            'birthday' => 'nullable|date',
            'position_id' => 'required|exists:positions,id',
            'email' => 'required|email|unique:users,email',
            'department_id' => 'required|exists:departments,id',
            'administrator' => 'required|in:0,1',
        ]);

        $validated['office_id'] = Auth::user()->office_id;

        $validated['password'] = Hash::make('Password123');

        Log::info('【従業員登録】バリデーション通過:', $validated);

        User::create($validated);

        return redirect()->route('setting.employee-list')->with('success', '従業員を登録しました。');
    }


}
