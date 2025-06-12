<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'annonce_id',
        'client_user_id',
        'supplier_user_id',
        'subject',
        'message',
        'client_name',
        'client_email',
        'client_phone',
        'inquiry_type',
        'budget_range',
        'preferred_contact_method',
        'status',
        'priority',
        'responded_at',
        'response_message',
        'notes',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Status constants
    const STATUS_NEW = 'new';
    const STATUS_READ = 'read';
    const STATUS_RESPONDED = 'responded';
    const STATUS_CLOSED = 'closed';
    const STATUS_SPAM = 'spam';

    // Priority constants
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    // Inquiry types
    const TYPE_GENERAL = 'general';
    const TYPE_PURCHASE = 'purchase';
    const TYPE_LEASE = 'lease';
    const TYPE_PARTNERSHIP = 'partnership';
    const TYPE_INFORMATION = 'information';

    /**
     * Get the property listing this inquiry is for
     */
    public function annonce()
    {
        return $this->belongsTo(Annonce::class);
    }

    /**
     * Get the client who made the inquiry
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_user_id');
    }

    /**
     * Get the supplier who received the inquiry
     */
    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_user_id');
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for new inquiries
     */
    public function scopeNew($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }

    /**
     * Scope for unread inquiries
     */
    public function scopeUnread($query)
    {
        return $query->whereIn('status', [self::STATUS_NEW]);
    }

    /**
     * Mark inquiry as read
     */
    public function markAsRead()
    {
        if ($this->status === self::STATUS_NEW) {
            $this->update(['status' => self::STATUS_READ]);
        }
    }

    /**
     * Mark inquiry as responded
     */
    public function markAsResponded($responseMessage = null)
    {
        $this->update([
            'status' => self::STATUS_RESPONDED,
            'responded_at' => now(),
            'response_message' => $responseMessage,
        ]);
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            self::STATUS_NEW => 'bg-blue-100 text-blue-800',
            self::STATUS_READ => 'bg-yellow-100 text-yellow-800',
            self::STATUS_RESPONDED => 'bg-green-100 text-green-800',
            self::STATUS_CLOSED => 'bg-gray-100 text-gray-800',
            self::STATUS_SPAM => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get priority badge color
     */
    public function getPriorityBadgeColorAttribute()
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'bg-gray-100 text-gray-600',
            self::PRIORITY_MEDIUM => 'bg-blue-100 text-blue-600',
            self::PRIORITY_HIGH => 'bg-orange-100 text-orange-600',
            self::PRIORITY_URGENT => 'bg-red-100 text-red-600',
            default => 'bg-gray-100 text-gray-600',
        };
    }

    /**
     * Get formatted inquiry type
     */
    public function getFormattedTypeAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->inquiry_type));
    }

    /**
     * Check if inquiry is new/unread
     */
    public function getIsUnreadAttribute()
    {
        return $this->status === self::STATUS_NEW;
    }

    /**
     * Get time since inquiry was created
     */
    public function getTimeSinceAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}