<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for($i=1; $i<10; $i++){
        	DB::table('users')->insert([
        		'nickname'=>'user_'.$i,
        		'email'=>'email_'.$i.'@gmail.com',
        		'password'=>bcrypt(123456),
        		'grant'=>'1',
        		'status'=>'1',
        	]);	
        }
        
    }
}
