<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Office;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class SignUpController extends Controller
{
    public function showAdminForm()
    {
        return view('sign_up.admin_information');
    }

    public function storeAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:255',
            'gender'     => 'required|in:1,2,3',
            'birthday'   => 'required|date',
            'email'      => 'required|email|unique:users,email',
            'password'   => [
                'required',
                'string',
                'min:8',
                'max:16',
                'regex:/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d!@#$%^&*(),.?":{}|<>]{8,16}$/'
            ],
            'password_confirmation' => 'required|same:password',
            'company'    => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'position'   => 'required|string|max:255',
        ], [
            'password.regex' => '半角英数字記号8文字以上16文字以内（英数字混在）で入力してください。空白は使用できません。',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // セッションに保存
        Session::put('sign_up_admin', [
            'name' => $request->name,
            'gender' => $request->gender,
            'birthday' => $request->birthday,
            'email' => $request->email,
            'password' => $request->password, // Hash は後で
            'company' => $request->company,
            'department' => $request->department,
            'position' => $request->position,
        ]);

        return redirect()->route('sign-up.company');
    }

    public function showCompanyForm()
    {
        return view('sign_up.company_information'); // bladeファイル名
    }

    public function finalRegister(Request $request)
    {
        $admin = session('sign_up_admin');

        if (!$admin) {
            return redirect()->route('sign-up.admin')->with('error', '管理者情報が見つかりません。');
        }

        $request->validate([
            'positions' => 'required|array|min:1',
            'positions.*' => 'required|string|max:255',
        ], [
            'positions.required' => '役職を1つ以上入力してください。',
        ]);

        DB::transaction(function () use ($admin, $request) {
            $office = Office::create(['name' => $admin['company']]);
        
            $mainDepartment = Department::create([
                'name' => $admin['department'],
                'office_id' => $office->id
            ]);
        
            $mainPosition = Position::create([
                'name' => $admin['position'],
                'office_id' => $office->id
            ]);
        
            foreach ($request->departments as $dept) {
                if ($dept !== $admin['department']) {
                    Department::create([
                        'name' => $dept,
                        'office_id' => $office->id
                    ]);
                }
            }
        
            foreach ($request->positions as $pos) {
                if ($pos !== $admin['position']) {
                    Position::create([
                        'name' => $pos,
                        'office_id' => $office->id
                    ]);
                }
            }
        
            User::create([
                'name' => $admin['name'],
                'email' => $admin['email'],
                'password' => Hash::make($admin['password']),
                'birthday' => $admin['birthday'],
                'gender' => $admin['gender'],
                'office_id' => $office->id,
                'department_id' => $mainDepartment->id,
                'position_id' => $mainPosition->id,
                'administrator' => 1
            ]);
        });
        
        // セッション削除
        session()->forget('sign_up_admin');

        return redirect()->route('login')->with('success', '新規登録が完了しました。ログインしてください。');
    }

    public function start()
    {
        return view('sign_up.start');
    }


}
