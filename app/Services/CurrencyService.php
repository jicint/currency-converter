<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CurrencyService
{
    /**
     * Holt die Liste der Währungen – mit Caching für bessere Performance.
     */
    public function getCurrencies(): array
    {
        try {
            // Wir cachen die Währungsliste für 24 Stunden, um API-Requests zu sparen.
            return Cache::remember('currency_list', 86400, function () {
                $response = Http::withoutVerifying()->get("https://api.frankfurter.app/currencies");
                
                if ($response->successful()) {
                    return $response->json();
                }

                throw new \Exception("API returned error status");
            });
        } catch (\Exception $e) {
            Log::warning("Währungsliste konnte nicht geladen werden, nutze Fallback: " . $e->getMessage());
            
            return [
                'EUR' => 'Euro',
                'USD' => 'US Dollar',
                'GBP' => 'British Pound',
                'JPY' => 'Japanese Yen'
            ];
        }
    }

    /**
     * Führt die eigentliche Umrechnung durch.
     */
    public function convert(mixed $amount, string $from, string $to): ?float
    {
        // Sicherheitshalber: Wenn Start und Ziel gleich sind
        if ($from === $to) {
            return (float) $amount;
        }

        try {
            $response = Http::withoutVerifying()->get("https://api.frankfurter.app/latest", [
                'amount' => $amount,
                'from'   => $from,
                'to'     => $to,
            ]);

            if ($response->successful()) {
                return (float) ($response->json()['rates'][$to] ?? null);
            }

            Log::error("API Fehler bei Umrechnung: " . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::critical("Kritischer Fehler im CurrencyService: " . $e->getMessage());
            return null;
        }
    }
}