<?php
namespace App\Listeners;

use App\Events\UserRegistered;
use App\Events\PaymentReceived;
use App\Events\ListingCreated;
use App\Services\NotificationService;

class CreateAdminNotifications
{
    protected $notificationService;
    
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    
    public function handleUserRegistered(UserRegistered $event)
    {
        $user = $event->user;
        $userType = $user->fournisseur ? 'supplier' : 'client';
        
        $this->notificationService->notifyAllAdmins(
            'user_registered',
            'New User Registration',
            "{$user->full_name} registered as a {$userType}",
            ['user_id' => $user->id, 'type' => $userType],
            route('admin.users.show', $user->id)
        );
    }
    
    public function handlePaymentReceived($event)
    {
        $transaction = $event->transaction;
        
        $this->notificationService->notifyAllAdmins(
            'payment_received',
            'Payment Received',
            "€{$transaction->montant} payment received for transaction #{$transaction->reference}",
            ['transaction_id' => $transaction->id, 'amount' => $transaction->montant],
            route('admin.transactions.show', $transaction->id)
        );
    }
    
    public function handleListingCreated($event)
    {
        $listing = $event->listing;
        
        $this->notificationService->notifyAllAdmins(
            'listing_pending',
            'New Listing Requires Review',
            "'{$listing->title}' in {$listing->terreAgricole->region} needs approval",
            ['listing_id' => $listing->id],
            route('admin.listings.show', $listing->id)
        );
    }
}