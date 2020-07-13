<?php

namespace App\Console\Commands;

use App\Exceptions\CustomException;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class UserCreator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {email} {--admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user';

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
        $newId = User::max('id') + 1;
        $name = env('APP_NAME', 'Gaea') . '#' .$newId;
        $password = Str::random(8);

        if ($this->createUser($name, $password)) {
            $this->info("Account:".$this->argument('email').",密码:{$password}");
            Artisan::call('notify:pass', ['email' => $this->argument('email')]);
        } else {
            throw new CustomException('创建用户失败');
        }
    }

    private function createUser($name, $password)
    {
        try {
            return User::create([
                'name' => $name,
                'email' => $this->argument('email'),
                'password' => bcrypt($password),
                'is_admin' => $this->option('admin') ? 1: 2,
                'is_block' => 2,
                'created_at' => Carbon::now()
            ]);
        } catch (\Exception $e) {
            return false;
        }

    }
}
