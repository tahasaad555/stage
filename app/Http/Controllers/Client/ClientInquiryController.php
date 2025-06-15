<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;

class ClientInquiryController extends Controller
{
    /**
     * Display a listing of client's inquiries.
     */
    public function index(Request $request)
    {
        $query = Inquiry::with(['property'])
            ->where('client_user_id', auth()->id())
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by inquiry type
        if ($request->filled('inquiry_type')) {
            $query->where('inquiry_type', $request->inquiry_type);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'LIKE', "%{$search}%")
                  ->orWhere('message', 'LIKE', "%{$search}%");
            });
        }

        $inquiries = $query->paginate(10)->withQueryString();

        // Get statistics
        $stats = [
            'total' => Inquiry::where('client_user_id', auth()->id())->count(),
            'new' => Inquiry::where('client_user_id', auth()->id())->where('status', 'new')->count(),
            'responded' => Inquiry::where('client_user_id', auth()->id())->where('status', 'responded')->count(),
            'closed' => Inquiry::where('client_user_id', auth()->id())->where('status', 'closed')->count(),
        ];

        return view('client.inquiries.index', compact('inquiries', 'stats'));
    }

    /**
     * Display the specified inquiry.
     */
    public function show(Inquiry $inquiry)
    {
        // Check if the inquiry belongs to the authenticated client
        if ($inquiry->client_user_id !== auth()->id()) {
            abort(403);
        }

        $inquiry->load(['property.user.fournisseur', 'responses']);

        // Mark as read if it was new
        if ($inquiry->status === 'new') {
            $inquiry->update(['status' => 'read']);
        }

        return view('client.inquiries.show', compact('inquiry'));
    }

    /**
     * Mark inquiry as closed.
     */
    public function markClosed(Inquiry $inquiry)
    {
        // Check if the inquiry belongs to the authenticated client
        if ($inquiry->client_user_id !== auth()->id()) {
            abort(403);
        }

        $inquiry->update(['status' => 'closed']);

        return back()->with('success', 'Inquiry marked as closed.');
    }

    /**
     * Delete an inquiry.
     */
    public function destroy(Inquiry $inquiry)
    {
        // Check if the inquiry belongs to the authenticated client
        if ($inquiry->client_user_id !== auth()->id()) {
            abort(403);
        }

        $inquiry->delete();

        return redirect()->route('client.inquiries.index')
            ->with('success', 'Inquiry deleted successfully.');
    }
}