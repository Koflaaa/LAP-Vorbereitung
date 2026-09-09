<?php

require_once __DIR__ . '/../src/Model/Kunde.php'; // bindet die Kunde-Datenklasse ein
require_once __DIR__ . '/../src/Repository/KundeRepository.php'; // bindet das Kunde-Repository ein

use Webshop\Model\Kunde; // Datenklasse für einen Kunden
use Webshop\Repository\KundeRepository; // Klasse für den Zugriff auf die Kundentabelle

if (session_status() !== PHP_SESSION_ACTIVE) { // nur starten, wenn noch keine Session läuft
    session_start(); // startet bzw. setzt die Session fort
}

$fehler = []; // Liste der Validierungsfehler
$eingabe = $_POST; // zuletzt eingegebene Werte, damit das Formular bei Fehlern nicht leer ist

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // das Formular wurde abgeschickt
    $pflichtfelder = ['vorname', 'nachname', 'email', 'telefon', 'passwort', 'passwort_wiederholen']; // erforderliche Felder
    foreach ($pflichtfelder as $feld) { // jedes Pflichtfeld prüfen
        if (trim($_POST[$feld] ?? '') === '') { // Feld fehlt oder ist leer
            $fehler[] = "Feld \"$feld\" ist erforderlich."; // Fehlermeldung sammeln
        }
    }

    if (!filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL)) { // E-Mail-Format prüfen
        $fehler[] = 'Die E-Mail-Adresse ist ungültig.'; // Fehlermeldung sammeln
    }

    if (strlen($_POST['passwort'] ?? '') < 8) { // Mindestlänge des Passworts prüfen
        $fehler[] = 'Das Passwort muss mindestens 8 Zeichen lang sein.'; // Fehlermeldung sammeln
    }

    if (($_POST['passwort'] ?? '') !== ($_POST['passwort_wiederholen'] ?? '')) { // beide Passwort-Felder müssen übereinstimmen
        $fehler[] = 'Die Passwörter stimmen nicht überein.'; // Fehlermeldung sammeln
    }

    $kundeRepo = new KundeRepository(); // erstellt das Kunde-Repository
    if (empty($fehler) && $kundeRepo->findByEmail(trim($_POST['email'])) !== null) { // prüfen, ob die E-Mail schon registriert ist
        $fehler[] = 'Für diese E-Mail-Adresse existiert bereits ein Konto.'; // Fehlermeldung sammeln
    }

    if (empty($fehler)) { // nur anlegen, wenn keine Fehler aufgetreten sind
        $kunde = new Kunde( // neues Kunde-Objekt mit Konto aufbauen
            kundeId: null, // noch nicht gespeichert
            vorname: trim($_POST['vorname']), // Vorname übernehmen
            nachname: trim($_POST['nachname']), // Nachname übernehmen
            email: trim($_POST['email']), // E-Mail übernehmen
            telefon: trim($_POST['telefon']), // Telefonnummer übernehmen
            passwortHash: password_hash($_POST['passwort'], PASSWORD_DEFAULT) // Passwort sicher gehasht speichern, nie im Klartext
        );
        $kundeId = $kundeRepo->erstellen($kunde); // Kunden in der DB anlegen, ID zurückbekommen

        $_SESSION['kunde_id'] = $kundeId; // neuen Kunden direkt einloggen

        header('Location: index.php'); // zur Produktübersicht weiterleiten
        exit; // Skript nach der Umleitung beenden
    }
}

require __DIR__ . '/../templates/registrieren.php'; // bindet die Ansicht ein und stellt ihr $fehler und $eingabe zur Verfügung
