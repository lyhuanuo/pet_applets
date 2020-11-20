<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [
            'name' => 'admin',
            'email' => '1065628795@qq.com',
            'password' => bcrypt('123456'),
            'ip' => '127.0.0.1'
        ];
        DB::table('admin_user')->insert($data);

    }
}
