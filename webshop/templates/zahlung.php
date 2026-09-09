<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zahlungsart — Webshop</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- gemeinsames, responsives Stylesheet einbinden -->
    <style>
        .zahlungsart-option { display: block; margin-top: 0.5rem; } /* jede Zahlungsart-Option in eigener Zeile */
        .zahlungsart-option input { width: auto; display: inline; } /* Radiobutton nicht auf volle Breite ziehen */
    </style>
</head>
<body>
    <?php require __DIR__ . '/partials/nav.php'; // bindet die gemeinsame Navigation mit Login-Status ein ?>
    <div class="container"> <!-- begrenzt die Inhaltsbreite und sorgt für Randabstand auf allen Bildschirmgrößen -->
        <h1>Zahlungsart wählen</h1>

        <div class="tabelle-wrapper"> <!-- ermöglicht horizontales Scrollen der Tabelle auf schmalen Bildschirmen -->
            <table>
                <thead>
                    <tr><th>Produkt</th><th>Menge</th><th>Zwischensumme</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($positionen as $position): // jede Bestellzeile zur Übersicht ausgeben ?>
                        <tr>
                            <td><?= htmlspecialchars($position['produkt']->bezeichnung) /* Produktname sicher ausgeben */ ?></td>
                            <td><?= (int) $position['menge'] /* Menge ausgeben */ ?></td>
                            <td><?= number_format($position['zwischensumme'], 2, ',', '.') /* Zwischensumme formatiert ausgeben */ ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p><strong>Gesamtsumme: <?= number_format($gesamtbetrag, 2, ',', '.') /* Gesamtsumme formatiert ausgeben */ ?> €</strong></p>

        <?php if (!empty($fehler)): // wenn Validierungsfehler vorhanden sind ?>
            <ul class="fehler">
                <?php foreach ($fehler as $meldung): // jede Fehlermeldung ausgeben ?>
                    <li><?= htmlspecialchars($meldung) /* Fehlermeldung sicher ausgeben */ ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action="zahlung.php" method="post"> <!-- Formular zur Auswahl der Zahlungsart -->
            <label class="zahlungsart-option"><input type="radio" name="zahlungsart" value="rechnung" checked> Rechnung</label> <!-- erste Option, standardmäßig ausgewählt -->
            <label class="zahlungsart-option"><input type="radio" name="zahlungsart" value="kreditkarte"> Kreditkarte</label> <!-- zweite Option -->
            <button type="submit">Bestellung abschließen</button> <!-- sendet das Formular ab und schließt die Bestellung ab -->
        </form>
    </div>
</body>
</html>
