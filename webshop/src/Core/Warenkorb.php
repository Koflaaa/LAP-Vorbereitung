<?php

namespace Webshop\Core; // Namensraum, unter dem diese Klasse liegt

class Warenkorb // verwaltet den Warenkorb in der PHP-Session (produkt_id => menge)
{
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) { // nur starten, wenn noch keine Session läuft
            session_start(); // startet bzw. setzt die Session fort
        }

        if (!isset($_SESSION['warenkorb'])) { // beim allerersten Aufruf ist der Warenkorb noch nicht angelegt
            $_SESSION['warenkorb'] = []; // leeren Warenkorb in der Session anlegen
        }
    }

    public function hinzufuegen(int $produktId, int $menge = 1): void // fügt ein Produkt hinzu oder erhöht die Menge
    {
        $bisherigeMenge = $_SESSION['warenkorb'][$produktId] ?? 0; // bisherige Menge lesen, 0 falls noch nicht im Warenkorb
        $_SESSION['warenkorb'][$produktId] = $bisherigeMenge + $menge; // neue Menge speichern
    }

    public function mengeAendern(int $produktId, int $menge): void // setzt die Menge eines Produkts auf einen festen Wert
    {
        if ($menge <= 0) { // 0 oder weniger bedeutet: Produkt entfernen
            $this->entfernen($produktId); // Produkt aus dem Warenkorb entfernen
            return; // Methode beenden
        }

        $_SESSION['warenkorb'][$produktId] = $menge; // neue Menge speichern
    }

    public function entfernen(int $produktId): void // entfernt ein Produkt vollständig aus dem Warenkorb
    {
        unset($_SESSION['warenkorb'][$produktId]); // Eintrag aus dem Array löschen
    }

    public function leeren(): void // leert den gesamten Warenkorb
    {
        $_SESSION['warenkorb'] = []; // Warenkorb-Array zurücksetzen
    }

    public function getPositionen(): array // liefert alle Positionen als produkt_id => menge
    {
        return $_SESSION['warenkorb']; // aktuellen Inhalt der Session zurückgeben
    }
}
