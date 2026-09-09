<?php

namespace Webshop\Core; // Namensraum, unter dem diese Klasse liegt

require_once __DIR__ . '/../Repository/KundeRepository.php'; // bindet das Kunde-Repository ein

use Webshop\Repository\KundeRepository; // Klasse für den Zugriff auf die Kundentabelle
use Webshop\Model\Kunde; // Datenklasse für einen Kunden

class Auth // kapselt das Auslesen des aktuell angemeldeten Kunden aus der Session
{
    public static function eingeloggterKunde(): ?Kunde // liefert den angemeldeten Kunden, oder null bei Gast-Nutzung
    {
        if (session_status() !== PHP_SESSION_ACTIVE) { // nur starten, wenn noch keine Session läuft
            session_start(); // startet bzw. setzt die Session fort
        }

        if (!isset($_SESSION['kunde_id'])) { // niemand ist eingeloggt
            return null; // null zurückgeben
        }

        return (new KundeRepository())->findById($_SESSION['kunde_id']); // eingeloggten Kunden aus der DB laden
    }
}
