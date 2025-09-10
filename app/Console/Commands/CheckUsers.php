<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check users in database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();
        
        if ($users->count() === 0) {
            $this->error('No users found in database!');
            $this->info('Please run: php artisan db:seed --class=AdminSeeder');
            return 1;
        }

        $this->info('Users in database:');
        $this->table(
            ['ID', 'Name', 'Email', 'Role', 'Created At'],
            $users->map(function ($user) {
                return [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->created_at
                ];
            })
        );

        return 0;
    }
}
