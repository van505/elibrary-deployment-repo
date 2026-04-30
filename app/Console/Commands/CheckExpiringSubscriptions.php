<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckExpiringSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check-expiring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for subscriptions expiring in 3 days and notify admins';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDate = now()->addDays(3)->toDateString();

        $expiringSubs = \App\Models\Subscription::with(['member.user', 'plan'])
            ->where('status', 'active')
            ->whereNotNull('expires_at')
            ->whereDate('expires_at', $targetDate)
            ->get();

        foreach ($expiringSubs as $sub) {
            $member = $sub->member;
            $name = $member->first_name ? $member->full_name : $member->user->email;
            
            \App\Models\AdminNotification::create([
                'type' => 'expiry_warning',
                'message' => "{$name}'s {$sub->plan->name} subscription expires in 3 days (on {$sub->expires_at->format('M d, Y')}).",
                'action_url' => route('admin.members.show', $member->id),
            ]);
        }

        $this->info("Checked expiring subscriptions. Found: " . $expiringSubs->count());
    }
}
