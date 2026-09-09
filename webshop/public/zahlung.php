<?php

require_once __DIR__ . '/../src/Core/Warenkorb.php'; // bindet die Warenkorb-Klasse ein
require_once __DIR__ . '/../src/Model/Bestellung.php'; // bindet die Bestellung-Datenklasse ein
require_once __DIR__ . '/../src/Model/Bestellposition.php'; // bindet die Bestellposition-Datenklasse ein
require_once __DIR__ . '/../src/Repository/ProduktRepository.php'; // bindet das Produkt-Repository ein
require_once __DIR__ . '/../src/Repository/BestellungRepository.php'; // bindet das Bestellung-Repository ein
require_once __DIR__ . '/../src/Repository/BestellpositionRepository.php'; // bindet das Bestellposition-Repository ein

use Webshop\Core\Warenkorb; // Klasse für die Verwaltung des Warenkorbs in der Session
use Webshop\Model\Bestellung; // Datenklasse für eine Bestellung
use Webshop\Model\Bestellposition; // Datenklasse für eine Bestellposition
use Webshop\Repository\ProduktRepository; // Klasse für den Zugriff auf die Produkttabelle
use Webshop\Repository\BestellungRepository; // Klasse für den Zugriff auf die Bestelltabelle
use Webshop\Repository\BestellpositionRepository; // Klasse für den Zugriff auf die Bestellpositionstabelle

$warenkorb = new Warenkorb(); // startet die Session und lädt den Warenkorb

if (empty($warenkorb->getPositionen()) || !isset($_SESSION['kasse'])) { // ohne Artikel oder ohne Kundendaten kann nicht bestellt werden
    header('Location: warenkorb.php'); // zurück zum Warenkorb schicken
    exit; // Skript beenden
}

$fehler = []; // Liste der Validierungsfehler
$zulaessigeZahlungsarten = [Bestellung::ZAHLUNG_RECHNUNG, Bestellung::ZAHLUNG_KREDITKARTE]; // erlaubte Werte für die Auswahl

$produktRepo = new ProduktRepository(); // erstellt das Produkt-Repository, wird für Anzeige und Bestellabschluss gebraucht
$positionen = []; // Warenkorb-Zeilen für die Anzeige (Produkt, Menge, Zwischensumme)
$gesamtbetrag = 0.0; // Summe über alle Positionen

foreach ($warenkorb->getPositionen() as $produktId => $menge) { // jede Position im Warenkorb durchgehen
    $produkt = $produktRepo->findById($produktId); // zugehöriges Produkt aus der Datenbank laden

    if ($produkt === null) { // Produkt existiert nicht mehr
        continue; // diese Position überspringen
    }

    $zwischensumme = $produkt->preis * $menge; // Preis mal Menge berechnen
    $gesamtbetrag += $zwischensumme; // zur Gesamtsumme addieren

    $positionen[] = [ // Zeile für Anzeige und Bestellabschluss zusammenstellen
        'produkt' => $produkt, // das Produkt-Objekt
        'menge' => $menge, // die bestellte Menge
        'zwischensumme' => $zwischensumme, // die berechnete Zwischensumme
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // das Formular wurde abgeschickt
    $zahlungsart = $_POST['zahlungsart'] ?? ''; // gewählte Zahlungsart lesen

    if (!in_array($zahlungsart, $zulaessigeZahlungsarten, true)) { // nur erlaubte Werte akzeptieren
        $fehler[] = 'Bitte eine Zahlungsart auswählen.'; // Fehlermeldung sammeln
    }

    if (empty($fehler)) { // nur abschließen, wenn keine Fehler aufgetreten sind
        $bestellungRepo = new BestellungRepository(); // erstellt das Bestellung-Repository
        $bestellpositionRepo = new BestellpositionRepository(); // erstellt das Bestellposition-Repository

        $bestellung = new Bestellung( // neues Bestellung-Objekt aus Session- und Warenkorbdaten aufbauen
            bestellungId: null, // noch nicht gespeichert
            kundeId: $_SESSION['kasse']['kunde_id'], // Kunde aus Schritt 3b
            rechnungsadresseId: $_SESSION['kasse']['rechnungsadresse_id'], // Rechnungsadresse aus Schritt 3b
            lieferadresseId: $_SESSION['kasse']['lieferadresse_id'], // Lieferadresse aus Schritt 3b
            zahlungsart: $zahlungsart, // ausgewählte Zahlungsart
            status: Bestellung::STATUS_OFFEN, // neue Bestellungen starten als "offen"
            gesamtbetrag: $gesamtbetrag // zuvor berechnete Gesamtsumme
        );
        $bestellungId = $bestellungRepo->erstellen($bestellung); // Bestellung in der DB anlegen, ID zurückbekommen

        foreach ($positionen as $position) { // für jede Warenkorb-Zeile eine Bestellposition anlegen
            $bestellposition = new Bestellposition( // neue Bestellposition aufbauen
                bestellpositionId: null, // noch nicht gespeichert
                bestellungId: $bestellungId, // gehört zur neu angelegten Bestellung
                produktId: $position['produkt']->produktId, // bestelltes Produkt
                menge: $position['menge'], // bestellte Menge
                einzelpreis: $position['produkt']->preis // aktueller Preis, wird als Preis zum Bestellzeitpunkt gespeichert
            );
            $bestellpositionRepo->erstellen($bestellposition); // Bestellposition in der DB anlegen
        }

        $warenkorb->leeren(); // Warenkorb nach erfolgreicher Bestellung leeren
        unset($_SESSION['kasse']); // Kundendaten-Zwischenspeicher der Kasse nicht mehr benötigt

        header('Location: bestellbestaetigung.php?id=' . $bestellungId); // weiter zur Bestätigungsseite (lädt die Bestellung neu aus der DB)
        exit; // Skript nach der Umleitung beenden
    }
}

require __DIR__ . '/../templates/zahlung.php'; // bindet die Ansicht ein und stellt ihr $positionen, $gesamtbetrag und $fehler zur Verfügung
