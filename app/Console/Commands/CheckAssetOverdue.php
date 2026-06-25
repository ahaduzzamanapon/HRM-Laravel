<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AssetAssignment;
use App\Models\User;
use App\Notifications\AssetOverdueNotification;
use Carbon\Carbon;

class CheckAssetOverdue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asset:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for overdue asset assignments and send notifications';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $overdueAssignments = AssetAssignment::with(['asset', 'employee'])
            ->where('status', 'Assigned')
            ->whereNotNull('expected_return_date')
            ->where('expected_return_date', '<', Carbon::today())
            ->get();

        $count = 0;
        foreach ($overdueAssignments as $assignment) {
            if ($assignment->employee) {
                // To avoid spamming, we might want to check if notification was already sent today,
                // but for this implementation, we will just send it when the command is run.
                $assignment->employee->notify(new AssetOverdueNotification($assignment));
                $count++;
            }
        }

        $this->info("Found and notified $count overdue asset assignments.");

        return Command::SUCCESS;
    }
}
