<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginUser extends Command
{
    protected $signature = 'auth:login {email} {password}';
    protected $description = 'Login user via console and generate token';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            $this->error('Login failed: Invalid credentials');
            return 1;
        }

        $token = $user->createToken('ConsoleToken')->plainTextToken;
        $this->info("Login successful! Your token: $token");

        return 0;
    }
}
