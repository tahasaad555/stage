<!-- resources/views/reports/index.blade.php -->
@extends('layouts.app')

@section('title', 'Rapports')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="h3">Rapports</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-light">
                <h5 class="mb-0">Rapport des Transactions</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('reports.transactions') }}" method="GET">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Date de début</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Date de fin</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Format</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="format" id="format_pdf" value="pdf" checked>
                            <label class="form-check-label" for="format_pdf">PDF</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="format" id="format_csv" value="csv">
                            <label class="form-check-label" for="format_csv">CSV</label>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Générer le rapport</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-light">
                <h5 class="mb-0">Rapport des Terres Agricoles</h5>
            </div>
            <div class="card-body">
                <!-- Similar form for land reports -->
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-light">
                <h5 class="mb-0">Rapport des Utilisateurs</h5>
            </div>
            <div class="card-body">
                <!-- Similar form for user reports -->
            </div>
        </div>
    </div>
</div>
@endsection