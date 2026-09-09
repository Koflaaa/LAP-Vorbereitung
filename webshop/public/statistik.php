<?php

require_once __DIR__ . '/../src/Repository/StatistikRepository.php'; // bindet das Statistik-Repository ein

use Webshop\Repository\StatistikRepository; // Klasse für die statistischen Auswertungen

$repo = new StatistikRepository(); // erstellt das Statistik-Repository

$topProdukte = $repo->meistBestellteProdukte(5); // lädt die 5 meistbestellten Produkte
$flopProdukte = $repo->seltenBestellteProdukte(5); // lädt die 5 am seltensten bestellten Produkte
$bestellungen = $repo->bestellungenLetzteVierWochen(); // lädt alle Bestellungen der letzten 4 Wochen

require __DIR__ . '/../templates/statistik.php'; // bindet die Ansicht ein und stellt ihr die drei Auswertungen zur Verfügung
