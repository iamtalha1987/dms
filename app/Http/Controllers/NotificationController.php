<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\NotificationLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:expiry.notify');
    }

    public function send(Request $request, Domain $domain): RedirectResponse
    {
        $validated = $request->validate([
            'recipient_type' => ['required', 'in:admin,client'],
        ]);

        $domain->load('client');

        $recipient = $validated['recipient_type'] === 'client'
            ? $domain->client?->email
            : \App\Models\Setting::get('admin_notification_email');

        if (! $recipient) {
            return back()->with('error', 'No recipient email available.');
        }

        $log = NotificationLog::create([
            'domain_id' => $domain->id,
            'client_id' => $domain->client_id,
            'type' => $validated['recipient_type'] === 'client' ? 'expiry_client' : 'expiry_admin',
            'recipient' => $recipient,
            'channel' => 'email',
            'status' => 'pending',
            'message' => "Domain {$domain->domain_name} expires on {$domain->current_expiry_date->format('Y-m-d')}.",
            'sent_by' => auth()->id(),
            'meta' => ['days_until_expiry' => $domain->days_until_expiry],
        ]);

        try {
            Mail::raw($log->message, function ($message) use ($recipient, $domain) {
                $message->to($recipient)
                    ->subject("Domain expiry reminder: {$domain->domain_name}");
            });

            $log->update(['status' => 'sent', 'sent_at' => now()]);
        } catch (\Throwable $e) {
            $log->update(['status' => 'failed']);

            return back()->with('error', 'Failed to send notification: '.$e->getMessage());
        }

        return back()->with('success', 'Notification sent successfully.');
    }
}
