<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class LogoutUser extends Command
{
    protected $signature = 'auth:logout {email}';
    protected $description = 'Logout user via console by revoking all tokens';

    public function handle()
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error('Logout failed: User not found');
            return 1;
        }

        $user->tokens()->delete();
        $this->info("Logout successful! All tokens revoked.");

        return 0;
    }
}
