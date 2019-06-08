<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(UsersSeeder::class);
    }
}

class UsersSeeder extends Seeder
{
    public function run()
    {
        for($i = 1; $i <= 50; $i++){
        	DB::table('users')->insert(
        		[
        			'role'=>0,
        			'name'=>'member'.$i,
        			'username'=>'member'.$i,
        			'email'=>'member'.$i.'@gmail.com',
        			'password'=>bcrypt('member'.$i),
        			'salary'=>1000,
        			'created_at'=>Carbon::now(),
        			'created_by'=>1
        		]
        	);
        }
    }
}
