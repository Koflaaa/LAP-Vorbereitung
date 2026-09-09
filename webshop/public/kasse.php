<?php

require_once __DIR__ . '/../src/Core/Warenkorb.php'; // bindet die Warenkorb-Klasse ein (Session-Start passiert im Konstruktor)
require_once __DIR__ . '/../src/Model/Kunde.php'; // bindet die Kunde-Datenklasse ein
require_once __DIR__ . '/../src/Model/Adresse.php'; // bindet die Adresse-Datenklasse ein
require_once __DIR__ . '/../src/Repository/KundeRepository.php'; // bindet das Kunde-Repository ein
require_once __DIR__ . '/../src/Repository/AdresseRepository.php'; // bindet das Adresse-Repository ein

use Webshop\Core\Warenkorb; // Klasse für die Verwaltung des Warenkorbs in der Session
use Webshop\Model\Kunde; // Datenklasse für einen Kunden
use Webshop\Model\Adresse; // Datenklasse für eine Adresse
use Webshop\Repository\KundeRepository; // Klasse für den Zugriff auf die Kundentabelle
use Webshop\Repository\AdresseRepository; // Klasse für den Zugriff auf die Adresstabelle

$warenkorb = new Warenkorb(); // startet die Session und lädt den Warenkorb

if (empty($warenkorb->getPositionen())) { // ohne Artikel im Warenkorb gibt es nichts zu bestellen
    header('Location: warenkorb.php'); // zurück zum (leeren) Warenkorb schicken
    exit; // Skript beenden
}

$kundeRepo = new KundeRepository(); // erstellt das Kunde-Repository
$adresseRepo = new AdresseRepository(); // erstellt das Adresse-Repository

$eingeloggterKunde = isset($_SESSION['kunde_id']) ? $kundeRepo->findById($_SESSION['kunde_id']) : null; // lädt den angemeldeten Kunden, falls vorhanden
$gespeicherteRechnung = $eingeloggterKunde ? $adresseRepo->findLetzteVonKunde($eingeloggterKunde->kundeId, Adresse::TYP_RECHNUNG) : null; // zuletzt verwendete Rechnungsadresse zur Vorbefüllung
$gespeicherteLieferung = $eingeloggterKunde ? $adresseRepo->findLetzteVonKunde($eingeloggterKunde->kundeId, Adresse::TYP_LIEFERUNG) : null; // zuletzt verwendete Lieferadresse zur Vorbefüllung

$fehler = []; // Liste der Validierungsfehler
$eingabe = $_POST; // zuletzt eingegebene Werte, damit das Formular bei Fehlern nicht leer ist

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // das Formular wurde abgeschickt
    $identisch = isset($_POST['lieferadresse_identisch']); // true, wenn die Checkbox angehakt wurde

    $pflichtfelder = ['r_strasse', 'r_hausnummer', 'r_plz', 'r_ort', 'r_land']; // Rechnungsadresse ist immer erforderlich
    if (!$eingeloggterKunde) { // Kontaktdaten nur bei Gastbestellung abfragen, eingeloggte Kunden haben sie schon hinterlegt
        $pflichtfelder = array_merge(['vorname', 'nachname', 'email', 'telefon'], $pflichtfelder); // Kontaktfelder ergänzen
    }
    if (!$identisch) { // eigene Lieferadresse nur prüfen, wenn sie nicht der Rechnungsadresse entspricht
        $pflichtfelder = array_merge($pflichtfelder, ['l_strasse', 'l_hausnummer', 'l_plz', 'l_ort', 'l_land']); // Lieferadress-Felder ergänzen
    }

    foreach ($pflichtfelder as $feld) { // jedes Pflichtfeld prüfen
        if (trim($_POST[$feld] ?? '') === '') { // Feld fehlt oder ist leer
            $fehler[] = "Feld \"$feld\" ist erforderlich."; // Fehlermeldung sammeln
        }
    }

    if (!$eingeloggterKunde && !filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL)) { // E-Mail-Format nur bei Gastbestellung prüfen
        $fehler[] = 'Die E-Mail-Adresse ist ungültig.'; // Fehlermeldung sammeln
    }

    if (empty($fehler)) { // nur speichern, wenn keine Fehler aufgetreten sind
        if ($eingeloggterKunde) { // bereits angemeldeter Kunde
            $kundeId = $eingeloggterKunde->kundeId; // vorhandenen Kunden weiterverwenden, keinen neuen anlegen
        } else { // Gastbestellung
            $kunde = new Kunde( // neues Kunde-Objekt aus den Formulardaten aufbauen
                kundeId: null, // noch nicht gespeichert
                vorname: trim($_POST['vorname']), // Vorname übernehmen
                nachname: trim($_POST['nachname']), // Nachname übernehmen
                email: trim($_POST['email']), // E-Mail übernehmen
                telefon: trim($_POST['telefon']) // Telefonnummer übernehmen
            );
            $kundeId = $kundeRepo->erstellen($kunde); // Kunden in der DB anlegen, ID zurückbekommen
        }

        $rechnungsadresse = new Adresse( // Rechnungsadresse aus den Formulardaten aufbauen
            adresseId: null, // noch nicht gespeichert
            kundeId: $kundeId, // gehört zum Kunden dieser Bestellung
            typ: Adresse::TYP_RECHNUNG, // Typ "rechnung"
            strasse: trim($_POST['r_strasse']), // Straße übernehmen
            hausnummer: trim($_POST['r_hausnummer']), // Hausnummer übernehmen
            plz: trim($_POST['r_plz']), // Postleitzahl übernehmen
            ort: trim($_POST['r_ort']), // Ort übernehmen
            land: trim($_POST['r_land']) // Land übernehmen
        );
        $rechnungsadresseId = $adresseRepo->erstellen($rechnungsadresse); // Rechnungsadresse in der DB anlegen

        $lieferdaten = $identisch // Werte für die Lieferadresse festlegen
            ? $_POST // bei Checkbox: dieselben Werte wie die Rechnungsadresse verwenden
            : ['r_strasse' => $_POST['l_strasse'], 'r_hausnummer' => $_POST['l_hausnummer'], 'r_plz' => $_POST['l_plz'], 'r_ort' => $_POST['l_ort'], 'r_land' => $_POST['l_land']]; // sonst eigene Werte verwenden

        $lieferadresse = new Adresse( // Lieferadresse aus den ermittelten Werten aufbauen
            adresseId: null, // noch nicht gespeichert
            kundeId: $kundeId, // gehört zum Kunden dieser Bestellung
            typ: Adresse::TYP_LIEFERUNG, // Typ "lieferung"
            strasse: trim($lieferdaten['r_strasse']), // Straße übernehmen
            hausnummer: trim($lieferdaten['r_hausnummer']), // Hausnummer übernehmen
            plz: trim($lieferdaten['r_plz']), // Postleitzahl übernehmen
            ort: trim($lieferdaten['r_ort']), // Ort übernehmen
            land: trim($lieferdaten['r_land']) // Land übernehmen
        );
        $lieferadresseId = $adresseRepo->erstellen($lieferadresse); // Lieferadresse in der DB anlegen

        $_SESSION['kasse'] = [ // Ergebnis für den nächsten Schritt (Zahlungsart) in der Session merken
            'kunde_id' => $kundeId, // verwendeter Kunde
            'rechnungsadresse_id' => $rechnungsadresseId, // angelegte Rechnungsadresse
            'lieferadresse_id' => $lieferadresseId, // angelegte Lieferadresse
        ];

        header('Location: zahlung.php'); // weiter zur Auswahl der Zahlungsart (Schritt 3c)
        exit; // Skript nach der Umleitung beenden
    }
}

require __DIR__ . '/../templates/kasse.php'; // bindet die Ansicht ein und stellt ihr $fehler, $eingabe, $eingeloggterKunde und die gespeicherten Adressen zur Verfügung
