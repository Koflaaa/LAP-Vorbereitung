<?php

namespace Webshop\Repository; // Namensraum für alle Repository-Klassen (Datenbankzugriff)

require_once __DIR__ . '/../Core/Database.php'; // bindet die Datenbankverbindung manuell ein (kein Autoloader)
require_once __DIR__ . '/../Model/Kunde.php'; // bindet die Kunde-Datenklasse manuell ein (kein Autoloader)

use Webshop\Core\Database; // stellt die Datenbankverbindung bereit
use Webshop\Model\Kunde; // Datenklasse für einen Kunden

class KundeRepository // kapselt den Datenbankzugriff auf die Tabelle "kunde"
{
    public function erstellen(Kunde $kunde): int // legt einen neuen Kunden an und liefert die neue ID
    {
        $db = Database::getConnection(); // holt die gemeinsame Datenbankverbindung
        $stmt = $db->prepare( // bereitet die Einfüge-Anweisung vor (schützt vor SQL-Injection)
            'INSERT INTO kunde (vorname, nachname, email, telefon, passwort_hash)
             VALUES (:vorname, :nachname, :email, :telefon, :passwort_hash)'
        );
        $stmt->execute([ // führt die Einfüge-Anweisung mit den Kundendaten aus
            'vorname' => $kunde->vorname, // Vorname übernehmen
            'nachname' => $kunde->nachname, // Nachname übernehmen
            'email' => $kunde->email, // E-Mail-Adresse übernehmen
            'telefon' => $kunde->telefon, // Telefonnummer übernehmen
            'passwort_hash' => $kunde->passwortHash, // null bei Gastbestellung
        ]);

        return (int) $db->lastInsertId(); // liefert die vom DBMS vergebene neue ID
    }

    public function findByEmail(string $email): ?Kunde // liefert einen Kunden anhand seiner E-Mail-Adresse (für Login/Registrierung)
    {
        $db = Database::getConnection(); // holt die gemeinsame Datenbankverbindung
        $stmt = $db->prepare('SELECT * FROM kunde WHERE email = :email'); // bereitet die Abfrage vor
        $stmt->execute(['email' => $email]); // führt die Abfrage mit der übergebenen E-Mail aus
        $zeile = $stmt->fetch(); // holt die eine Ergebniszeile (oder false, wenn keine gefunden wurde)

        return $zeile ? $this->hydrate($zeile) : null; // wandelt die Zeile in ein Kunde-Objekt um, sonst null
    }

    public function findById(int $kundeId): ?Kunde // liefert einen Kunden anhand seiner ID (für den eingeloggten Nutzer)
    {
        $db = Database::getConnection(); // holt die gemeinsame Datenbankverbindung
        $stmt = $db->prepare('SELECT * FROM kunde WHERE kunde_id = :id'); // bereitet die Abfrage vor
        $stmt->execute(['id' => $kundeId]); // führt die Abfrage mit der übergebenen ID aus
        $zeile = $stmt->fetch(); // holt die eine Ergebniszeile (oder false, wenn keine gefunden wurde)

        return $zeile ? $this->hydrate($zeile) : null; // wandelt die Zeile in ein Kunde-Objekt um, sonst null
    }

    private function hydrate(array $zeile): Kunde // wandelt eine DB-Zeile (Array) in ein Kunde-Objekt um
    {
        return new Kunde(
            kundeId: (int) $zeile['kunde_id'], // ID aus der DB in ein int umwandeln
            vorname: $zeile['vorname'], // Vorname unverändert übernehmen
            nachname: $zeile['nachname'], // Nachname unverändert übernehmen
            email: $zeile['email'], // E-Mail unverändert übernehmen
            telefon: $zeile['telefon'], // Telefonnummer unverändert übernehmen
            passwortHash: $zeile['passwort_hash'] // gehashtes Passwort unverändert übernehmen (oder null bei Gastkonto)
        );
    }
}
