<?php

use Illuminate\Database\Seeder;
use App\Helpers\MasterHelper;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Str;

class EmailAuthSeeder extends Seeder
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
        $taskResult = User::where('email', 'joojang.help@gmail.com')->get();
        $userInfo = $taskResult->first();

        DB::table('tbl_email_auth_master')->insert([
            'user_uuid' => $userInfo->uuid,
            'auth_code' => Str::random(80),
            'verified_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
    }
}
