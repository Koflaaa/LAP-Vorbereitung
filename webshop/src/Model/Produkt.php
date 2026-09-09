<?php

namespace Webshop\Model; // Namensraum für alle Datenklassen (Models)

class Produkt // bildet eine Zeile der Tabelle "produkt" als Objekt ab
{
    public function __construct(
        public ?int $produktId, // Primärschlüssel; null, solange das Produkt noch nicht gespeichert ist
        public int $kategorieId, // verweist auf die zugehörige Kategorie
        public string $bezeichnung, // Produktname
        public ?string $beschreibung, // ausführliche Beschreibung, darf leer sein
        public float $preis, // Verkaufspreis
        public ?string $bildPfad, // Pfad zum Produktbild im Dateisystem
        public int $lagerbestand, // verfügbare Stückzahl
        public bool $aktiv = true // false = Produkt im Shop ausgeblendet (Soft-Delete)
    ) {
    }
}
