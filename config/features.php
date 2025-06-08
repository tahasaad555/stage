<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    |
    | Here you may enable or disable various features of the AgriTerre
    | platform. These settings can be controlled from the admin panel.
    |
    */

    'user_registration' => env('FEATURE_USER_REGISTRATION', true),
    'google_maps' => env('FEATURE_GOOGLE_MAPS', true),
    'analytics' => env('FEATURE_ANALYTICS', true),
    'backup' => env('FEATURE_BACKUP', true),
    'email_verification' => env('FEATURE_EMAIL_VERIFICATION', true),
    'sms_notifications' => env('FEATURE_SMS_NOTIFICATIONS', false),
    'two_factor_auth' => env('FEATURE_TWO_FACTOR_AUTH', false),
    'social_login' => env('FEATURE_SOCIAL_LOGIN', false),
    'api_access' => env('FEATURE_API_ACCESS', false),
    'multi_language' => env('FEATURE_MULTI_LANGUAGE', true),
    'dark_mode' => env('FEATURE_DARK_MODE', false),
    'export_data' => env('FEATURE_EXPORT_DATA', true),
    'import_data' => env('FEATURE_IMPORT_DATA', true),
    'advanced_search' => env('FEATURE_ADVANCED_SEARCH', true),
    'property_alerts' => env('FEATURE_PROPERTY_ALERTS', true),
    'favorites' => env('FEATURE_FAVORITES', true),
    'reviews_ratings' => env('FEATURE_REVIEWS_RATINGS', false),
    'chat_support' => env('FEATURE_CHAT_SUPPORT', false),
    'document_upload' => env('FEATURE_DOCUMENT_UPLOAD', true),
    'virtual_tours' => env('FEATURE_VIRTUAL_TOURS', false),
    'payment_gateway' => env('FEATURE_PAYMENT_GATEWAY', false),
];