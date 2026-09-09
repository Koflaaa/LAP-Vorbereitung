<?php

if (session_status() !== PHP_SESSION_ACTIVE) { // nur starten, wenn noch keine Session läuft
    session_start(); // muss vor jeder Ausgabe passieren, damit die Navigation den Login-Status per Cookie erkennen kann
}

require_once __DIR__ . '/../src/Model/Produkt.php'; // bindet die Produkt-Datenklasse ein
require_once __DIR__ . '/../src/Repository/ProduktRepository.php'; // bindet das Produkt-Repository ein
require_once __DIR__ . '/../src/Repository/KategorieRepository.php'; // bindet das Kategorie-Repository ein

use Webshop\Model\Produkt; // Datenklasse für ein Produkt
use Webshop\Repository\ProduktRepository; // Klasse für den Zugriff auf die Produkttabelle
use Webshop\Repository\KategorieRepository; // Klasse für den Zugriff auf die Kategorietabelle

$erlaubteBildtypen = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp']; // erlaubte MIME-Typen samt Dateiendung
$bildOrdner = __DIR__ . '/assets/img/produkte/'; // Zielordner für hochgeladene Produktbilder auf dem Dateisystem

$produktRepo = new ProduktRepository(); // erstellt das Produkt-Repository
$kategorieRepo = new KategorieRepository(); // erstellt das Kategorie-Repository
$kategorien = $kategorieRepo->findAlle(); // lädt alle Kategorien für das Auswahlfeld

$produktId = (int) ($_GET['id'] ?? $_POST['produkt_id'] ?? 0); // ID aus URL (GET) oder Formular (POST) lesen, 0 = neues Produkt
$produkt = $produktId ? $produktRepo->findById($produktId) : null; // bei vorhandener ID das bestehende Produkt laden
$fehler = []; // Liste der Validierungsfehler
$eingabe = $_POST; // zuletzt eingegebene Werte, damit das Formular bei Fehlern nicht leer ist

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // das Formular wurde abgeschickt
    $pflichtfelder = ['bezeichnung', 'preis', 'kategorie_id', 'lagerbestand']; // immer erforderliche Felder
    foreach ($pflichtfelder as $feld) { // jedes Pflichtfeld prüfen
        if (trim($_POST[$feld] ?? '') === '') { // Feld fehlt oder ist leer
            $fehler[] = "Feld \"$feld\" ist erforderlich."; // Fehlermeldung sammeln
        }
    }

    if (!is_numeric($_POST['preis'] ?? '')) { // Preis muss eine Zahl sein
        $fehler[] = 'Preis muss eine Zahl sein.'; // Fehlermeldung sammeln
    }

    $bildPfad = $produkt?->bildPfad ?? null; // bestehendes Bild beibehalten, falls kein neues hochgeladen wird
    if (!empty($_FILES['bild']['name'])) { // ein neues Bild wurde ausgewählt
        $upload = $_FILES['bild']; // Upload-Informationen holen

        if ($upload['error'] !== UPLOAD_ERR_OK) { // beim Hochladen ist ein Fehler aufgetreten
            $fehler[] = 'Fehler beim Hochladen des Bildes.'; // Fehlermeldung sammeln
        } else {
            $mimeTyp = mime_content_type($upload['tmp_name']); // tatsächlichen Dateityp anhand des Inhalts prüfen (nicht nur die Dateiendung)

            if (!isset($erlaubteBildtypen[$mimeTyp])) { // nur erlaubte Bildtypen zulassen
                $fehler[] = 'Nur JPG-, PNG- oder WEBP-Bilder sind erlaubt.'; // Fehlermeldung sammeln
            } else {
                $dateiname = uniqid('produkt_', true) . '.' . $erlaubteBildtypen[$mimeTyp]; // eindeutigen, sicheren Dateinamen erzeugen
                move_uploaded_file($upload['tmp_name'], $bildOrdner . $dateiname); // Datei in den Zielordner verschieben
                $bildPfad = $dateiname; // neuen Dateinamen für die DB übernehmen
            }
        }
    }

    if (empty($fehler)) { // nur speichern, wenn keine Fehler aufgetreten sind
        $neuesProdukt = new Produkt( // Produkt-Objekt aus den Formulardaten aufbauen
            produktId: $produktId ?: null, // vorhandene ID beim Bearbeiten, sonst null (= neu anlegen)
            kategorieId: (int) $_POST['kategorie_id'], // gewählte Kategorie
            bezeichnung: trim($_POST['bezeichnung']), // Produktname
            beschreibung: trim($_POST['beschreibung'] ?? ''), // Beschreibung, optional
            preis: (float) $_POST['preis'], // Preis als Kommazahl
            bildPfad: $bildPfad, // neuer oder bestehender Bildpfad
            lagerbestand: (int) $_POST['lagerbestand'], // Lagerbestand als Ganzzahl
            aktiv: isset($_POST['aktiv']) // Häkchen gesetzt = im Shop sichtbar
        );

        if ($produktId) { // wenn eine ID vorhanden ist, existiert das Produkt bereits
            $produktRepo->aktualisieren($neuesProdukt); // bestehendes Produkt aktualisieren
        } else {
            $produktId = $produktRepo->erstellen($neuesProdukt); // neues Produkt anlegen
        }

        header('Location: produktverwaltung.php'); // zurück zur Produktverwaltung
        exit; // Skript nach der Umleitung beenden
    }
}

require __DIR__ . '/../templates/produkt_formular.php'; // bindet die Ansicht ein und stellt ihr $produkt, $kategorien, $eingabe und $fehler zur Verfügung
