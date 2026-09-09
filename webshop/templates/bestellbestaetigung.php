<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestellbestätigung — Webshop</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- gemeinsames, responsives Stylesheet einbinden -->
</head>
<body>
    <div class="container"> <!-- begrenzt die Inhaltsbreite und sorgt für Randabstand auf allen Bildschirmgrößen -->
        <?php if (!$bestellung): // prüft, ob die Bestellung gefunden wurde ?>
            <p>Bestellung wurde nicht gefunden.</p>
        <?php else: // wenn die Bestellung vorhanden ist ?>
            <h1>Danke für deine Bestellung!</h1>
            <p>Bestellnummer: <?= (int) $bestellung->bestellungId /* Bestellnummer ausgeben */ ?></p>
            <p>Zahlungsart: <?= htmlspecialchars($bestellung->zahlungsart) /* gewählte Zahlungsart ausgeben */ ?></p>

            <div class="tabelle-wrapper"> <!-- ermöglicht horizontales Scrollen der Tabelle auf schmalen Bildschirmen -->
                <table>
                    <thead>
                        <tr><th>Produkt</th><th>Menge</th><th>Einzelpreis</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($positionen as $position): // jede bestellte Position ausgeben ?>
                            <tr>
                                <td><?= htmlspecialchars($position['produkt']->bezeichnung ?? 'unbekanntes Produkt') /* Produktname sicher ausgeben, Fallback falls gelöscht */ ?></td>
                                <td><?= (int) $position['menge'] /* Menge ausgeben */ ?></td>
                                <td><?= number_format($position['einzelpreis'], 2, ',', '.') /* Einzelpreis formatiert ausgeben */ ?> €</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <p><strong>Gesamtsumme: <?= number_format($bestellung->gesamtbetrag, 2, ',', '.') /* Gesamtsumme formatiert ausgeben */ ?> €</strong></p>
        <?php endif; ?>

        <p class="nav-links"><a href="index.php">Zurück zur Produktübersicht</a></p>
    </div>
</body>
</html>
