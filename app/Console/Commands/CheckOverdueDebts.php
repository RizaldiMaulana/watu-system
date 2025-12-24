<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Purchase;
use App\Models\User;
use App\Notifications\UrgentDebtNotification;
use Carbon\Carbon;

class CheckOverdueDebts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:overdue-debts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for overdue purchases (debts) and notify management.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for overdue debts...');

        // Find Unpaid Purchases where Due Date < Today
        $overduePurchases = Purchase::where('payment_status', '!=', 'Paid')
            ->whereDate('due_date', '<', Carbon::today())
            ->get();

        if ($overduePurchases->isEmpty()) {
            $this->info('No overdue debts found.');
            return;
        }

        $this->info("Found {$overduePurchases->count()} overdue debts.");

        // Get Managers/Owners to notify
        $managers = User::whereIn('role', ['admin', 'manager', 'owner'])->get();

        foreach ($overduePurchases as $purchase) {
            foreach ($managers as $user) {
                try {
                    // Check if already notified recently? 
                    // For now, we just send it. The Notification class handles the content.
                    $user->notify(new UrgentDebtNotification($purchase));
                    $this->info("Sent notification for Invoice #{$purchase->invoice_number} to {$user->name}.");
                } catch (\Exception $e) {
                    $this->error("Failed to notify {$user->name}: " . $e->getMessage());
                }
            }
        }

        $this->info('Overdue debt check completed.');
    }
}
