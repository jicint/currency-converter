# Globaler Waehrungsrechner (Laravel 11)

Eine robuste Laravel 11-Anwendung, die Echtzeit-Waehrungsumrechnungen durch die Integration der Frankfurter Finanz-API ermoeglicht. Entwickelt mit Fokus auf Testbarkeit, Wartbarkeit und saubere Architektur.

## Technische Architektur
Dieses Projekt nutzt das **Service-Oriented Architecture (SOA)** Muster. Die Logik wurde bewusst aus dem Controller in einen dedizierten Service extrahiert.

* **Controller:** Verarbeitet Benutzereingaben und validiert Daten.
* **Service-Ebene (`CurrencyService`):** Kapselt die gesamte HTTP-Kommunikation mit der Frankfurter API.
* **Frontend:** Umsetzung mit Bootstrap 5 fuer ein responsives Design.

## Dateistruktur und Zustaendigkeiten

| Dateipfad | Aufgabe |
|-----------|---------|
| app/Services/CurrencyService.php | Logik fuer API-Abfragen und Waehrungsumrechnung. |
| app/Http/Controllers/CurrencyController.php | Steuerung des Datenflusses und Fehlerbehandlung. |
| resources/views/converter.blade.php | Benutzeroberflaeche (UI) inkl. Validierungsfehlern. |
| routes/web.php | Definition der Web-Endpunkte. |

## Installation

1. Projekt klonen und Abhaengigkeiten installieren:
   composer install

2. Umgebung konfigurieren:
   cp .env.example .env
   php artisan key:generate

3. Server starten:
   php artisan serve --port=7777

## Sicherheit & Konfiguration
Die Anwendung folgt Best-Practices fuer Sicherheit. Sensible Daten werden ausschliesslich ueber die `.env` Datei verwaltet. Die `.env.example` dient als Vorlage fuer neue Umgebungen.

Erstellt im Januar 2026 bei Jicin Thekkekara als Programmieraufgabe.
