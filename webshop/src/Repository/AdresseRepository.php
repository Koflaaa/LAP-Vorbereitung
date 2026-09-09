<?php

namespace Webshop\Repository; // Namensraum für alle Repository-Klassen (Datenbankzugriff)

require_once __DIR__ . '/../Core/Database.php'; // bindet die Datenbankverbindung manuell ein (kein Autoloader)
require_once __DIR__ . '/../Model/Adresse.php'; // bindet die Adresse-Datenklasse manuell ein (kein Autoloader)

use Webshop\Core\Database; // stellt die Datenbankverbindung bereit
use Webshop\Model\Adresse; // Datenklasse für eine Adresse

class AdresseRepository // kapselt den Datenbankzugriff auf die Tabelle "adresse"
{
    public function erstellen(Adresse $adresse): int // legt eine neue Adresse an und liefert die neue ID
    {
        $db = Database::getConnection(); // holt die gemeinsame Datenbankverbindung
        $stmt = $db->prepare( // bereitet die Einfüge-Anweisung vor (schützt vor SQL-Injection)
            'INSERT INTO adresse (kunde_id, typ, strasse, hausnummer, plz, ort, land)
             VALUES (:kunde_id, :typ, :strasse, :hausnummer, :plz, :ort, :land)'
        );
        $stmt->execute([ // führt die Einfüge-Anweisung mit den Adressdaten aus
            'kunde_id' => $adresse->kundeId, // zugehörigen Kunden übernehmen
            'typ' => $adresse->typ, // "rechnung" oder "lieferung"
            'strasse' => $adresse->strasse, // Straße übernehmen
            'hausnummer' => $adresse->hausnummer, // Hausnummer übernehmen
            'plz' => $adresse->plz, // Postleitzahl übernehmen
            'ort' => $adresse->ort, // Ort übernehmen
            'land' => $adresse->land, // Land übernehmen
        ]);

        return (int) $db->lastInsertId(); // liefert die vom DBMS vergebene neue ID
    }

    public function findLetzteVonKunde(int $kundeId, string $typ): ?Adresse // liefert die zuletzt gespeicherte Adresse eines Kunden für Vorbefüllung
    {
        $db = Database::getConnection(); // holt die gemeinsame Datenbankverbindung
        $stmt = $db->prepare( // bereitet die Abfrage vor (schützt vor SQL-Injection)
            'SELECT * FROM adresse WHERE kunde_id = :kunde_id AND typ = :typ ORDER BY adresse_id DESC LIMIT 1'
        );
        $stmt->execute(['kunde_id' => $kundeId, 'typ' => $typ]); // führt die Abfrage mit Kunde und Adresstyp aus
        $zeile = $stmt->fetch(); // holt die eine Ergebniszeile (oder false, wenn keine gefunden wurde)

        if (!$zeile) { // wenn keine passende Adresse gefunden wurde
            return null; // null zurückgeben
        }

        return new Adresse( // Ergebniszeile in ein Adresse-Objekt umwandeln
            adresseId: (int) $zeile['adresse_id'], // ID aus der DB in ein int umwandeln
            kundeId: (int) $zeile['kunde_id'], // Kunde-ID umwandeln
            typ: $zeile['typ'], // Typ unverändert übernehmen
            strasse: $zeile['strasse'], // Straße unverändert übernehmen
            hausnummer: $zeile['hausnummer'], // Hausnummer unverändert übernehmen
            plz: $zeile['plz'], // Postleitzahl unverändert übernehmen
            ort: $zeile['ort'], // Ort unverändert übernehmen
            land: $zeile['land'] // Land unverändert übernehmen
        );
    }
}
