<?php

require_once __DIR__ . '/../src/Repository/ProduktRepository.php'; // bindet das Produkt-Repository ein

use Webshop\Repository\ProduktRepository; // Klasse für den Zugriff auf die Produkttabelle

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // nur auf POST reagieren, damit ein Löschen nicht über einen einfachen Link ausgelöst werden kann
    $produktId = (int) ($_POST['produkt_id'] ?? 0); // zu löschendes Produkt aus dem Formular lesen

    $repo = new ProduktRepository(); // erstellt das Repository-Objekt
    $repo->deaktivieren($produktId); // Produkt deaktivieren (Soft-Delete, siehe ER-Diagramm.md)
}

header('Location: produktverwaltung.php'); // zurück zur Produktverwaltung
exit; // Skript beenden
