<?php

if (session_status() !== PHP_SESSION_ACTIVE) { // nur starten, wenn noch keine Session läuft
    session_start(); // muss vor jeder Ausgabe passieren, damit die Navigation den Login-Status per Cookie erkennen kann
}

require_once __DIR__ . '/../src/Repository/StatistikRepository.php'; // bindet das Statistik-Repository ein

use Webshop\Repository\StatistikRepository; // Klasse für die statistischen Auswertungen

$repo = new StatistikRepository(); // erstellt das Statistik-Repository

$topProdukte = $repo->meistBestellteProdukte(5); // lädt die 5 meistbestellten Produkte
$flopProdukte = $repo->seltenBestellteProdukte(5); // lädt die 5 am seltensten bestellten Produkte
$bestellungen = $repo->bestellungenLetzteVierWochen(); // lädt alle Bestellungen der letzten 4 Wochen

require __DIR__ . '/../templates/statistik.php'; // bindet die Ansicht ein und stellt ihr die drei Auswertungen zur Verfügung
