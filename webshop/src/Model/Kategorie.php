<?php

namespace Webshop\Model; // Namensraum für alle Datenklassen (Models)

class Kategorie // bildet eine Zeile der Tabelle "kategorie" als Objekt ab
{
    public function __construct(
        public ?int $kategorieId, // Primärschlüssel; null, solange die Kategorie noch nicht gespeichert ist
        public string $bezeichnung // Name der Kategorie, z. B. "Getränke"
    ) {
    }
}
