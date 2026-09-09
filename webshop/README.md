# Webshop — LAP-Projekt

Umsetzung der Aufgabenstellung aus [`../Aufgabe_Webshop.md`](../Aufgabe_Webshop.md).

**Stack:** PHP (OOP, manuelles `require_once` statt Composer/Autoloader) + MySQL/MariaDB.

## Struktur

```
webshop/
  docs/           ER-Diagramm, Zeitschätzung, Anforderungsliste Hosting, weitere Dokumentation
  sql/            schema.sql — Datenbankschema, seed.sql — Testdaten
  config/         config.example.php als Vorlage, config.php lokal anlegen (nicht versioniert)
  src/
    Core/         Infrastruktur (DB-Verbindung, Warenkorb-Session, Auth/Login-Status)
    Model/        Entitätsklassen (Produkt, Kunde, Adresse, Bestellung, Bestellposition, Kategorie)
    Repository/   DB-Zugriff pro Entität (Repository-Pattern)
  public/         Webroot: Controller + assets/ (CSS, Produktbilder)
  templates/      HTML-Views, werden von den Controllern in public/ eingebunden; templates/partials/nav.php ist die gemeinsame Navigation
```

Kein Composer/Autoloader: jede Datei bindet ihre Abhängigkeiten selbst per `require_once` ein (z. B. bindet `ProduktRepository.php` `Database.php` und `Produkt.php` ein). Neue Klassen müssen daher dort verlinkt werden, wo sie verwendet werden.

## Seitenübersicht

| Seite | Zweck |
|---|---|
| `index.php` | Produktliste |
| `produkt.php?id=` | Produktdetail + "In den Warenkorb" |
| `warenkorb.php` | Warenkorb ansehen/ändern |
| `kasse.php` | Kundendaten + Adressen (nutzt gespeicherte Daten bei Login) |
| `zahlung.php` | Zahlungsart wählen, Bestellung abschließen |
| `bestellbestaetigung.php?id=` | Bestätigung nach Bestellabschluss |
| `rechnung.php?id=` | Rechnung im HTML-Format, druckbar |
| `statistik.php` | Meist-/seltenbestellte Produkte, Bestellungen der letzten 4 Wochen |
| `produktverwaltung.php` / `produkt_formular.php` / `produkt_loeschen.php` | Produkt-CRUD (Zusatzaufgabe a) |
| `registrieren.php` / `login.php` / `logout.php` | Kundenkonto (Zusatzaufgabe b) |

## Setup (lokal)

1. `config/config.example.php` nach `config/config.php` kopieren, DB-Zugangsdaten eintragen
2. `sql/schema.sql` in MySQL/MariaDB einspielen
3. optional zum Testen: `sql/seed.sql` einspielen (4 Beispielprodukte, 1 Kunde, 2 Bestellungen — kein Ersatz für die finalen Testdaten)
4. sicherstellen, dass `public/assets/img/produkte/` für den Webserver beschreibbar ist (Produktbild-Upload, Zusatzaufgabe a)
5. PHP-Built-in-Server: `php -S localhost:8000 -t public`

## Stand

- [x] ER-Diagramm + Zeitschätzung ([docs/](docs/))
- [x] Projektgerüst (OOP-Grundstruktur, DB-Verbindung, Beispiel-Repository)
- [x] Produktliste + Detailansicht (Punkt 2) — `public/index.php`, `public/produkt.php`
- [x] Warenkorb (Punkt 3a) — `public/warenkorb.php`, `src/Core/Warenkorb.php`
- [x] Kundendaten für Rechnungs-/Lieferadresse (Punkt 3b) — `public/kasse.php`
- [x] Zahlungsart, Bestellabschluss (Punkt 3c) — `public/zahlung.php`, `public/bestellbestaetigung.php`
- [x] Responsives Layout (Punkt 4) — `public/assets/css/style.css`
- [x] UI/UX (Punkt 5) — gemeinsame Navigation (`templates/partials/nav.php`) mit Login-Status, einheitliches Farbschema/Hover-States in `style.css`
- [x] Statistikseite (Punkt 6) — `public/statistik.php`
- [x] Anforderungsliste Hosting (Punkt 7) — [docs/Anforderungsliste-Hosting.md](docs/Anforderungsliste-Hosting.md)
- [x] Zusatzaufgabe a: Produkt-CRUD — `public/produktverwaltung.php`, `public/produkt_formular.php` (inkl. Bild-Upload), `public/produkt_loeschen.php`
- [x] Zusatzaufgabe b: Kundenkonto — `public/registrieren.php`, `public/login.php`, `public/logout.php`, `src/Core/Auth.php`; `kasse.php` befüllt Formular bei Login automatisch vor
- [x] Zusatzaufgabe c: Rechnung (HTML) — `public/rechnung.php`, druckbar, verlinkt von der Bestellbestätigung

## Bewusst nicht umgesetzt (außerhalb der Angabe oder für später)

- Lagerbestand wird bei Bestellabschluss nicht automatisch reduziert
- Keine Passwort-Reset-Funktion
- Keine automatische Bestellbestätigung per E-Mail (nur Anzeige im Browser)
