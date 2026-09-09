# ER-Diagramm — Webshop

## Entitäten und Beziehungen

```mermaid
%% erDiagram startet ein Entity-Relationship-Diagramm
erDiagram
    %% eine Kategorie enthält null bis viele Produkte
    KATEGORIE ||--o{ PRODUKT : enthaelt
    %% ein Kunde besitzt null bis viele Adressen
    KUNDE ||--o{ ADRESSE : besitzt
    %% ein Kunde tätigt null bis viele Bestellungen
    KUNDE ||--o{ BESTELLUNG : taetigt
    %% jede Bestellung verweist auf genau eine Rechnungsadresse
    BESTELLUNG }o--|| ADRESSE : rechnungsadresse
    %% jede Bestellung verweist auf genau eine Lieferadresse
    BESTELLUNG }o--|| ADRESSE : lieferadresse
    %% eine Bestellung enthält null bis viele Bestellpositionen
    BESTELLUNG ||--o{ BESTELLPOSITION : enthaelt
    %% ein Produkt kann in null bis vielen Bestellpositionen vorkommen
    PRODUKT ||--o{ BESTELLPOSITION : wird_bestellt

    %% Attribute der Entität KATEGORIE
    KATEGORIE {
        int kategorie_id PK
        %% Primärschlüssel der Kategorie
        varchar bezeichnung
        %% Name der Kategorie
    }

    %% Attribute der Entität PRODUKT
    PRODUKT {
        int produkt_id PK
        %% Primärschlüssel des Produkts
        int kategorie_id FK
        %% Fremdschlüssel zur Kategorie
        varchar bezeichnung
        %% Produktname
        text beschreibung
        %% ausführliche Beschreibung
        decimal preis
        %% Verkaufspreis
        varchar bild_pfad
        %% Pfad zum Produktbild im Dateisystem
        int lagerbestand
        %% verfügbare Stückzahl
        boolean aktiv
        %% false = deaktiviert (Soft-Delete)
        datetime erstellt_am
        %% Anlagezeitpunkt
    }

    %% Attribute der Entität KUNDE
    KUNDE {
        int kunde_id PK
        %% Primärschlüssel des Kunden
        varchar vorname
        %% Vorname
        varchar nachname
        %% Nachname
        varchar email UK
        %% eindeutige E-Mail-Adresse
        varchar telefon
        %% Telefonnummer
        varchar passwort_hash "NULL = Gastbestellung"
        %% gehashtes Passwort, nur bei Konto vorhanden
        datetime erstellt_am
        %% Anlagezeitpunkt
    }

    %% Attribute der Entität ADRESSE
    ADRESSE {
        int adresse_id PK
        %% Primärschlüssel der Adresse
        int kunde_id FK
        %% Fremdschlüssel zum Kunden
        enum typ "rechnung | lieferung"
        %% unterscheidet Rechnungs- von Lieferadresse
        varchar strasse
        %% Straßenname
        varchar hausnummer
        %% Hausnummer
        varchar plz
        %% Postleitzahl
        varchar ort
        %% Ort/Stadt
        varchar land
        %% Land
    }

    %% Attribute der Entität BESTELLUNG
    BESTELLUNG {
        int bestellung_id PK
        %% Primärschlüssel der Bestellung
        int kunde_id FK
        %% Fremdschlüssel zum Kunden
        int rechnungsadresse_id FK
        %% Fremdschlüssel zur Rechnungsadresse
        int lieferadresse_id FK
        %% Fremdschlüssel zur Lieferadresse
        datetime bestelldatum
        %% Zeitpunkt der Bestellung
        enum status "offen|bezahlt|versandt|storniert"
        %% aktueller Bearbeitungsstatus
        enum zahlungsart "rechnung|kreditkarte"
        %% gewählte Zahlungsart
        decimal gesamtbetrag
        %% Summe aller Positionen zum Bestellzeitpunkt
    }

    %% Attribute der Entität BESTELLPOSITION
    BESTELLPOSITION {
        int bestellposition_id PK
        %% Primärschlüssel der Position
        int bestellung_id FK
        %% Fremdschlüssel zur Bestellung
        int produkt_id FK
        %% Fremdschlüssel zum Produkt
        int menge
        %% bestellte Stückzahl
        decimal einzelpreis "Preis zum Bestellzeitpunkt"
        %% Preis pro Stück, unabhängig vom aktuellen Produktpreis
    }
```

## Designentscheidungen

- **Einzelpreis in `BESTELLPOSITION` statt Verweis auf `PRODUKT.preis`**: Produktpreise können sich ändern; für eine korrekte Rechnung (Zusatzaufgabe c) muss der historische Preis zum Bestellzeitpunkt erhalten bleiben.
- **`ADRESSE` getrennt von `KUNDE`, zwei FKs in `BESTELLUNG`**: Rechnungs- und Lieferadresse können unterschiedlich sein und sich unabhängig von den Stammdaten des Kunden ändern; jede Bestellung referenziert die zum Bestellzeitpunkt gültige Adresse.
- **`passwort_hash` nullable**: erlaubt sowohl Gastbestellung als auch optionales Kundenkonto (Zusatzaufgabe b). Wird ein Konto angelegt, wird das Passwort gehasht (`password_hash()`), nie im Klartext gespeichert.
- **`bild_pfad`**: Bilddateien liegen im Dateisystem (`public/assets/img/produkte/`), nur der relative Pfad wird in der DB gespeichert (Vorgabe Punkt 2c).
- **`aktiv` (Soft-Delete) bei `PRODUKT`**: Damit bestehende Bestellpositionen älterer Bestellungen referenziell gültig bleiben, werden Produkte beim "Löschen" (Zusatzaufgabe a) nur deaktiviert statt physisch entfernt.
- **Kein eigenes `WARENKORB`-Tabellenobjekt**: Der Warenkorb wird serverseitig in der PHP-Session gehalten (Produkt-ID + Menge). Das ist ausreichend, da die Aufgabenstellung keine geräteübergreifende Persistenz des Warenkorbs fordert — nur die Kundendaten sollen bei einem Konto wiederverwendbar sein.
- **Statistik-Anforderungen (Punkt 6)** werden direkt über Aggregat-Queries auf `BESTELLPOSITION`/`BESTELLUNG` abgebildet, keine zusätzlichen Tabellen nötig:
  - Meist-/seltenbestellte Produkte: `SUM(menge)` gruppiert nach `produkt_id`, sortiert auf-/absteigend, `LIMIT 5`.
  - Bestellungen der letzten 4 Wochen: `WHERE bestelldatum >= NOW() - INTERVAL 4 WEEK`.
