<?php

namespace Webshop\Repository; // Namensraum für alle Repository-Klassen (Datenbankzugriff)

require_once __DIR__ . '/../Core/Database.php'; // bindet die Datenbankverbindung manuell ein (kein Autoloader)
require_once __DIR__ . '/../Model/Kategorie.php'; // bindet die Kategorie-Datenklasse manuell ein (kein Autoloader)

use Webshop\Core\Database; // stellt die Datenbankverbindung bereit
use Webshop\Model\Kategorie; // Datenklasse, in die die Ergebniszeile umgewandelt wird

class KategorieRepository // kapselt den Lesezugriff auf die Tabelle "kategorie"
{
    public function findAlle(): array // liefert alle Kategorien, z. B. für ein Auswahlfeld im Formular
    {
        $db = Database::getConnection(); // holt die gemeinsame Datenbankverbindung
        $stmt = $db->query('SELECT * FROM kategorie ORDER BY bezeichnung'); // fragt alle Kategorien sortiert nach Name ab
        $zeilen = $stmt->fetchAll(); // holt alle Ergebniszeilen als Array

        return array_map(fn (array $zeile) => new Kategorie( // wandelt jede Zeile in ein Kategorie-Objekt um
            kategorieId: (int) $zeile['kategorie_id'], // ID aus der DB in ein int umwandeln
            bezeichnung: $zeile['bezeichnung'] // Name unverändert übernehmen
        ), $zeilen);
    }

    public function findById(int $kategorieId): ?Kategorie // liefert eine einzelne Kategorie anhand ihrer ID
    {
        $db = Database::getConnection(); // holt die gemeinsame Datenbankverbindung
        $stmt = $db->prepare('SELECT * FROM kategorie WHERE kategorie_id = :id'); // bereitet die Abfrage vor (schützt vor SQL-Injection)
        $stmt->execute(['id' => $kategorieId]); // führt die Abfrage mit der übergebenen ID aus
        $zeile = $stmt->fetch(); // holt die eine Ergebniszeile (oder false, wenn keine gefunden wurde)

        if (!$zeile) { // wenn keine Kategorie gefunden wurde
            return null; // null zurückgeben
        }

        return new Kategorie( // Ergebniszeile in ein Kategorie-Objekt umwandeln
            kategorieId: (int) $zeile['kategorie_id'], // ID aus der DB in ein int umwandeln
            bezeichnung: $zeile['bezeichnung'] // Name unverändert übernehmen
        );
    }
}
