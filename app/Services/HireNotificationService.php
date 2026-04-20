<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\AppNotification;
use App\Models\AuditLog;

class HireNotificationService
{
    public static function notifyTutor($recipientId, $title, $message, $type, $jobId): void
    {
        AppNotification::create([
            'recipient_type' => 'tutor',
            'recipient_id'   => $recipientId,
            'title'          => $title,
            'message'        => $message,
            'type'           => $type,
            'related_job_id' => $jobId,
            'is_read'        => false,
        ]);
    }

    public static function notifyGuardian($recipientId, $title, $message, $type, $jobId): void
    {
        AppNotification::create([
            'recipient_type' => 'guardian',
            'recipient_id'   => $recipientId,
            'title'          => $title,
            'message'        => $message,
            'type'           => $type,
            'related_job_id' => $jobId,
            'is_read'        => false,
        ]);
    }

    public static function notifyAdmin($title, $message, $type, $jobId): void
    {
        foreach (Admin::all() as $admin) {
            AppNotification::create([
                'recipient_type' => 'admin',
                'recipient_id'   => $admin->id,
                'title'          => $title,
                'message'        => $message,
                'type'           => $type,
                'related_job_id' => $jobId,
                'is_read'        => false,
            ]);
        }
    }

    public static function logEvent(array $data): void
    {
        AuditLog::create(array_merge([
            'payment_status' => 'pending',
        ], $data));
    }
}
