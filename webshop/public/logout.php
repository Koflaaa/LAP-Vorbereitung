<?php

if (session_status() !== PHP_SESSION_ACTIVE) { // nur starten, wenn noch keine Session läuft
    session_start(); // startet bzw. setzt die Session fort
}

unset($_SESSION['kunde_id']); // Login-Status aus der Session entfernen

header('Location: index.php'); // zur Produktübersicht weiterleiten
exit; // Skript beenden
