<?php

namespace App\Console\Commands;

use App\Models\Domain;
use App\Models\NotificationLog;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDomainExpiryReminders extends Command
{
    protected $signature = 'domains:expiry-reminders';

    protected $description = 'Send domain expiry reminder emails to admin and clients';

    public function handle(): int
    {
        $days = (int) Setting::get('expiry_alert_days', 30);
        $adminEmail = Setting::get('admin_notification_email');

        $domains = Domain::query()->active()->expiringWithin($days)->with('client')->get();

        foreach ($domains as $domain) {
            if (Setting::get('notify_admin_enabled', '1') === '1' && $adminEmail) {
                $this->sendIfNotRecent($domain, 'expiry_admin', $adminEmail, $days);
            }

            if (Setting::get('notify_client_enabled', '1') === '1' && $domain->client?->email) {
                $this->sendIfNotRecent($domain, 'expiry_client', $domain->client->email, $days);
            }
        }

        $this->info("Processed {$domains->count()} domains.");

        return self::SUCCESS;
    }

    protected function sendIfNotRecent(Domain $domain, string $type, string $recipient, int $days): void
    {
        if (! $domain->current_expiry_date) {
            return;
        }

        $recent = NotificationLog::query()
            ->where('domain_id', $domain->id)
            ->where('type', $type)
            ->where('created_at', '>=', now()->subDays(7))
            ->exists();

        if ($recent) {
            return;
        }

        $message = "Reminder: domain {$domain->domain_name} expires on {$domain->current_expiry_date->format('Y-m-d')}.";

        $log = NotificationLog::create([
            'domain_id' => $domain->id,
            'client_id' => $domain->client_id,
            'type' => $type,
            'recipient' => $recipient,
            'channel' => 'email',
            'status' => 'pending',
            'message' => $message,
            'meta' => ['days' => $days],
        ]);

        try {
            Mail::raw($message, fn ($mail) => $mail->to($recipient)->subject("Domain expiry: {$domain->domain_name}"));
            $log->update(['status' => 'sent', 'sent_at' => now()]);
        } catch (\Throwable) {
            $log->update(['status' => 'failed']);
        }
    }
}
