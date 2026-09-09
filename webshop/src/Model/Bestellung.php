<?php

namespace Webshop\Model; // Namensraum für alle Datenklassen (Models)

class Bestellung // bildet eine Zeile der Tabelle "bestellung" als Objekt ab
{
    public function __construct(
        public ?int $bestellungId, // Primärschlüssel; null, solange die Bestellung noch nicht gespeichert ist
        public int $kundeId, // verweist auf den bestellenden Kunden
        public int $rechnungsadresseId, // verweist auf die verwendete Rechnungsadresse
        public int $lieferadresseId, // verweist auf die verwendete Lieferadresse
        public string $zahlungsart, // "rechnung" oder "kreditkarte"
        public string $status, // "offen", "bezahlt", "versandt" oder "storniert"
        public float $gesamtbetrag, // Summe aller Bestellpositionen zum Bestellzeitpunkt
        public ?string $bestelldatum = null // Zeitpunkt der Bestellung; null = wird beim Speichern von der DB gesetzt
    ) {
    }
}
