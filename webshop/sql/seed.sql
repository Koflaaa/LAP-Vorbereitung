-- Minimale Testdaten, damit Produktliste und Detailseite lokal testbar sind.
-- Die vollständigen Testdaten (mind. 20 Produkte, 5 Kunden, 10 Bestellungen) folgen als eigener Schritt.
USE webshop; -- wählt die Ziel-Datenbank aus

-- zwei Beispielkategorien anlegen
INSERT INTO kategorie (bezeichnung) VALUES
    ('Getränke'), -- erste Kategorie
    ('Snacks'); -- zweite Kategorie

-- vier Beispielprodukte anlegen, ohne Bild (bild_pfad bleibt NULL)
INSERT INTO produkt (kategorie_id, bezeichnung, beschreibung, preis, bild_pfad, lagerbestand) VALUES
    (1, 'Mineralwasser 0,5l', 'Stilles Mineralwasser in der Glasflasche.', 1.50, NULL, 100), -- Produkt 1, Kategorie "Getränke"
    (1, 'Apfelsaft 1l', 'Naturtrüber Apfelsaft, regional gepresst.', 2.90, NULL, 50), -- Produkt 2, Kategorie "Getränke"
    (2, 'Kartoffelchips 200g', 'Knusprige Chips, klassisch gesalzen.', 2.20, NULL, 80), -- Produkt 3, Kategorie "Snacks"
    (2, 'Schokoriegel', 'Vollmilchschokolade mit Karamellfüllung.', 1.10, NULL, 120); -- Produkt 4, Kategorie "Snacks"

-- ein Beispielkunde mit Rechnungs- und Lieferadresse, damit die Statistikseite (Punkt 6) etwas anzeigen kann
INSERT INTO kunde (vorname, nachname, email, telefon) VALUES
    ('Max', 'Mustermann', 'max.mustermann@example.com', '+43 660 1234567'); -- Testkunde ohne Konto (passwort_hash bleibt NULL)

INSERT INTO adresse (kunde_id, typ, strasse, hausnummer, plz, ort, land) VALUES
    (1, 'rechnung', 'Hauptstraße', '1', '1010', 'Wien', 'Österreich'), -- Rechnungsadresse des Testkunden
    (1, 'lieferung', 'Hauptstraße', '1', '1010', 'Wien', 'Österreich'); -- identische Lieferadresse

-- zwei Bestellungen: eine aktuelle (innerhalb der letzten 4 Wochen) und eine ältere (außerhalb), zum Testen des Statistik-Filters
INSERT INTO bestellung (kunde_id, rechnungsadresse_id, lieferadresse_id, bestelldatum, status, zahlungsart, gesamtbetrag) VALUES
    (1, 1, 2, NOW() - INTERVAL 3 DAY, 'bezahlt', 'kreditkarte', 8.50), -- aktuelle Bestellung
    (1, 1, 2, NOW() - INTERVAL 8 WEEK, 'versandt', 'rechnung', 4.40); -- ältere Bestellung, fällt aus dem 4-Wochen-Filter

-- Positionen der aktuellen Bestellung (bestellung_id 1): 2x Mineralwasser, 1x Apfelsaft
INSERT INTO bestellposition (bestellung_id, produkt_id, menge, einzelpreis) VALUES
    (1, 1, 2, 1.50),
    (1, 2, 1, 2.90);

-- Positionen der älteren Bestellung (bestellung_id 2): 2x Schokoriegel
INSERT INTO bestellposition (bestellung_id, produkt_id, menge, einzelpreis) VALUES
    (2, 4, 2, 1.10);
