<?php

require_once __DIR__ . '/../src/Repository/ProduktRepository.php'; // bindet das Produkt-Repository ein
require_once __DIR__ . '/../src/Repository/KundeRepository.php'; // bindet das Kunde-Repository ein
require_once __DIR__ . '/../src/Repository/AdresseRepository.php'; // bindet das Adresse-Repository ein
require_once __DIR__ . '/../src/Repository/BestellungRepository.php'; // bindet das Bestellung-Repository ein
require_once __DIR__ . '/../src/Repository/BestellpositionRepository.php'; // bindet das Bestellposition-Repository ein

use Webshop\Repository\ProduktRepository; // Klasse für den Zugriff auf die Produkttabelle
use Webshop\Repository\KundeRepository; // Klasse für den Zugriff auf die Kundentabelle
use Webshop\Repository\AdresseRepository; // Klasse für den Zugriff auf die Adresstabelle
use Webshop\Repository\BestellungRepository; // Klasse für den Zugriff auf die Bestelltabelle
use Webshop\Repository\BestellpositionRepository; // Klasse für den Zugriff auf die Bestellpositionstabelle

$bestellungId = (int) ($_GET['id'] ?? 0); // liest die Bestellungs-ID aus der URL

$bestellungRepo = new BestellungRepository(); // erstellt das Bestellung-Repository
$bestellung = $bestellungRepo->findById($bestellungId); // lädt die Bestellung (oder null, wenn nicht gefunden)

$kunde = null; // Kunde für den Rechnungskopf
$rechnungsadresse = null; // Rechnungsadresse für den Rechnungskopf
$lieferadresse = null; // Lieferadresse für den Rechnungskopf
$positionen = []; // Liste der Rechnungspositionen

if ($bestellung !== null) { // nur laden, wenn die Bestellung existiert
    $kunde = (new KundeRepository())->findById($bestellung->kundeId); // Kunde der Bestellung laden
    $adresseRepo = new AdresseRepository(); // erstellt das Adresse-Repository
    $rechnungsadresse = $adresseRepo->findById($bestellung->rechnungsadresseId); // exakte Rechnungsadresse dieser Bestellung laden
    $lieferadresse = $adresseRepo->findById($bestellung->lieferadresseId); // exakte Lieferadresse dieser Bestellung laden

    $produktRepo = new ProduktRepository(); // erstellt das Produkt-Repository für die Produktnamen
    $bestellpositionRepo = new BestellpositionRepository(); // erstellt das Bestellposition-Repository

    foreach ($bestellpositionRepo->findByBestellung($bestellungId) as $position) { // jede gespeicherte Position durchgehen
        $positionen[] = [ // Zeile für die Rechnung zusammenstellen
            'produkt' => $produktRepo->findById($position->produktId), // zugehöriges Produkt laden (für den Namen)
            'menge' => $position->menge, // bestellte Menge
            'einzelpreis' => $position->einzelpreis, // Preis zum Bestellzeitpunkt
            'zwischensumme' => $position->menge * $position->einzelpreis, // Zeilensumme berechnen
        ];
    }
}

require __DIR__ . '/../templates/rechnung.php'; // bindet die Ansicht ein und stellt ihr alle geladenen Daten zur Verfügung
