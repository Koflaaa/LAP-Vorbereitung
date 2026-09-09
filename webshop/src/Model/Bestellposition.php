<?php

namespace Webshop\Model; // Namensraum für alle Datenklassen (Models)

class Bestellposition // bildet eine Zeile der Tabelle "bestellposition" als Objekt ab
{
    public function __construct(
        public ?int $bestellpositionId, // Primärschlüssel; null, solange die Position noch nicht gespeichert ist
        public int $bestellungId, // verweist auf die zugehörige Bestellung
        public int $produktId, // verweist auf das bestellte Produkt
        public int $menge, // bestellte Stückzahl
        public float $einzelpreis // Preis pro Stück zum Bestellzeitpunkt (unabhängig vom aktuellen Produktpreis)
    ) {
    }
}
