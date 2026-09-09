<?php
// Kopie als config.php anlegen und mit echten Zugangsdaten befüllen. config.php ist in .gitignore und wird NICHT versioniert.

return [ // gibt ein Array mit der gesamten Konfiguration zurück
    'db' => [ // Zugangsdaten für die Datenbankverbindung
        'host' => '127.0.0.1', // Adresse des Datenbankservers
        'port' => 3306, // Standardport von MySQL/MariaDB
        'name' => 'webshop', // Name der zu verwendenden Datenbank
        'user' => 'webshop_user', // Datenbank-Benutzername
        'pass' => 'change_me', // Datenbank-Passwort
        'charset' => 'utf8mb4', // Zeichensatz für volle Unicode-Unterstützung
    ],
];
