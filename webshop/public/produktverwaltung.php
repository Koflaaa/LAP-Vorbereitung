<?php

if (session_status() !== PHP_SESSION_ACTIVE) { // nur starten, wenn noch keine Session läuft
    session_start(); // muss vor jeder Ausgabe passieren, damit die Navigation den Login-Status per Cookie erkennen kann
}

require_once __DIR__ . '/../src/Repository/ProduktRepository.php'; // bindet das Produkt-Repository ein

use Webshop\Repository\ProduktRepository; // Klasse für den Zugriff auf die Produkttabelle

$repo = new ProduktRepository(); // erstellt das Repository-Objekt
$produkte = $repo->findAlle(); // lädt wirklich alle Produkte, auch deaktivierte

require __DIR__ . '/../templates/produktverwaltung.php'; // bindet die Ansicht ein und stellt ihr $produkte zur Verfügung
