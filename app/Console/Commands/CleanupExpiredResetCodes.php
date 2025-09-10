<?php

namespace App\Console\Commands;

use App\Models\PasswordResetCode;
use Illuminate\Console\Command;

class CleanupExpiredResetCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired password reset codes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deletedCount = PasswordResetCode::cleanupExpired();
        
        $this->info("Cleaned up {$deletedCount} expired password reset codes.");
        
        return Command::SUCCESS;
    }
}
