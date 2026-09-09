<?php

require_once __DIR__ . '/../src/Core/Warenkorb.php'; // bindet die Warenkorb-Klasse ein
require_once __DIR__ . '/../src/Repository/ProduktRepository.php'; // bindet das Produkt-Repository ein

use Webshop\Core\Warenkorb; // Klasse für die Verwaltung des Warenkorbs in der Session
use Webshop\Repository\ProduktRepository; // Klasse für den Zugriff auf die Produkttabelle

$warenkorb = new Warenkorb(); // erstellt/lädt den Warenkorb aus der Session

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // ein Formular wurde abgeschickt (hinzufügen/ändern/entfernen)
    $action = $_POST['action'] ?? ''; // gewünschte Aktion aus dem Formular lesen
    $produktId = (int) ($_POST['produkt_id'] ?? 0); // betroffene Produkt-ID lesen
    $menge = (int) ($_POST['menge'] ?? 1); // gewünschte Menge lesen, Standard 1

    if ($action === 'hinzufuegen') { // Produkt soll hinzugefügt werden
        $warenkorb->hinzufuegen($produktId, max(1, $menge)); // mindestens 1 Stück hinzufügen
    } elseif ($action === 'aendern') { // Menge eines vorhandenen Produkts soll geändert werden
        $warenkorb->mengeAendern($produktId, $menge); // neue Menge setzen (0 entfernt das Produkt)
    } elseif ($action === 'entfernen') { // Produkt soll komplett entfernt werden
        $warenkorb->entfernen($produktId); // Produkt aus dem Warenkorb löschen
    }

    header('Location: warenkorb.php'); // nach der Aktion zur gleichen Seite umleiten (verhindert Doppel-Absenden bei Neuladen)
    exit; // Skript nach der Umleitung beenden
}

$produktRepo = new ProduktRepository(); // erstellt das Produkt-Repository für die Anzeige
$positionen = []; // Liste der Warenkorb-Zeilen für die Ansicht
$gesamtbetrag = 0.0; // Summe über alle Positionen

foreach ($warenkorb->getPositionen() as $produktId => $menge) { // jede Position im Warenkorb durchgehen
    $produkt = $produktRepo->findById($produktId); // zugehöriges Produkt aus der Datenbank laden

    if ($produkt === null) { // Produkt existiert nicht mehr (z. B. gelöscht)
        continue; // diese Position überspringen
    }

    $zwischensumme = $produkt->preis * $menge; // Preis mal Menge berechnen
    $gesamtbetrag += $zwischensumme; // zur Gesamtsumme addieren

    $positionen[] = [ // Zeile für die Ansicht zusammenstellen
        'produkt' => $produkt, // das Produkt-Objekt
        'menge' => $menge, // die bestellte Menge
        'zwischensumme' => $zwischensumme, // die berechnete Zwischensumme
    ];
}

require __DIR__ . '/../templates/warenkorb.php'; // bindet die Ansicht ein und stellt ihr $positionen und $gesamtbetrag zur Verfügung
