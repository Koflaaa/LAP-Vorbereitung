# Zeitschätzung — Webshop-Projekt

Annahme: ein Entwickler, Tech-Stack PHP (objektorientiert) + MySQL, inkl. Testen und Doku je Punkt.

| # | Arbeitspaket | Stunden |
|---|---|---|
| 0 | Projektsetup (Ordnerstruktur, DB-Verbindung, Autoloading, Git) | 2 |
| 1 | DB-Design (ER-Diagramm), Zeitschätzung, Grundgerüst OOP-Klassen | 4 |
| 2a–c | Produktliste (Übersicht + Detailansicht), DB-Anbindung, Bildverwaltung | 8 |
| 3a | Warenkorb (Session-basiert, hinzufügen/entfernen/Menge ändern) | 5 |
| 3b | Kundendaten-Formular (Rechnungs-/Lieferadresse, Kontaktdaten, Validierung) | 5 |
| 3c | Zahlungsart-Auswahl (Rechnung/Kreditkarte) + Bestellabschluss | 3 |
| 4 | Responsives Layout (CSS, Breakpoints, Test auf Mobile/Tablet/Desktop) | 6 |
| 5 | UI/UX-Feinschliff (Navigation, Styling, Usability-Test) | 6 |
| 6a–c | Statistikseite (Top-5/Flop-5, Bestellungen letzte 4 Wochen, SQL-Aggregation, Diagramme) | 6 |
| 7 | Anforderungsliste Hosting (Recherche + Dokumentation) | 2 |
| Zusatz a | Produkt-CRUD (Anlegen/Editieren/Löschen inkl. Formulare & Validierung) | 6 |
| Zusatz b | Kundenkonto (Registrierung, Login, Passwort-Hashing, Session-Auth) | 6 |
| Zusatz c | Rechnung als HTML (Template, Berechnung, Druckansicht) | 4 |
| — | Testdaten (20 Produkte, 5 Kunden, 10 Bestellungen) | 2 |
| — | Gesamttest, Bugfixing, Dokumentation finalisieren | 6 |
| **Summe** | | **~71 Stunden** |

## Annahmen / Einflussfaktoren

- Geschätzt für einen Entwickler mit Grundkenntnissen in PHP/SQL/HTML/CSS (Niveau LAP-Kandidat).
- Keine externen Zahlungs-Gateways werden real angebunden (nur Auswahl der Zahlungsart lt. Angabe).
- Bei Umsetzung über mehrere Praxistage verteilt (z. B. 6–7 Arbeitstage à ~10 Stunden), realistisch planbar über 2 Wochen bei Teilzeitarbeit am Projekt.
- Pufferzeit für unvorhergesehene Probleme (Browser-Kompatibilität, Deployment) ist in "Gesamttest, Bugfixing" bereits mitgedacht, sollte bei realer Umsetzung aber zusätzlich mit ca. 10 % Reserve versehen werden (~7 Std.).
