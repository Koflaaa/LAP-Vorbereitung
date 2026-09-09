<?php

namespace Webshop\Model; // Namensraum für alle Datenklassen (Models)

class Kunde // bildet eine Zeile der Tabelle "kunde" als Objekt ab
{
    public function __construct(
        public ?int $kundeId, // Primärschlüssel; null, solange der Kunde noch nicht gespeichert ist
        public string $vorname, // Vorname des Kunden
        public string $nachname, // Nachname des Kunden
        public string $email, // E-Mail-Adresse, in der DB eindeutig (UNIQUE)
        public ?string $telefon, // Telefonnummer, darf leer sein
        public ?string $passwortHash = null // gehashtes Passwort; null = Gastbestellung ohne Konto
    ) {
    }
}
