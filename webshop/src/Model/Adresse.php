<?php

namespace Webshop\Model; // Namensraum für alle Datenklassen (Models)

class Adresse // bildet eine Zeile der Tabelle "adresse" als Objekt ab
{
    public function __construct(
        public ?int $adresseId, // Primärschlüssel; null, solange die Adresse noch nicht gespeichert ist
        public int $kundeId, // verweist auf den zugehörigen Kunden
        public string $typ, // "rechnung" oder "lieferung"
        public string $strasse, // Straßenname
        public string $hausnummer, // Hausnummer
        public string $plz, // Postleitzahl
        public string $ort, // Ort/Stadt
        public string $land = 'Österreich' // Land, Standardwert Österreich
    ) {
    }
}
