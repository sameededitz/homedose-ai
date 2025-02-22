<?php

namespace App\Console\Commands;

use App\Models\Purchase;
use Illuminate\Console\Command;

class ExpirePlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plans:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire plans that have reached their end date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredPlans = Purchase::where('status', 'active')
            ->where('end_date', '<', now())
            ->update(['status' => 'expired']);

        $this->info("Expired $expiredPlans plans successfully.");
    }
}
