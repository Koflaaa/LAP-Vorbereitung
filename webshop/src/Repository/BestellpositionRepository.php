<?php

namespace Webshop\Repository; // Namensraum für alle Repository-Klassen (Datenbankzugriff)

require_once __DIR__ . '/../Core/Database.php'; // bindet die Datenbankverbindung manuell ein (kein Autoloader)
require_once __DIR__ . '/../Model/Bestellposition.php'; // bindet die Bestellposition-Datenklasse manuell ein (kein Autoloader)

use Webshop\Core\Database; // stellt die Datenbankverbindung bereit
use Webshop\Model\Bestellposition; // Datenklasse für eine Bestellposition

class BestellpositionRepository // kapselt den Datenbankzugriff auf die Tabelle "bestellposition"
{
    public function erstellen(Bestellposition $position): int // legt eine neue Bestellposition an und liefert die neue ID
    {
        $db = Database::getConnection(); // holt die gemeinsame Datenbankverbindung
        $stmt = $db->prepare( // bereitet die Einfüge-Anweisung vor (schützt vor SQL-Injection)
            'INSERT INTO bestellposition (bestellung_id, produkt_id, menge, einzelpreis)
             VALUES (:bestellung_id, :produkt_id, :menge, :einzelpreis)'
        );
        $stmt->execute([ // führt die Einfüge-Anweisung mit den Positionsdaten aus
            'bestellung_id' => $position->bestellungId, // zugehörige Bestellung übernehmen
            'produkt_id' => $position->produktId, // bestelltes Produkt übernehmen
            'menge' => $position->menge, // bestellte Stückzahl übernehmen
            'einzelpreis' => $position->einzelpreis, // Preis zum Bestellzeitpunkt übernehmen
        ]);

        return (int) $db->lastInsertId(); // liefert die vom DBMS vergebene neue ID
    }

    public function findByBestellung(int $bestellungId): array // liefert alle Positionen einer Bestellung
    {
        $db = Database::getConnection(); // holt die gemeinsame Datenbankverbindung
        $stmt = $db->prepare('SELECT * FROM bestellposition WHERE bestellung_id = :id'); // bereitet die Abfrage vor
        $stmt->execute(['id' => $bestellungId]); // führt die Abfrage mit der übergebenen Bestellung-ID aus
        $zeilen = $stmt->fetchAll(); // holt alle Ergebniszeilen als Array

        return array_map(function (array $zeile): Bestellposition { // wandelt jede Zeile in ein Bestellposition-Objekt um
            return new Bestellposition(
                bestellpositionId: (int) $zeile['bestellposition_id'], // ID aus der DB in ein int umwandeln
                bestellungId: (int) $zeile['bestellung_id'], // Bestellung-ID umwandeln
                produktId: (int) $zeile['produkt_id'], // Produkt-ID umwandeln
                menge: (int) $zeile['menge'], // Menge umwandeln
                einzelpreis: (float) $zeile['einzelpreis'] // Preis zum Bestellzeitpunkt umwandeln
            );
        }, $zeilen);
    }
}
