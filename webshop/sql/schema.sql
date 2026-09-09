-- Legt die Datenbank "webshop" mit Unicode-Zeichensatz an, falls sie noch nicht existiert
CREATE DATABASE IF NOT EXISTS webshop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- wählt "webshop" als Ziel-Datenbank für alle folgenden Befehle aus
USE webshop;

-- Tabelle für Produktkategorien
CREATE TABLE kategorie (
    kategorie_id INT AUTO_INCREMENT PRIMARY KEY, -- eindeutige, automatisch hochzählende ID
    bezeichnung VARCHAR(100) NOT NULL -- Name der Kategorie, z. B. "Getränke"
) ENGINE=InnoDB; -- InnoDB ermöglicht Fremdschlüssel-Beziehungen zu anderen Tabellen

-- Tabelle für Produkte
CREATE TABLE produkt (
    produkt_id INT AUTO_INCREMENT PRIMARY KEY, -- eindeutige ID des Produkts
    kategorie_id INT NOT NULL, -- verweist auf die zugehörige Kategorie
    bezeichnung VARCHAR(150) NOT NULL, -- Produktname
    beschreibung TEXT, -- ausführliche Produktbeschreibung
    preis DECIMAL(10,2) NOT NULL, -- Verkaufspreis mit 2 Nachkommastellen
    bild_pfad VARCHAR(255), -- Pfad zum Produktbild im Dateisystem (nicht das Bild selbst)
    lagerbestand INT NOT NULL DEFAULT 0, -- verfügbare Stückzahl, standardmäßig 0
    aktiv TINYINT(1) NOT NULL DEFAULT 1, -- 1 = im Shop sichtbar, 0 = deaktiviert (Soft-Delete)
    erstellt_am DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Anlagezeitpunkt, wird automatisch gesetzt
    FOREIGN KEY (kategorie_id) REFERENCES kategorie(kategorie_id) -- erlaubt nur existierende Kategorien
) ENGINE=InnoDB;

-- Tabelle für Kunden
CREATE TABLE kunde (
    kunde_id INT AUTO_INCREMENT PRIMARY KEY, -- eindeutige ID des Kunden
    vorname VARCHAR(100) NOT NULL, -- Vorname
    nachname VARCHAR(100) NOT NULL, -- Nachname
    email VARCHAR(150) NOT NULL UNIQUE, -- E-Mail-Adresse, muss eindeutig sein
    telefon VARCHAR(50), -- Telefonnummer, optional
    passwort_hash VARCHAR(255) NULL, -- gehashtes Passwort; NULL = Gastbestellung ohne Konto
    erstellt_am DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP -- Anlagezeitpunkt, wird automatisch gesetzt
) ENGINE=InnoDB;

-- Tabelle für Rechnungs-/Lieferadressen
CREATE TABLE adresse (
    adresse_id INT AUTO_INCREMENT PRIMARY KEY, -- eindeutige ID der Adresse
    kunde_id INT NOT NULL, -- verweist auf den zugehörigen Kunden
    typ ENUM('rechnung', 'lieferung') NOT NULL, -- unterscheidet Rechnungs- von Lieferadresse
    strasse VARCHAR(150) NOT NULL, -- Straßenname
    hausnummer VARCHAR(20) NOT NULL, -- Hausnummer
    plz VARCHAR(20) NOT NULL, -- Postleitzahl
    ort VARCHAR(100) NOT NULL, -- Ort/Stadt
    land VARCHAR(100) NOT NULL DEFAULT 'Österreich', -- Land, Standardwert Österreich
    FOREIGN KEY (kunde_id) REFERENCES kunde(kunde_id) ON DELETE CASCADE -- Adressen werden mitgelöscht, wenn der Kunde gelöscht wird
) ENGINE=InnoDB;

-- Tabelle für Bestellungen
CREATE TABLE bestellung (
    bestellung_id INT AUTO_INCREMENT PRIMARY KEY, -- eindeutige ID der Bestellung
    kunde_id INT NOT NULL, -- verweist auf den bestellenden Kunden
    rechnungsadresse_id INT NOT NULL, -- verweist auf die verwendete Rechnungsadresse
    lieferadresse_id INT NOT NULL, -- verweist auf die verwendete Lieferadresse
    bestelldatum DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Zeitpunkt der Bestellung, wird automatisch gesetzt
    status ENUM('offen', 'bezahlt', 'versandt', 'storniert') NOT NULL DEFAULT 'offen', -- aktueller Bearbeitungsstatus
    zahlungsart ENUM('rechnung', 'kreditkarte') NOT NULL, -- gewählte Zahlungsart
    gesamtbetrag DECIMAL(10,2) NOT NULL, -- Summe aller Bestellpositionen zum Bestellzeitpunkt
    FOREIGN KEY (kunde_id) REFERENCES kunde(kunde_id), -- erlaubt nur existierende Kunden
    FOREIGN KEY (rechnungsadresse_id) REFERENCES adresse(adresse_id), -- erlaubt nur existierende Adressen
    FOREIGN KEY (lieferadresse_id) REFERENCES adresse(adresse_id) -- erlaubt nur existierende Adressen
) ENGINE=InnoDB;

-- Tabelle für einzelne Positionen innerhalb einer Bestellung
CREATE TABLE bestellposition (
    bestellposition_id INT AUTO_INCREMENT PRIMARY KEY, -- eindeutige ID der Position
    bestellung_id INT NOT NULL, -- verweist auf die zugehörige Bestellung
    produkt_id INT NOT NULL, -- verweist auf das bestellte Produkt
    menge INT NOT NULL, -- bestellte Stückzahl
    einzelpreis DECIMAL(10,2) NOT NULL, -- Preis pro Stück zum Bestellzeitpunkt (unabhängig vom aktuellen Produktpreis)
    FOREIGN KEY (bestellung_id) REFERENCES bestellung(bestellung_id) ON DELETE CASCADE, -- Positionen werden mitgelöscht, wenn die Bestellung gelöscht wird
    FOREIGN KEY (produkt_id) REFERENCES produkt(produkt_id) -- erlaubt nur existierende Produkte
) ENGINE=InnoDB;
