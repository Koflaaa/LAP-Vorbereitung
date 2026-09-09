<?php

require_once __DIR__ . '/../src/Repository/KundeRepository.php'; // bindet das Kunde-Repository ein

use Webshop\Repository\KundeRepository; // Klasse für den Zugriff auf die Kundentabelle

if (session_status() !== PHP_SESSION_ACTIVE) { // nur starten, wenn noch keine Session läuft
    session_start(); // startet bzw. setzt die Session fort
}

$fehler = []; // Liste der Validierungsfehler

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // das Formular wurde abgeschickt
    $kundeRepo = new KundeRepository(); // erstellt das Kunde-Repository
    $kunde = $kundeRepo->findByEmail(trim($_POST['email'] ?? '')); // Kunden anhand der E-Mail suchen

    if ($kunde === null || $kunde->passwortHash === null || !password_verify($_POST['passwort'] ?? '', $kunde->passwortHash)) { // E-Mail unbekannt, Gastkonto ohne Passwort, oder Passwort falsch
        $fehler[] = 'E-Mail-Adresse oder Passwort ist falsch.'; // bewusst unspezifische Meldung (verrät nicht, ob die E-Mail existiert)
    } else {
        $_SESSION['kunde_id'] = $kunde->kundeId; // Kunden einloggen
        header('Location: index.php'); // zur Produktübersicht weiterleiten
        exit; // Skript nach der Umleitung beenden
    }
}

require __DIR__ . '/../templates/login.php'; // bindet die Ansicht ein und stellt ihr $fehler zur Verfügung
