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

        DB::transaction(function () use ($request) {
            $office = Office::create([
                'name' => $request->company,
            ]);

            $department = Department::create([
                'name' => $request->department,
                'office_id' => $office->id,
            ]);

            $position = Position::create([
                'name' => $request->position,
                'office_id' => $office->id,
            ]);

            User::create([
                'name'         => $request->name,
                'email'        => $request->email,
                'password'     => Hash::make($request->password),
                'birthday'     => $request->birthday,
                'gender'       => $request->gender,
                'office_id'    => $office->id,
                'department_id'=> $department->id,
                'position_id'  => $position->id,
                'administrator'=> 1,
            ]);
        });

        // 次画面へ遷移（例: 会社情報ページ）
        return redirect()->route('sign-up.company'); // 後ほど追加
    }

    public function showCompanyForm()
    {
        return view('sign_up.company_information'); // bladeファイル名
    }
}
