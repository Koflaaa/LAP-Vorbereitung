<?php

require_once __DIR__ . '/../src/Repository/ProduktRepository.php'; // bindet das Repository ein (dieses bindet Database.php und Produkt.php selbst mit ein)

use Webshop\Repository\ProduktRepository; // Klasse für den Zugriff auf die Produkttabelle

$repo = new ProduktRepository(); // erstellt das Repository-Objekt
$produkte = $repo->findAlleAktiven(); // lädt alle aktiven Produkte aus der Datenbank

require __DIR__ . '/../templates/produktliste.php'; // bindet die Ansicht ein und stellt ihr $produkte zur Verfügung
