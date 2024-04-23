<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BlockInactiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'block:inactive-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Block users who have not logged in for 3 days';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        User::query()
            ->where('last_login_at', '<', Carbon::now()->subDays(3))
            ->update(['status' => 0]);


        $this->info('Inactive users have been blocked.');
    }
}
