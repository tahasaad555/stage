<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    |
    | Configure how notifications are sent and handled in the AgriTerre
    | platform. These settings control email, SMS, and in-app notifications.
    |
    */

    'email_enabled' => env('NOTIFICATIONS_EMAIL_ENABLED', true),
    'sms_enabled' => env('NOTIFICATIONS_SMS_ENABLED', false),
    'push_enabled' => env('NOTIFICATIONS_PUSH_ENABLED', false),
    'in_app_enabled' => env('NOTIFICATIONS_IN_APP_ENABLED', true),
    
    'frequency' => env('NOTIFICATIONS_FREQUENCY', 'immediate'), // immediate, hourly, daily, weekly
    
    /*
    |--------------------------------------------------------------------------
    | Email Notification Templates
    |--------------------------------------------------------------------------
    */
    
    'email_templates' => [
        'user_registration' => [
            'enabled' => true,
            'subject' => 'Welcome to AgriTerre',
            'template' => 'emails.user.registration',
        ],
        'property_listed' => [
            'enabled' => true,
            'subject' => 'New Property Listed',
            'template' => 'emails.property.listed',
        ],
        'transaction_completed' => [
            'enabled' => true,
            'subject' => 'Transaction Completed',
            'template' => 'emails.transaction.completed',
        ],
        'password_reset' => [
            'enabled' => true,
            'subject' => 'Password Reset Request',
            'template' => 'emails.auth.password-reset',
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Notification Recipients
    |--------------------------------------------------------------------------
    */
    
    'admin_notifications' => [
        'new_user_registration' => true,
        'new_property_listing' => true,
        'transaction_alerts' => true,
        'system_errors' => true,
    ],
    
    'user_notifications' => [
        'property_updates' => true,
        'transaction_updates' => true,
        'account_security' => true,
        'marketing_emails' => false,
    ],
];