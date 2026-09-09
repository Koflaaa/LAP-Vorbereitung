<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechnung — Webshop</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- gemeinsames, responsives Stylesheet einbinden -->
    <style>
        .rechnung { max-width: 700px; margin: 0 auto; padding: 1.5rem; border: 1px solid var(--farbe-rand); } /* Rechnung wirkt wie ein eigenständiges Blatt Papier */
        .rechnung-kopf { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; } /* Absender und Rechnungsdaten nebeneinander */
        .rechnung-adressen { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; } /* Rechnungs- und Lieferadresse nebeneinander */
        .rechnung table { margin-top: 1rem; } /* Abstand der Positionstabelle zum Text darüber */
        .rechnung-summe { text-align: right; margin-top: 1rem; font-size: 1.1rem; } /* Gesamtsumme deutlich hervorgehoben */

        @media print { /* Regeln, die nur beim Drucken/Als-PDF-Speichern gelten */
            .hauptnav, .nav-links, .rechnung-drucken { display: none; } /* Navigation und Button sollen nicht mitgedruckt werden */
            .rechnung { border: none; } /* auf Papier braucht es keinen Rahmen */
        }
    </style>
</head>
<body>
    <?php require __DIR__ . '/partials/nav.php'; // bindet die gemeinsame Navigation mit Login-Status ein (wird beim Drucken ausgeblendet) ?>
    <div class="container"> <!-- begrenzt die Inhaltsbreite und sorgt für Randabstand auf allen Bildschirmgrößen -->
        <?php if (!$bestellung): // prüft, ob die Bestellung gefunden wurde ?>
            <p>Rechnung wurde nicht gefunden.</p>
        <?php else: // wenn die Bestellung vorhanden ist ?>
            <p class="rechnung-drucken"><button type="button" onclick="window.print()">Rechnung drucken / als PDF speichern</button></p> <!-- löst den Browser-Druckdialog aus -->

            <div class="rechnung"> <!-- eigentlicher Rechnungsinhalt -->
                <div class="rechnung-kopf">
                    <div>
                        <strong>Webshop GmbH</strong><br> <!-- Platzhalter für die Absenderdaten des Shopbetreibers -->
                        Musterstraße 1, 1010 Wien<br>
                        UID: ATU00000000
                    </div>
                    <div>
                        <strong>Rechnung Nr. <?= (int) $bestellung->bestellungId /* Bestellnummer dient hier als Rechnungsnummer */ ?></strong><br>
                        Datum: <?= htmlspecialchars($bestellung->bestelldatum) /* Rechnungsdatum sicher ausgeben */ ?><br>
                        Zahlungsart: <?= htmlspecialchars($bestellung->zahlungsart) /* gewählte Zahlungsart sicher ausgeben */ ?>
                    </div>
                </div>

                <div class="rechnung-adressen">
                    <div>
                        <strong>Rechnungsadresse</strong><br>
                        <?= htmlspecialchars($kunde->vorname . ' ' . $kunde->nachname) /* Kundenname sicher ausgeben */ ?><br>
                        <?= htmlspecialchars($rechnungsadresse->strasse . ' ' . $rechnungsadresse->hausnummer) /* Straße und Hausnummer sicher ausgeben */ ?><br>
                        <?= htmlspecialchars($rechnungsadresse->plz . ' ' . $rechnungsadresse->ort) /* PLZ und Ort sicher ausgeben */ ?><br>
                        <?= htmlspecialchars($rechnungsadresse->land) /* Land sicher ausgeben */ ?><br>
                        <?= htmlspecialchars($kunde->email) /* E-Mail sicher ausgeben */ ?>
                    </div>
                    <div>
                        <strong>Lieferadresse</strong><br>
                        <?= htmlspecialchars($lieferadresse->strasse . ' ' . $lieferadresse->hausnummer) /* Straße und Hausnummer sicher ausgeben */ ?><br>
                        <?= htmlspecialchars($lieferadresse->plz . ' ' . $lieferadresse->ort) /* PLZ und Ort sicher ausgeben */ ?><br>
                        <?= htmlspecialchars($lieferadresse->land) /* Land sicher ausgeben */ ?>
                    </div>
                </div>

                <div class="tabelle-wrapper"> <!-- ermöglicht horizontales Scrollen der Tabelle auf schmalen Bildschirmen -->
                    <table>
                        <thead>
                            <tr><th>Produkt</th><th>Menge</th><th>Einzelpreis</th><th>Summe</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($positionen as $position): // jede Rechnungsposition ausgeben ?>
                                <tr>
                                    <td><?= htmlspecialchars($position['produkt']->bezeichnung ?? 'unbekanntes Produkt') /* Produktname sicher ausgeben, Fallback falls gelöscht */ ?></td>
                                    <td><?= (int) $position['menge'] /* Menge ausgeben */ ?></td>
                                    <td><?= number_format($position['einzelpreis'], 2, ',', '.') /* Einzelpreis formatiert ausgeben */ ?> €</td>
                                    <td><?= number_format($position['zwischensumme'], 2, ',', '.') /* Zeilensumme formatiert ausgeben */ ?> €</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <p class="rechnung-summe"><strong>Gesamtsumme: <?= number_format($bestellung->gesamtbetrag, 2, ',', '.') /* Gesamtsumme formatiert ausgeben */ ?> €</strong></p>
                <p>Alle Preise inkl. gesetzlicher Umsatzsteuer.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
