<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik — Webshop</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- gemeinsames, responsives Stylesheet einbinden -->
</head>
<body>
    <?php require __DIR__ . '/partials/nav.php'; // bindet die gemeinsame Navigation mit Login-Status ein ?>
    <div class="container"> <!-- begrenzt die Inhaltsbreite und sorgt für Randabstand auf allen Bildschirmgrößen -->
        <h1>Statistik</h1>

        <h2>Die 5 meistbestellten Produkte</h2>
        <div class="tabelle-wrapper"> <!-- ermöglicht horizontales Scrollen der Tabelle auf schmalen Bildschirmen -->
            <table>
                <thead>
                    <tr><th>Produkt</th><th>Bestellte Menge</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($topProdukte)): // wenn noch keine Bestellungen existieren ?>
                        <tr><td colspan="2">Noch keine Bestellungen vorhanden.</td></tr>
                    <?php else: // wenn Daten vorhanden sind ?>
                        <?php foreach ($topProdukte as $zeile): // jede Ergebniszeile ausgeben ?>
                            <tr>
                                <td><?= htmlspecialchars($zeile['bezeichnung']) /* Produktname sicher ausgeben */ ?></td>
                                <td><?= (int) $zeile['gesamtmenge'] /* bestellte Gesamtmenge ausgeben */ ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <h2>Die 5 am seltensten bestellten Produkte</h2>
        <div class="tabelle-wrapper"> <!-- ermöglicht horizontales Scrollen der Tabelle auf schmalen Bildschirmen -->
            <table>
                <thead>
                    <tr><th>Produkt</th><th>Bestellte Menge</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($flopProdukte)): // wenn keine Produkte existieren ?>
                        <tr><td colspan="2">Keine Produkte vorhanden.</td></tr>
                    <?php else: // wenn Daten vorhanden sind ?>
                        <?php foreach ($flopProdukte as $zeile): // jede Ergebniszeile ausgeben ?>
                            <tr>
                                <td><?= htmlspecialchars($zeile['bezeichnung']) /* Produktname sicher ausgeben */ ?></td>
                                <td><?= (int) $zeile['gesamtmenge'] /* bestellte Gesamtmenge ausgeben (0 = noch nie bestellt) */ ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <h2>Bestellungen der letzten 4 Wochen</h2>
        <div class="tabelle-wrapper"> <!-- ermöglicht horizontales Scrollen der Tabelle auf schmalen Bildschirmen -->
            <table>
                <thead>
                    <tr><th>Nr.</th><th>Datum</th><th>Kunde</th><th>Status</th><th>Zahlungsart</th><th>Summe</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($bestellungen)): // wenn keine Bestellungen in den letzten 4 Wochen vorliegen ?>
                        <tr><td colspan="6">Keine Bestellungen in den letzten 4 Wochen.</td></tr>
                    <?php else: // wenn Daten vorhanden sind ?>
                        <?php foreach ($bestellungen as $zeile): // jede Bestellung ausgeben ?>
                            <tr>
                                <td><?= (int) $zeile['bestellung_id'] /* Bestellnummer ausgeben */ ?></td>
                                <td><?= htmlspecialchars($zeile['bestelldatum']) /* Bestelldatum sicher ausgeben */ ?></td>
                                <td><?= htmlspecialchars($zeile['vorname'] . ' ' . $zeile['nachname']) /* Kundenname sicher ausgeben */ ?></td>
                                <td><?= htmlspecialchars($zeile['status']) /* Status sicher ausgeben */ ?></td>
                                <td><?= htmlspecialchars($zeile['zahlungsart']) /* Zahlungsart sicher ausgeben */ ?></td>
                                <td><?= number_format((float) $zeile['gesamtbetrag'], 2, ',', '.') /* Gesamtsumme formatiert ausgeben */ ?> €</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
