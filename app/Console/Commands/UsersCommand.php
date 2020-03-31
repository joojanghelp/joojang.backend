<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Helpers\MasterHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '테스트 사용자 등록.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // $this->init();

        $i = 1;
        do {
          $this->init();
          $i++; // 1씩 증가
        } while ( $i < 60 );

        echo "\n";
    }

    public function init()
    {
        DB::table('users')->insert([
            'name' => Str::random(7),
            'email' => Str::random(7).'@gmail.com',
            'email_verified_at' => \Carbon\Carbon::now(),
            'password' => bcrypt('1212'),
            'uuid' => MasterHelper::GenerateUUID(),
            'type' => 'A01001',
            'state' => 'A10010',
            'level' => 'A20000',
            'active' => 'Y',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
    }
}
