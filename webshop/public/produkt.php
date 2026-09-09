<?php

if (session_status() !== PHP_SESSION_ACTIVE) { // nur starten, wenn noch keine Session läuft
    session_start(); // muss vor jeder Ausgabe passieren, damit die Navigation den Login-Status per Cookie erkennen kann
}

require_once __DIR__ . '/../src/Repository/ProduktRepository.php'; // bindet das Produkt-Repository ein
require_once __DIR__ . '/../src/Repository/KategorieRepository.php'; // bindet das Kategorie-Repository ein

use Webshop\Repository\ProduktRepository; // Klasse für den Zugriff auf die Produkttabelle
use Webshop\Repository\KategorieRepository; // Klasse für den Zugriff auf die Kategorietabelle

$produktId = (int) ($_GET['id'] ?? 0); // liest die ID aus der URL, 0 falls keine übergeben wurde

$produktRepo = new ProduktRepository(); // erstellt das Produkt-Repository-Objekt
$produkt = $produktRepo->findById($produktId); // lädt das gewünschte Produkt (oder null, wenn nicht gefunden)

$kategorie = null; // Standardwert, falls kein Produkt gefunden wurde
if ($produkt !== null) { // nur wenn ein Produkt gefunden wurde
    $kategorieRepo = new KategorieRepository(); // erstellt das Kategorie-Repository-Objekt
    $kategorie = $kategorieRepo->findById($produkt->kategorieId); // lädt die zugehörige Kategorie
}

require __DIR__ . '/../templates/produktdetail.php'; // bindet die Ansicht ein und stellt ihr $produkt und $kategorie zur Verfügung
