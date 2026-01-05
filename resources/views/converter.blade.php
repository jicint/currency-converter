<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Currency Converter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; }
        .card { border: none; border-radius: 15px; }
        .btn-primary { background-color: #4e73df; border: none; border-radius: 10px; padding: 12px; }
        .form-select, .form-control { border-radius: 10px; }
    </style>
</head>
<body>
    <div class="container mt-5" style="max-width: 600px;">
        
        @if(session('error'))
            <div class="alert alert-danger shadow-sm border-0 mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="card shadow-lg p-4 p-md-5">
            <h2 class="text-center fw-bold mb-4">Währungsumrechner</h2>
            
            <form action="/convert" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="form-label fw-semibold">Betrag</label>
                    <input type="number" name="amount" step="0.01" min="0.01" 
                           class="form-control @error('amount') is-invalid @enderror" 
                           value="{{ old('amount', $amount ?? 1) }}" required>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Von</label>
                        <select name="from" class="form-select">
                            @foreach($currencies as $code => $name)
                                <option value="{{ $code }}" {{ (old('from', $from ?? '') == $code || (!isset($from) && $code == 'EUR')) ? 'selected' : '' }}>
                                    {{ $code }} - {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nach</label>
                        <select name="to" class="form-select">
                            @foreach($currencies as $code => $name)
                                <option value="{{ $code }}" {{ (old('to', $to ?? '') == $code || (!isset($to) && $code == 'USD')) ? 'selected' : '' }}>
                                    {{ $code }} - {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">
                    Jetzt umrechnen
                </button>
            </form>

            @if(isset($result))
                <div class="mt-5 p-4 text-center bg-light rounded-3 border">
                    <p class="text-muted mb-1 small uppercase fw-bold">Ergebnis:</p>
                    <h3 class="text-primary fw-bold mb-0">
                        {{ number_format((float)$amount, 2, ',', '.') }} {{ $from }} = 
                        <span class="text-success">{{ number_format((float)$result, 2, ',', '.') }} {{ $to }}</span>
                    </h3>
                </div>
            @endif
        </div>

        <p class="text-center text-muted mt-4 small">
            Datenquelle: Frankfurter API | Erstellt mit Laravel 11
        </p>
    </div>
</body>
</html>