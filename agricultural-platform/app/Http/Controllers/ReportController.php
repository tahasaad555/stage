<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TerreAgricole;
use App\Models\Client;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use PDF; // Use a PDF library like DomPDF

class ReportController extends Controller
{
    public function index()
    {
        // Only admins can access this
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('reports.index');
    }
    
    public function generateTransactionReport(Request $request)
    {
        // Only admins can access this
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:pdf,csv',
        ]);
        
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        // Get transactions within date range
        $transactions = Transaction::whereBetween('dateTransaction', [$startDate, $endDate])
            ->with(['client.utilisateur', 'fournisseur.utilisateur'])
            ->get();
            
        if ($request->format === 'pdf') {
            // Generate PDF report
            $pdf = PDF::loadView('reports.transactions_pdf', compact('transactions', 'startDate', 'endDate'));
            return $pdf->download('transactions_report.pdf');
        } else {
            // Generate CSV report
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="transactions_report.csv"',
            ];
            
            $callback = function() use ($transactions) {
                $file = fopen('php://output', 'w');
                
                // Add CSV headers
                fputcsv($file, [
                    'ID', 'Date', 'Client', 'Fournisseur', 'Montant', 'Commission', 'Statut'
                ]);
                
                // Add data rows
                foreach ($transactions as $transaction) {
                    fputcsv($file, [
                        $transaction->id,
                        $transaction->dateTransaction->format('d/m/Y'),
                        $transaction->client->utilisateur->prenom . ' ' . $transaction->client->utilisateur->nom,
                        $transaction->fournisseur->entreprise,
                        $transaction->montant,
                        $transaction->commission,
                        $transaction->statusTransaction,
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        }
    }
    
    public function generateLandReport(Request $request)
    {
        // Similar to transaction report but for lands
    }
    
    public function generateUserReport(Request $request)
    {
        // Similar to transaction report but for users
    }
}