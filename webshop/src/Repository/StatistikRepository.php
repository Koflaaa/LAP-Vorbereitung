<?php

namespace Webshop\Repository; // Namensraum für alle Repository-Klassen (Datenbankzugriff)

require_once __DIR__ . '/../Core/Database.php'; // bindet die Datenbankverbindung manuell ein (kein Autoloader)

use Webshop\Core\Database; // stellt die Datenbankverbindung bereit

class StatistikRepository // liefert aggregierte Auswertungen für die Statistikseite (Punkt 6)
{
    public function meistBestellteProdukte(int $anzahl = 5): array // liefert die N meistbestellten Produkte
    {
        $db = Database::getConnection(); // holt die gemeinsame Datenbankverbindung
        $limit = (int) $anzahl; // sicherstellen, dass wirklich eine Zahl in die Query kommt (kein SQL-Injection-Risiko)

        $sql = 'SELECT p.produkt_id, p.bezeichnung, SUM(bp.menge) AS gesamtmenge
                FROM bestellposition bp
                JOIN produkt p ON p.produkt_id = bp.produkt_id
                GROUP BY p.produkt_id, p.bezeichnung
                ORDER BY gesamtmenge DESC
                LIMIT ' . $limit; // Query: Produkte nach bestellter Gesamtmenge absteigend sortiert

        return $db->query($sql)->fetchAll(); // Abfrage ausführen und alle Ergebniszeilen zurückgeben
    }

    public function seltenBestellteProdukte(int $anzahl = 5): array // liefert die N am seltensten bestellten Produkte
    {
        $db = Database::getConnection(); // holt die gemeinsame Datenbankverbindung
        $limit = (int) $anzahl; // sicherstellen, dass wirklich eine Zahl in die Query kommt (kein SQL-Injection-Risiko)

        $sql = 'SELECT p.produkt_id, p.bezeichnung, COALESCE(SUM(bp.menge), 0) AS gesamtmenge
                FROM produkt p
                LEFT JOIN bestellposition bp ON bp.produkt_id = p.produkt_id
                GROUP BY p.produkt_id, p.bezeichnung
                ORDER BY gesamtmenge ASC
                LIMIT ' . $limit; // Query: auch nie bestellte Produkte (0) werden mitgezählt

        return $db->query($sql)->fetchAll(); // Abfrage ausführen und alle Ergebniszeilen zurückgeben
    }

    public function bestellungenLetzteVierWochen(): array // liefert alle Bestellungen der letzten vier Wochen
    {
        $db = Database::getConnection(); // holt die gemeinsame Datenbankverbindung

        $sql = 'SELECT b.bestellung_id, b.bestelldatum, b.status, b.zahlungsart, b.gesamtbetrag,
                       k.vorname, k.nachname
                FROM bestellung b
                JOIN kunde k ON k.kunde_id = b.kunde_id
                WHERE b.bestelldatum >= (NOW() - INTERVAL 4 WEEK)
                ORDER BY b.bestelldatum DESC'; // Query: Bestellungen der letzten 4 Wochen, neueste zuerst

        return $db->query($sql)->fetchAll(); // Abfrage ausführen und alle Ergebniszeilen zurückgeben
    }
}
