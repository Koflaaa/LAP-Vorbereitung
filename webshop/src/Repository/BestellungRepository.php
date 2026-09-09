<?php

namespace Webshop\Repository; // Namensraum für alle Repository-Klassen (Datenbankzugriff)

require_once __DIR__ . '/../Core/Database.php'; // bindet die Datenbankverbindung manuell ein (kein Autoloader)
require_once __DIR__ . '/../Model/Bestellung.php'; // bindet die Bestellung-Datenklasse manuell ein (kein Autoloader)

use Webshop\Core\Database; // stellt die Datenbankverbindung bereit
use Webshop\Model\Bestellung; // Datenklasse für eine Bestellung

class BestellungRepository // kapselt den Datenbankzugriff auf die Tabelle "bestellung"
{
    public function erstellen(Bestellung $bestellung): int // legt eine neue Bestellung an und liefert die neue ID
    {
        $db = Database::getConnection(); // holt die gemeinsame Datenbankverbindung
        $stmt = $db->prepare( // bereitet die Einfüge-Anweisung vor (schützt vor SQL-Injection)
            'INSERT INTO bestellung (kunde_id, rechnungsadresse_id, lieferadresse_id, status, zahlungsart, gesamtbetrag)
             VALUES (:kunde_id, :rechnungsadresse_id, :lieferadresse_id, :status, :zahlungsart, :gesamtbetrag)'
        );
        $stmt->execute([ // führt die Einfüge-Anweisung mit den Bestelldaten aus
            'kunde_id' => $bestellung->kundeId, // bestellenden Kunden übernehmen
            'rechnungsadresse_id' => $bestellung->rechnungsadresseId, // Rechnungsadresse übernehmen
            'lieferadresse_id' => $bestellung->lieferadresseId, // Lieferadresse übernehmen
            'status' => $bestellung->status, // Bearbeitungsstatus übernehmen
            'zahlungsart' => $bestellung->zahlungsart, // gewählte Zahlungsart übernehmen
            'gesamtbetrag' => $bestellung->gesamtbetrag, // berechnete Gesamtsumme übernehmen
        ]);

        return (int) $db->lastInsertId(); // liefert die vom DBMS vergebene neue ID
    }

    public function findById(int $bestellungId): ?Bestellung // liefert eine einzelne Bestellung anhand ihrer ID
    {
        $db = Database::getConnection(); // holt die gemeinsame Datenbankverbindung
        $stmt = $db->prepare('SELECT * FROM bestellung WHERE bestellung_id = :id'); // bereitet die Abfrage vor
        $stmt->execute(['id' => $bestellungId]); // führt die Abfrage mit der übergebenen ID aus
        $zeile = $stmt->fetch(); // holt die eine Ergebniszeile (oder false, wenn keine gefunden wurde)

        if (!$zeile) { // wenn keine Bestellung gefunden wurde
            return null; // null zurückgeben
        }

        return new Bestellung( // Ergebniszeile in ein Bestellung-Objekt umwandeln
            bestellungId: (int) $zeile['bestellung_id'], // ID aus der DB in ein int umwandeln
            kundeId: (int) $zeile['kunde_id'], // Kunde-ID aus der DB in ein int umwandeln
            rechnungsadresseId: (int) $zeile['rechnungsadresse_id'], // Rechnungsadresse-ID umwandeln
            lieferadresseId: (int) $zeile['lieferadresse_id'], // Lieferadresse-ID umwandeln
            zahlungsart: $zeile['zahlungsart'], // Zahlungsart unverändert übernehmen
            status: $zeile['status'], // Status unverändert übernehmen
            gesamtbetrag: (float) $zeile['gesamtbetrag'], // Gesamtbetrag in eine Kommazahl umwandeln
            bestelldatum: $zeile['bestelldatum'] // Bestelldatum unverändert übernehmen
        );
    }
}
