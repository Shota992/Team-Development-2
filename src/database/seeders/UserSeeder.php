<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        User::create ([
            'name' => '鈴木拓真',
            'email' => 'suzuki.taku@example.com',
            'password' => Hash::make('Password123'),
            'birthday' => '1984-07-27',
            'gender' => '0',
            'office_id' => 1,
            'department_id' => 1,
            'position_id' => 3,
            'administrator' => true,
        ]);

        // office_id=1, department_id=1, position_id=4を2人
        User::factory()->count(2)->create([
            'office_id' => 1,
            'department_id' => 1,
            'position_id' => 4,
            'administrator' => false,
        ]);

        // office_id=1, department_id=1, position_id=5を10人
        User::factory()->count(10)->create([
            'office_id' => 1,
            'department_id' => 1,
            'position_id' => 6,
            'administrator' => false,
        ]);

        // office_id=1, department_id=2, position_id=3を1人
        User::factory()->create([
            'office_id' => 1,
            'department_id' => 2,
            'position_id' => 3,
            'administrator' => true,
        ]);

        // office_id=1, department_id=2, position_id=4を2人
        User::factory()->count(2)->create([
            'office_id' => 1,
            'department_id' => 2,
            'position_id' => 4,
            'administrator' => false,
        ]);

        // office_id=1, department_id=2, position_id=5を7人
        User::factory()->count(7)->create([
            'office_id' => 1,
            'department_id' => 2,
            'position_id' => 6,
            'administrator' => false,
        ]);

        // office_id=1, department_id=3, position_id=3を1人
        User::factory()->create([
            'office_id' => 1,
            'department_id' => 3,
            'position_id' => 3,
            'administrator' => true,
        ]);

        // office_id=1, department_id=3, position_id=4を2人
        User::factory()->count(2)->create([
            'office_id' => 1,
            'department_id' => 3,
            'position_id' => 4,
            'administrator' => false,
        ]);

        // office_id=1, department_id=3, position_id=5を7人
        User::factory()->count(7)->create([
            'office_id' => 1,
            'department_id' => 3,
            'position_id' => 6,
            'administrator' => false,
        ]);

        // office_id=1, department_id=4, position_id=3を1人
        User::factory()->create([
            'office_id' => 1,
            'department_id' => 4,
            'position_id' => 3,
            'administrator' => true,
        ]);

        // office_id=1, department_id=4, position_id=4を2人
        User::factory()->count(2)->create([
            'office_id' => 1,
            'department_id' => 4,
            'position_id' => 4,
            'administrator' => false,
        ]);

        // office_id=1, department_id=4, position_id=5を7人
        User::factory()->count(7)->create([
            'office_id' => 1,
            'department_id' => 4,
            'position_id' => 6,
            'administrator' => false,
        ]);

        // office_id=1, department_id=5, position_id=3を1人
        User::factory()->create([
            'office_id' => 1,
            'department_id' => 5,
            'position_id' => 3,
            'administrator' => true,
        ]);

        // office_id=1, department_id=5, position_id=4を2人
        User::factory()->count(2)->create([
            'office_id' => 1,
            'department_id' => 5,
            'position_id' => 4,
            'administrator' => false,
        ]);

        // office_id=1, department_id=5, position_id=5を5人
        User::factory()->count(5)->create([
            'office_id' => 1,
            'department_id' => 5,
            'position_id' => 6,
            'administrator' => false,
        ]);

        // office_id=2, department_id=6, position_id=9を1人
        User::factory()->create([
            'office_id' => 2,
            'department_id' => 6,
            'position_id' => 9,
            'administrator' => true,
        ]);

        // office_id=2, department_id=6, position_id=10を2人
        User::factory()->count(2)->create([
            'office_id' => 2,
            'department_id' => 6,
            'position_id' => 10,
            'administrator' => false,
        ]);

        // office_id=2, department_id=6, position_id=11を7人
        User::factory()->count(7)->create([
            'office_id' => 2,
            'department_id' => 6,
            'position_id' => 12,
            'administrator' => false,
        ]);

        // office_id=2, department_id=7, position_id=9を1人
        User::factory()->create([
            'office_id' => 2,
            'department_id' => 7,
            'position_id' => 9,
            'administrator' => true,
        ]);

        // office_id=2, department_id=7, position_id=10を2人
        User::factory()->count(2)->create([
            'office_id' => 2,
            'department_id' => 7,
            'position_id' => 10,
            'administrator' => false,
        ]);

        // office_id=2, department_id=7, position_id=11を7人
        User::factory()->count(7)->create([
            'office_id' => 2,
            'department_id' => 7,
            'position_id' => 12,
            'administrator' => false,
        ]);

        // office_id=2, department_id=8, position_id=9を1人
        User::factory()->create([
            'office_id' => 2,
            'department_id' => 8,
            'position_id' => 9,
            'administrator' => true,
        ]);

        // office_id=2, department_id=8, position_id=10を2人
        User::factory()->count(2)->create([
            'office_id' => 2,
            'department_id' => 8,
            'position_id' => 10,
            'administrator' => false,
        ]);

        // office_id=2, department_id=8, position_id=11を7人
        User::factory()->count(7)->create([
            'office_id' => 2,
            'department_id' => 8,
            'position_id' => 12,
            'administrator' => false,
        ]);

        // office_id=2, department_id=9, position_id=9を1人
        User::factory()->create([
            'office_id' => 2,
            'department_id' => 9,
            'position_id' => 9,
            'administrator' => true,
        ]);

        // office_id=2, department_id=9, position_id=10を2人
        User::factory()->count(2)->create([
            'office_id' => 2,
            'department_id' => 9,
            'position_id' => 10,
            'administrator' => false,
        ]);

        // office_id=2, department_id=9, position_id=11を7人
        User::factory()->count(7)->create([
            'office_id' => 2,
            'department_id' => 9,
            'position_id' => 12,
            'administrator' => false,
        ]);

        // office_id=3, department_id=10, position_id=15を1人
        User::factory()->create([
            'office_id' => 3,
            'department_id' => 10,
            'position_id' => 15,
            'administrator' => true,
        ]);

        // office_id=3, department_id=10, position_id=16を2人
        User::factory()->count(2)->create([
            'office_id' => 3,
            'department_id' => 10,
            'position_id' => 16,
            'administrator' => false,
        ]);

        // office_id=3, department_id=10, position_id=18を7人
        User::factory()->count(7)->create([
            'office_id' => 3,
            'department_id' => 10,
            'position_id' => 18,
            'administrator' => false,
        ]);

        // office_id=3, department_id=11, position_id=15を1人
        User::factory()->create([
            'office_id' => 3,
            'department_id' => 11,
            'position_id' => 15,
            'administrator' => true,
        ]);

        // office_id=3, department_id=11, position_id=16を2人
        User::factory()->count(2)->create([
            'office_id' => 3,
            'department_id' => 11,
            'position_id' => 16,
            'administrator' => false,
        ]);

        // office_id=3, department_id=11, position_id=18を7人
        User::factory()->count(7)->create([
            'office_id' => 3,
            'department_id' => 11,
            'position_id' => 18,
            'administrator' => false,
        ]);

        // office_id=3, department_id=12, position_id=15を1人
        User::factory()->create([
            'office_id' => 3,
            'department_id' => 12,
            'position_id' => 15,
            'administrator' => true,
        ]);

        // office_id=3, department_id=12, position_id=16を2人
        User::factory()->count(2)->create([
            'office_id' => 3,
            'department_id' => 12,
            'position_id' => 16,
            'administrator' => false,
        ]);

        // office_id=3, department_id=12, position_id=18を7人
        User::factory()->count(7)->create([
            'office_id' => 3,
            'department_id' => 12,
            'position_id' => 18,
            'administrator' => false,
        ]);
    }
}
