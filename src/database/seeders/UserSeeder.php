<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'email'         => 'user@example.com',
            'password'      => Hash::make('password'), // パスワードはハッシュ化して保存
            // 初回登録時は他の項目は設定せず null にする
            'birthday'      => null,
            'gender'        => null,
            'office_id'     => null,
            'department_id' => null,
            'position_id'   => null,
            'administrator' => null,
        ]);
    }
}
