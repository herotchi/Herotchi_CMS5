<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Admin::factory()->create([
            'name' => 'test1',
            'login_id' => 'test1test1',
            'password' => Hash::make('test1test1'),
        ]);

        Admin::factory()->create([
            'name' => 'test2',
            'login_id' => 'test2test2',
            'password' => Hash::make('test2test2'),
        ]);
    }
}
