<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\Annonce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InquiryController extends Controller
{
    /**
     * Display inquiries list
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Build query for inquiries received by this supplier
        $query = Inquiry::with(['annonce.terreAgricole', 'client'])
            ->where('supplier_user_id', $user->id);

        // Apply filters
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('inquiry_type') && $request->inquiry_type !== 'all') {
            $query->where('inquiry_type', $request->inquiry_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('client_email', 'like', "%{$search}%")
                  ->orWhereHas('annonce', function($annonceQuery) use ($search) {
                      $annonceQuery->where('title', 'like', "%{$search}%")
                                  ->orWhere('titre', 'like', "%{$search}%");
                  });
            });
        }

        // Sort by most recent first
        $inquiries = $query->orderBy('created_at', 'desc')->paginate(12);

        // Get statistics
        $stats = $this->getInquiryStats($user->id);

        return view('supplier.inquiries.index', compact('inquiries', 'stats'));
    }

    /**
     * Show specific inquiry
     */
    public function show(Inquiry $inquiry)
    {
        // Ensure the inquiry belongs to the authenticated supplier
        if ($inquiry->supplier_user_id !== auth()->id()) {
            abort(403, 'Unauthorized to view this inquiry.');
        }

        // Mark as read if it's new
        $inquiry->markAsRead();

        // Load relationships
        $inquiry->load(['annonce.terreAgricole', 'client']);

        return view('supplier.inquiries.show', compact('inquiry'));
    }

    /**
     * Update inquiry status
     */
    public function updateStatus(Request $request, Inquiry $inquiry)
    {
        // Ensure the inquiry belongs to the authenticated supplier
        if ($inquiry->supplier_user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:new,read,responded,closed,spam',
            'notes' => 'nullable|string|max:1000',
        ]);

        $inquiry->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Inquiry status updated successfully!'
        ]);
    }

    /**
     * Update inquiry priority
     */
    public function updatePriority(Request $request, Inquiry $inquiry)
    {
        // Ensure the inquiry belongs to the authenticated supplier
        if ($inquiry->supplier_user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $inquiry->update(['priority' => $request->priority]);

        return response()->json([
            'success' => true,
            'message' => 'Priority updated successfully!'
        ]);
    }

    /**
     * Send response to inquiry
     */
    public function sendResponse(Request $request, Inquiry $inquiry)
    {
        // Ensure the inquiry belongs to the authenticated supplier
        if ($inquiry->supplier_user_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized to respond to this inquiry.');
        }

        $request->validate([
            'response_message' => 'required|string|min:10|max:2000',
            'contact_method' => 'required|in:email,phone,both',
        ]);

        // Mark inquiry as responded
        $inquiry->markAsResponded($request->response_message);

        // Send email response (implement email sending logic)
        try {
            $this->sendResponseEmail($inquiry, $request->response_message);
            
            return back()->with('success', 'Response sent successfully!');
        } catch (\Exception $e) {
            \Log::error('Failed to send inquiry response email: ' . $e->getMessage());
            return back()->with('error', 'Response saved but email could not be sent.');
        }
    }

    /**
     * Mark inquiry as spam
     */
    public function markAsSpam(Inquiry $inquiry)
    {
        // Ensure the inquiry belongs to the authenticated supplier
        if ($inquiry->supplier_user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $inquiry->update(['status' => Inquiry::STATUS_SPAM]);

        return response()->json([
            'success' => true,
            'message' => 'Inquiry marked as spam'
        ]);
    }

    /**
     * Delete inquiry
     */
    public function destroy(Inquiry $inquiry)
    {
        // Ensure the inquiry belongs to the authenticated supplier
        if ($inquiry->supplier_user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $inquiry->delete();

        return response()->json([
            'success' => true,
            'message' => 'Inquiry deleted successfully'
        ]);
    }

    /**
     * Bulk update inquiries
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'inquiry_ids' => 'required|array',
            'inquiry_ids.*' => 'exists:inquiries,id',
            'action' => 'required|in:mark_read,mark_responded,mark_closed,mark_spam,delete',
        ]);

        $user = auth()->user();
        
        // Get inquiries that belong to this supplier
        $inquiries = Inquiry::whereIn('id', $request->inquiry_ids)
            ->where('supplier_user_id', $user->id)
            ->get();

        if ($inquiries->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No valid inquiries found'
            ]);
        }

        $updated = 0;
        
        foreach ($inquiries as $inquiry) {
            switch ($request->action) {
                case 'mark_read':
                    $inquiry->update(['status' => Inquiry::STATUS_READ]);
                    $updated++;
                    break;
                case 'mark_responded':
                    $inquiry->update(['status' => Inquiry::STATUS_RESPONDED]);
                    $updated++;
                    break;
                case 'mark_closed':
                    $inquiry->update(['status' => Inquiry::STATUS_CLOSED]);
                    $updated++;
                    break;
                case 'mark_spam':
                    $inquiry->update(['status' => Inquiry::STATUS_SPAM]);
                    $updated++;
                    break;
                case 'delete':
                    $inquiry->delete();
                    $updated++;
                    break;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully processed {$updated} inquiries"
        ]);
    }

    /**
     * Get inquiry statistics for the supplier
     */
    private function getInquiryStats($supplierId)
    {
        $baseQuery = Inquiry::where('supplier_user_id', $supplierId);

        // Get all inquiries for this supplier
        $allInquiries = $baseQuery->get();
        
        // Calculate various statistics
        $total = $allInquiries->count();
        $new = $allInquiries->where('status', Inquiry::STATUS_NEW)->count();
        $read = $allInquiries->where('status', Inquiry::STATUS_READ)->count();
        $responded = $allInquiries->where('status', Inquiry::STATUS_RESPONDED)->count();
        $closed = $allInquiries->where('status', Inquiry::STATUS_CLOSED)->count();
        $highPriority = $allInquiries->where('priority', Inquiry::PRIORITY_HIGH)->count();
        $urgent = $allInquiries->where('priority', Inquiry::PRIORITY_URGENT)->count();
        
        // Time-based statistics
        $thisMonth = $allInquiries->where('created_at', '>=', now()->startOfMonth())->count();
        $thisWeek = $allInquiries->where('created_at', '>=', now()->startOfWeek())->count();

        return [
            'total' => $total,
            'new' => $new,
            'read' => $read,
            'responded' => $responded,
            'closed' => $closed,
            'this_month' => $thisMonth,
            'this_week' => $thisWeek,
            'high_priority' => $highPriority,
            'urgent' => $urgent,
        ];
    }

    /**
     * Send response email to inquiry
     */
    private function sendResponseEmail(Inquiry $inquiry, $responseMessage)
    {
        // TODO: Implement actual email sending logic
        // This would typically use Laravel's Mail facade
        
        /*
        Mail::send('emails.inquiry-response', [
            'inquiry' => $inquiry,
            'responseMessage' => $responseMessage,
            'supplier' => auth()->user()
        ], function ($message) use ($inquiry) {
            $message->to($inquiry->client_email, $inquiry->client_name)
                   ->subject('Response to your inquiry about ' . $inquiry->annonce->title);
        });
        */
        
        // For now, just log the response
        \Log::info("Inquiry response sent to {$inquiry->client_email}: {$responseMessage}");
    }
}