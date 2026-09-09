<?php

require_once __DIR__ . '/../src/Repository/ProduktRepository.php'; // bindet das Produkt-Repository ein
require_once __DIR__ . '/../src/Repository/BestellungRepository.php'; // bindet das Bestellung-Repository ein
require_once __DIR__ . '/../src/Repository/BestellpositionRepository.php'; // bindet das Bestellposition-Repository ein

use Webshop\Repository\ProduktRepository; // Klasse für den Zugriff auf die Produkttabelle
use Webshop\Repository\BestellungRepository; // Klasse für den Zugriff auf die Bestelltabelle
use Webshop\Repository\BestellpositionRepository; // Klasse für den Zugriff auf die Bestellpositionstabelle

$bestellungId = (int) ($_GET['id'] ?? 0); // liest die Bestellungs-ID aus der URL

$bestellungRepo = new BestellungRepository(); // erstellt das Bestellung-Repository
$bestellung = $bestellungRepo->findById($bestellungId); // lädt die Bestellung (oder null, wenn nicht gefunden)

$positionen = []; // Liste der Bestellpositionen für die Anzeige
if ($bestellung !== null) { // nur laden, wenn die Bestellung existiert
    $bestellpositionRepo = new BestellpositionRepository(); // erstellt das Bestellposition-Repository
    $produktRepo = new ProduktRepository(); // erstellt das Produkt-Repository für die Produktnamen

    foreach ($bestellpositionRepo->findByBestellung($bestellungId) as $position) { // jede gespeicherte Position durchgehen
        $positionen[] = [ // Zeile für die Anzeige zusammenstellen
            'produkt' => $produktRepo->findById($position->produktId), // zugehöriges Produkt laden (für den Namen)
            'menge' => $position->menge, // bestellte Menge
            'einzelpreis' => $position->einzelpreis, // Preis zum Bestellzeitpunkt
        ];
    }
}

require __DIR__ . '/../templates/bestellbestaetigung.php'; // bindet die Ansicht ein und stellt ihr $bestellung und $positionen zur Verfügung
