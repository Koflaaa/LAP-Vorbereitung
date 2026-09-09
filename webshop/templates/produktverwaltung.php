<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produktverwaltung — Webshop</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- gemeinsames, responsives Stylesheet einbinden -->
    <style>
        .verwaltung-formular { display: inline-block; margin: 0; } /* Formular nimmt nur so viel Platz wie der Button, nicht die ganze Zeile */
        .verwaltung-formular button { width: auto; margin-top: 0; } /* Button behält seine Textbreite statt der globalen 100% */
    </style>
</head>
<body>
    <?php require __DIR__ . '/partials/nav.php'; // bindet die gemeinsame Navigation mit Login-Status ein ?>
    <div class="container"> <!-- begrenzt die Inhaltsbreite und sorgt für Randabstand auf allen Bildschirmgrößen -->
        <h1>Produktverwaltung</h1>
        <p><a href="produkt_formular.php">Neues Produkt anlegen</a></p>

        <div class="tabelle-wrapper"> <!-- ermöglicht horizontales Scrollen der Tabelle auf schmalen Bildschirmen -->
            <table>
                <thead>
                    <tr><th>Bezeichnung</th><th>Preis</th><th>Lager</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    <?php foreach ($produkte as $produkt): // jedes Produkt (auch deaktivierte) durchgehen ?>
                        <tr>
                            <td><?= htmlspecialchars($produkt->bezeichnung) /* Produktname sicher ausgeben */ ?></td>
                            <td><?= number_format($produkt->preis, 2, ',', '.') /* Preis formatiert ausgeben */ ?> €</td>
                            <td><?= (int) $produkt->lagerbestand /* Lagerbestand ausgeben */ ?></td>
                            <td><?= $produkt->aktiv ? 'aktiv' : 'deaktiviert' /* Status als Text ausgeben */ ?></td>
                            <td>
                                <a href="produkt_formular.php?id=<?= (int) $produkt->produktId /* zu bearbeitendes Produkt festlegen */ ?>">Bearbeiten</a>
                                <?php if ($produkt->aktiv): // Löschen nur anbieten, wenn das Produkt noch aktiv ist ?>
                                    <form class="verwaltung-formular" action="produkt_loeschen.php" method="post"> <!-- Formular zum Deaktivieren des Produkts -->
                                        <input type="hidden" name="produkt_id" value="<?= (int) $produkt->produktId /* betroffenes Produkt festlegen */ ?>">
                                        <button type="submit" onclick="return confirm('Produkt wirklich löschen?')">Löschen</button> <!-- fragt vor dem Löschen nach Bestätigung -->
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
