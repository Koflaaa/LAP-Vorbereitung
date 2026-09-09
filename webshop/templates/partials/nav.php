<?php

require_once __DIR__ . '/../../src/Core/Auth.php'; // bindet die Auth-Klasse ein (kein Autoloader)

use Webshop\Core\Auth; // Klasse für das Auslesen des angemeldeten Kunden

$navKunde = Auth::eingeloggterKunde(); // lädt den angemeldeten Kunden, falls vorhanden (für alle Seiten gleich)

?>
<nav class="hauptnav"> <!-- gemeinsame Navigation, wird auf jeder Seite eingebunden -->
    <a href="index.php">Produkte</a>
    <a href="warenkorb.php">Warenkorb</a>
    <a href="statistik.php">Statistik</a>
    <a href="produktverwaltung.php">Produktverwaltung</a>
    <span class="hauptnav-konto"> <!-- rechter Bereich: Login-Status -->
        <?php if ($navKunde): // wenn jemand eingeloggt ist ?>
            Hallo, <?= htmlspecialchars($navKunde->vorname) /* Vorname sicher ausgeben */ ?>
            <a href="logout.php">Logout</a>
        <?php else: // niemand eingeloggt ?>
            <a href="login.php">Login</a>
            <a href="registrieren.php">Registrieren</a>
        <?php endif; ?>
    </span>
</nav>
