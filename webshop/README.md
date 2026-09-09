# Webshop — LAP-Projekt

Umsetzung der Aufgabenstellung aus [`../Aufgabe_Webshop.md`](../Aufgabe_Webshop.md).

**Stack:** PHP (OOP, manuelles `require_once` statt Composer/Autoloader) + MySQL/MariaDB.

## Struktur

```
webshop/
  docs/           ER-Diagramm, Zeitschätzung, weitere Dokumentation
  sql/            schema.sql — Datenbankschema
  config/         config.example.php als Vorlage, config.php lokal anlegen (nicht versioniert)
  src/
    Core/         Infrastruktur (DB-Verbindung, ...)
    Model/        Entitätsklassen (Produkt, Kunde, Adresse, Bestellung, ...)
    Repository/   DB-Zugriff pro Entität (Repository-Pattern)
  public/         Webroot: Controller (index.php, produkt.php, warenkorb.php, kasse.php, zahlung.php, statistik.php, ...) + assets/ (CSS, Produktbilder)
  templates/      HTML-Views, werden von den Controllern in public/ eingebunden
```

Kein Composer/Autoloader: jede Datei bindet ihre Abhängigkeiten selbst per `require_once` ein (z. B. bindet `ProduktRepository.php` `Database.php` und `Produkt.php` ein). Neue Klassen müssen daher dort verlinkt werden, wo sie verwendet werden.

## Setup (lokal)

1. `config/config.example.php` nach `config/config.php` kopieren, DB-Zugangsdaten eintragen
2. `sql/schema.sql` in MySQL/MariaDB einspielen
3. optional zum Testen: `sql/seed.sql` einspielen (4 Beispielprodukte, 1 Kunde, 2 Bestellungen — kein Ersatz für die finalen Testdaten)
4. PHP-Built-in-Server: `php -S localhost:8000 -t public`

## Stand

- [x] ER-Diagramm + Zeitschätzung ([docs/](docs/))
- [x] Projektgerüst (OOP-Grundstruktur, DB-Verbindung, Beispiel-Repository)
- [x] Produktliste + Detailansicht (Punkt 2) — `public/index.php`, `public/produkt.php`
- [x] Warenkorb (Punkt 3a) — `public/warenkorb.php`, `src/Core/Warenkorb.php`
- [x] Kundendaten für Rechnungs-/Lieferadresse (Punkt 3b) — `public/kasse.php`
- [x] Zahlungsart, Bestellabschluss (Punkt 3c) — `public/zahlung.php`, `public/bestellbestaetigung.php`
- [x] Responsives Layout (Punkt 4) — `public/assets/css/style.css`
- [ ] UI/UX (Punkt 5)
- [x] Statistikseite (Punkt 6) — `public/statistik.php`
- [ ] Anforderungsliste Hosting (Punkt 7)
- [ ] Zusatzaufgaben a–c
