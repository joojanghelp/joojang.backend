<?php

use Illuminate\Database\Seeder;
use App\Helpers\MasterHelper;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->init();
    }

    public function init()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'joojang.help@gmail.com',
            'email_verified_at' => \Carbon\Carbon::now(),
            'password' => bcrypt('1212'),
            'uuid' => MasterHelper::GenerateUUID(),
            'type' => 'A01001',
            'state' => 'A10010',
            'level' => 'A20999',
            'active' => 'Y',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
    }
}
