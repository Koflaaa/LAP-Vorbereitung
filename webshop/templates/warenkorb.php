<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warenkorb — Webshop</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- gemeinsames, responsives Stylesheet einbinden -->
    <style>
        .warenkorb-formular { display: inline-block; } /* Mengen-/Entfernen-Formulare bleiben in der Tabellenzelle nebeneinander */
        .warenkorb-formular input[type="number"] { width: 4rem; display: inline-block; } /* Mengenfeld schmal statt volle Breite */
        .warenkorb-formular button { width: auto; margin-top: 0; } /* Buttons in der Tabelle nicht volle Breite */
    </style>
</head>
<body>
    <div class="container"> <!-- begrenzt die Inhaltsbreite und sorgt für Randabstand auf allen Bildschirmgrößen -->
        <p class="nav-links"><a href="index.php">&larr; Zurück zur Produktübersicht</a></p>
        <h1>Warenkorb</h1>

        <?php if (empty($positionen)): // prüft, ob der Warenkorb leer ist ?>
            <p>Dein Warenkorb ist leer.</p>
        <?php else: // wenn Positionen vorhanden sind ?>
            <div class="tabelle-wrapper"> <!-- ermöglicht horizontales Scrollen der Tabelle auf schmalen Bildschirmen -->
                <table>
                    <thead>
                        <tr>
                            <th>Produkt</th>
                            <th>Preis</th>
                            <th>Menge</th>
                            <th>Zwischensumme</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($positionen as $position): // jede Warenkorb-Zeile durchgehen ?>
                            <?php $produkt = $position['produkt']; // Produkt-Objekt dieser Zeile herausholen ?>
                            <tr>
                                <td><?= htmlspecialchars($produkt->bezeichnung) /* Produktname sicher ausgeben */ ?></td>
                                <td><?= number_format($produkt->preis, 2, ',', '.') /* Einzelpreis formatiert ausgeben */ ?> €</td>
                                <td>
                                    <form class="warenkorb-formular" action="warenkorb.php" method="post"> <!-- Formular zum Ändern der Menge -->
                                        <input type="hidden" name="action" value="aendern"> <!-- legt die Aktion für den Controller fest -->
                                        <input type="hidden" name="produkt_id" value="<?= (int) $produkt->produktId /* betroffenes Produkt festlegen */ ?>">
                                        <input type="number" name="menge" min="0" value="<?= (int) $position['menge'] /* aktuelle Menge vorbelegen */ ?>"> <!-- Eingabefeld für die neue Menge -->
                                        <button type="submit">Ändern</button> <!-- sendet das Formular ab -->
                                    </form>
                                </td>
                                <td><?= number_format($position['zwischensumme'], 2, ',', '.') /* Zwischensumme formatiert ausgeben */ ?> €</td>
                                <td>
                                    <form class="warenkorb-formular" action="warenkorb.php" method="post"> <!-- Formular zum Entfernen der Position -->
                                        <input type="hidden" name="action" value="entfernen"> <!-- legt die Aktion für den Controller fest -->
                                        <input type="hidden" name="produkt_id" value="<?= (int) $produkt->produktId /* betroffenes Produkt festlegen */ ?>">
                                        <button type="submit">Entfernen</button> <!-- sendet das Formular ab -->
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <p><strong>Gesamtsumme: <?= number_format($gesamtbetrag, 2, ',', '.') /* Gesamtsumme formatiert ausgeben */ ?> €</strong></p>
            <p><a href="kasse.php">Zur Kasse</a></p> <!-- führt zur Eingabe der Kundendaten (Schritt 3b) -->
        <?php endif; ?>
    </div>
</body>
</html>
