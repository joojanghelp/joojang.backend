<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Helpers\MasterHelper;
use Illuminate\Support\Facades\DB;

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
        $this->init();

        echo "\n";
    }

    public function init()
    {
        DB::table('users')->insert([
            'name' => 'testid',
            'email' => 'testid@gmail.com',
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
