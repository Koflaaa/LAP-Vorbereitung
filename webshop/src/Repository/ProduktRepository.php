<?php

namespace Webshop\Repository; // Namensraum für alle Repository-Klassen (Datenbankzugriff)

require_once __DIR__ . '/../Core/Database.php'; // bindet die Datenbankverbindung manuell ein (kein Autoloader mehr)
require_once __DIR__ . '/../Model/Produkt.php'; // bindet die Produkt-Datenklasse manuell ein (kein Autoloader mehr)

use Webshop\Core\Database; // stellt die Datenbankverbindung bereit
use Webshop\Model\Produkt; // Datenklasse, in die die Ergebniszeilen umgewandelt werden

class ProduktRepository // kapselt den gesamten Lesezugriff auf die Tabelle "produkt"
{
    public function findAlleAktiven(): array // liefert alle im Shop sichtbaren Produkte
    {
        $db = Database::getConnection(); // holt die gemeinsame Datenbankverbindung
        $stmt = $db->query('SELECT * FROM produkt WHERE aktiv = 1 ORDER BY bezeichnung'); // fragt alle aktiven Produkte sortiert nach Name ab
        $zeilen = $stmt->fetchAll(); // holt alle Ergebniszeilen als Array

        return array_map([$this, 'hydrate'], $zeilen); // wandelt jede Zeile in ein Produkt-Objekt um
    }

    public function findAlle(): array // liefert wirklich alle Produkte, auch deaktivierte (für die Produktverwaltung)
    {
        $db = Database::getConnection(); // holt die gemeinsame Datenbankverbindung
        $stmt = $db->query('SELECT * FROM produkt ORDER BY bezeichnung'); // fragt alle Produkte sortiert nach Name ab
        $zeilen = $stmt->fetchAll(); // holt alle Ergebniszeilen als Array

        return array_map([$this, 'hydrate'], $zeilen); // wandelt jede Zeile in ein Produkt-Objekt um
    }

    public function findById(int $produktId): ?Produkt // liefert ein einzelnes Produkt anhand seiner ID
    {
        $db = Database::getConnection(); // holt die gemeinsame Datenbankverbindung
        $stmt = $db->prepare('SELECT * FROM produkt WHERE produkt_id = :id'); // bereitet die Abfrage vor (schützt vor SQL-Injection)
        $stmt->execute(['id' => $produktId]); // führt die Abfrage mit der übergebenen ID aus
        $zeile = $stmt->fetch(); // holt die eine Ergebniszeile (oder false, wenn keine gefunden wurde)

        return $zeile ? $this->hydrate($zeile) : null; // wandelt die Zeile in ein Produkt-Objekt um, sonst null
    }

    public function erstellen(Produkt $produkt): int // legt ein neues Produkt an und liefert die neue ID
    {
        $db = Database::getConnection(); // holt die gemeinsame Datenbankverbindung
        $stmt = $db->prepare( // bereitet die Einfüge-Anweisung vor (schützt vor SQL-Injection)
            'INSERT INTO produkt (kategorie_id, bezeichnung, beschreibung, preis, bild_pfad, lagerbestand, aktiv)
             VALUES (:kategorie_id, :bezeichnung, :beschreibung, :preis, :bild_pfad, :lagerbestand, :aktiv)'
        );
        $stmt->execute($this->toParams($produkt)); // führt die Einfüge-Anweisung mit den Produktdaten aus

        return (int) $db->lastInsertId(); // liefert die vom DBMS vergebene neue ID
    }

    public function aktualisieren(Produkt $produkt): void // speichert Änderungen an einem bestehenden Produkt
    {
        $db = Database::getConnection(); // holt die gemeinsame Datenbankverbindung
        $stmt = $db->prepare( // bereitet die Update-Anweisung vor (schützt vor SQL-Injection)
            'UPDATE produkt SET kategorie_id = :kategorie_id, bezeichnung = :bezeichnung,
             beschreibung = :beschreibung, preis = :preis, bild_pfad = :bild_pfad,
             lagerbestand = :lagerbestand, aktiv = :aktiv
             WHERE produkt_id = :produkt_id'
        );
        $stmt->execute($this->toParams($produkt) + ['produkt_id' => $produkt->produktId]); // führt das Update aus, inkl. der zu ändernden ID
    }

    public function deaktivieren(int $produktId): void // Soft-Delete: Produkt im Shop ausblenden statt physisch löschen
    {
        $db = Database::getConnection(); // holt die gemeinsame Datenbankverbindung
        $stmt = $db->prepare('UPDATE produkt SET aktiv = 0 WHERE produkt_id = :id'); // bereitet die Update-Anweisung vor
        $stmt->execute(['id' => $produktId]); // setzt "aktiv" auf 0, bestehende Bestellpositionen bleiben gültig
    }

    private function toParams(Produkt $produkt): array // wandelt ein Produkt-Objekt in benannte SQL-Parameter um
    {
        return [
            'kategorie_id' => $produkt->kategorieId, // Kategorie-ID übernehmen
            'bezeichnung' => $produkt->bezeichnung, // Produktname übernehmen
            'beschreibung' => $produkt->beschreibung, // Beschreibung übernehmen
            'preis' => $produkt->preis, // Preis übernehmen
            'bild_pfad' => $produkt->bildPfad, // Bildpfad übernehmen
            'lagerbestand' => $produkt->lagerbestand, // Lagerbestand übernehmen
            'aktiv' => (int) $produkt->aktiv, // true/false in 1/0 umwandeln
        ];
    }

    private function hydrate(array $zeile): Produkt // wandelt eine DB-Zeile (Array) in ein Produkt-Objekt um
    {
        return new Produkt(
            produktId: (int) $zeile['produkt_id'], // ID aus der DB in ein int umwandeln
            kategorieId: (int) $zeile['kategorie_id'], // Kategorie-ID aus der DB in ein int umwandeln
            bezeichnung: $zeile['bezeichnung'], // Produktname unverändert übernehmen
            beschreibung: $zeile['beschreibung'], // Beschreibung unverändert übernehmen
            preis: (float) $zeile['preis'], // Preis aus der DB in eine Kommazahl umwandeln
            bildPfad: $zeile['bild_pfad'], // Bildpfad unverändert übernehmen
            lagerbestand: (int) $zeile['lagerbestand'], // Lagerbestand aus der DB in ein int umwandeln
            aktiv: (bool) $zeile['aktiv'] // 1/0 aus der DB in true/false umwandeln
        );
    }
}
