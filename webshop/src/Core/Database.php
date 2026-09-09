<?php

namespace Webshop\Core; // Namensraum, unter dem diese Klasse per Autoloading gefunden wird

use PDO; // PHP-eigene Klasse für Datenbankverbindungen

class Database // Klasse, die die Datenbankverbindung bereitstellt
{
    private static ?PDO $connection = null; // hält die eine gemeinsam genutzte Verbindung (oder null, solange keine besteht)

    public static function getConnection(): PDO // liefert die Verbindung, ohne dass eine neue Instanz der Klasse nötig ist
    {
        if (self::$connection === null) { // nur beim allerersten Aufruf eine Verbindung aufbauen
            $config = require __DIR__ . '/../../config/config.php'; // lädt die Zugangsdaten aus der Konfigurationsdatei
            $db = $config['db']; // greift auf den Teilbereich "db" der Konfiguration zu

            $dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['name']};charset={$db['charset']}"; // baut die Verbindungsadresse für PDO zusammen

            self::$connection = new PDO($dsn, $db['user'], $db['pass'], [ // baut die eigentliche Verbindung auf und speichert sie
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Fehler sollen als Exception geworfen werden statt stillzuschweigen
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Ergebniszeilen als assoziatives Array (Spaltenname => Wert) liefern
            ]);
        }

        return self::$connection; // gibt die (neue oder bereits bestehende) Verbindung zurück
    }
}
