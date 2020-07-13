<?php

namespace App\Console\Commands;

use App\Mail\SendUserPassword;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NewPasswordNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:pass {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send new password to user';

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
        try {
            $user = User::where('email', $this->argument('email'))->firstOrFail();
            $newPass = Str::random(8);
            $user->setPassword($newPass);
            if ($user->save()) {
                Mail::to($user)->send(new SendUserPassword($user->email, $newPass));
                return true;
            }
            return false;
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

    }
}
