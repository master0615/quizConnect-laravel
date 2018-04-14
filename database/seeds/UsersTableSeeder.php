<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => '1',
                'first_name' => 'Jeremy',
                'last_name' => 'Jacob',
                'email' => 'jeremy@example.org',
                'password' => bcrypt('quizconnect'),
                'role' => 'owner',
                'active' => 'active',
            ], 
            [
                'id' => '2',
                'first_name' => 'Eimantas',
                'last_name' => 'Kasperiunas',
                'email' => 'eimantas@example.org',
                'password' => bcrypt('quizconnect'),
                'role' => 'admin',
                'active' => 'active',                
            ],
            [
                'id' => '3',
                'first_name' => 'Pavel',
                'last_name' => 'Panov',
                'email' => 'pavelpanov@example.org',
                'password' => bcrypt('quizconnect'),
                'role' => 'admin',
                'active' => 'active',                
            ]
        ]); 
    }
}
