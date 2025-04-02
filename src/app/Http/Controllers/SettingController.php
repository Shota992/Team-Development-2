<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Support\Facades\Auth;

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

}
