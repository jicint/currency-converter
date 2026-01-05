<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CurrencyService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CurrencyController extends Controller
{
    protected CurrencyService $currencyService;


    public function __construct(CurrencyService $currencyService) 
    { 
        $this->currencyService = $currencyService; 
    }

    /**
     * Zeigt das Hauptformular an.
     */
    public function index(): View 
    {
        $currencies = $this->currencyService->getCurrencies();
        return view('converter', compact('currencies'));
    }

    /**
     * Verarbeitet die Umrechnung.
     */
    public function convert(Request $request) 
    {
        // Validierung 
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'from' => 'required|string',
            'to' => 'required|string',
        ], [
            'amount.min' => 'Der Betrag muss mindestens 0.01 sein.',
            'amount.required' => 'Bitte geben Sie einen Betrag ein.'
        ]);

        // Logik-Check: Gleiche Währungen verhindern
        if ($request->from === $request->to) {
            return redirect('/')
                ->withInput() // Behält die Eingaben des Nutzers im Formular
                ->with('error', 'Ursprungs- und Zielwährung müssen sich unterscheiden!');
        }

        // Die eigentliche Umrechnung über den Service
        $convertedAmount = $this->currencyService->convert(
            $request->amount, 
            $request->from, 
            $request->to
        );

        // Falls die API fehlschlägt (null zurückgibt)
        if ($convertedAmount === null) {
            return redirect('/')->with('error', 'Der Umrechnungsdienst ist derzeit nicht erreichbar.');
        }

        $currencies = $this->currencyService->getCurrencies();

        return view('converter', [
            'result' => $convertedAmount, 
            'amount' => $request->amount, 
            'from' => $request->from, 
            'to' => $request->to, 
            'currencies' => $currencies
        ]);
    }
}