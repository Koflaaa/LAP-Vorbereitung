<?php

require_once __DIR__ . '/../src/Repository/ProduktRepository.php'; // bindet das Produkt-Repository ein

use Webshop\Repository\ProduktRepository; // Klasse für den Zugriff auf die Produkttabelle

$repo = new ProduktRepository(); // erstellt das Repository-Objekt
$produkte = $repo->findAlle(); // lädt wirklich alle Produkte, auch deaktivierte

require __DIR__ . '/../templates/produktverwaltung.php'; // bindet die Ansicht ein und stellt ihr $produkte zur Verfügung
