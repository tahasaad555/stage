<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public static function sendNotification($userId, $content, $type = 'system')
    {
        $notification = new Notification();
        $notification->user_id = $userId;
        $notification->contenu = $content;
        $notification->type = $type;
        $notification->dateCreation = now();
        $notification->lu = false;
        $notification->save();
        
        return $notification;
    }
    
    public static function sendMessageNotification($userId, $senderName)
    {
        return self::sendNotification(
            $userId,
            "You have a new message from $senderName.",
            'message'
        );
    }
    
    public static function sendTransactionNotification($userId, $status, $amount)
    {
        return self::sendNotification(
            $userId,
            "Your transaction of €$amount has been $status.",
            'transaction'
        );
    }
    
    public static function sendSystemNotification($userId, $content)
    {
        return self::sendNotification($userId, $content, 'system');
    }
}