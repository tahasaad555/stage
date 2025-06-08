<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Business Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the business rules and settings for AgriTerre
    | platform including commission rates, pricing limits, and other
    | business logic parameters.
    |
    */

    'commission_rate' => env('BUSINESS_COMMISSION_RATE', 5.0),
    'vat_rate' => env('BUSINESS_VAT_RATE', 20.0),
    'min_property_price' => env('BUSINESS_MIN_PROPERTY_PRICE', 10000),
    'max_property_price' => env('BUSINESS_MAX_PROPERTY_PRICE', 10000000),
    'surface_unit' => env('BUSINESS_SURFACE_UNIT', 'hectares'),
    
    /*
    |--------------------------------------------------------------------------
    | Payment Settings
    |--------------------------------------------------------------------------
    */
    
    'payment_methods' => [
        'bank_transfer' => 'Bank Transfer',
        'wire_transfer' => 'Wire Transfer',
        'credit_card' => 'Credit Card',
        'cash' => 'Cash Payment',
    ],
    
    'transaction_statuses' => [
        'pending' => 'Pending',
        'completed' => 'Completed',
        'failed' => 'Failed',
        'cancelled' => 'Cancelled',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Property Settings
    |--------------------------------------------------------------------------
    */
    
    'property_statuses' => [
        'available' => 'Available',
        'reserved' => 'Reserved',
        'sold' => 'Sold',
    ],
    
    'soil_types' => [
        'clay' => 'Clay',
        'sandy' => 'Sandy',
        'loamy' => 'Loamy',
        'silty' => 'Silty',
        'rocky' => 'Rocky',
        'alluvial' => 'Alluvial',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Morocco Regions
    |--------------------------------------------------------------------------
    */
    
    'morocco_regions' => [
        'Tanger-Tétouan-Al Hoceïma' => 'Tanger-Tétouan-Al Hoceïma',
        'Oriental' => 'Oriental',
        'Fès-Meknès' => 'Fès-Meknès',
        'Rabat-Salé-Kénitra' => 'Rabat-Salé-Kénitra',
        'Béni Mellal-Khénifra' => 'Béni Mellal-Khénifra',
        'Casablanca-Settat' => 'Casablanca-Settat',
        'Marrakech-Safi' => 'Marrakech-Safi',
        'Drâa-Tafilalet' => 'Drâa-Tafilalet',
        'Souss-Massa' => 'Souss-Massa',
        'Guelmim-Oued Noun' => 'Guelmim-Oued Noun',
        'Laâyoune-Sakia El Hamra' => 'Laâyoune-Sakia El Hamra',
        'Dakhla-Oued Ed-Dahab' => 'Dakhla-Oued Ed-Dahab',
    ],
];