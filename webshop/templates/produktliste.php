<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produkte — Webshop</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- gemeinsames, responsives Stylesheet einbinden -->
</head>
<body>
    <div class="container"> <!-- begrenzt die Inhaltsbreite und sorgt für Randabstand auf allen Bildschirmgrößen -->
        <p class="nav-links"><a href="warenkorb.php">Warenkorb</a> <a href="statistik.php">Statistik</a> <a href="produktverwaltung.php">Produktverwaltung</a></p>
        <h1>Produktübersicht</h1>

        <?php if (empty($produkte)): // prüft, ob die Produktliste leer ist ?>
            <p>Aktuell sind keine Produkte verfügbar.</p>
        <?php else: // wenn Produkte vorhanden sind ?>
            <div class="produkt-grid">
                <?php foreach ($produkte as $produkt): // geht jedes Produkt-Objekt aus der Liste durch ?>
                    <div class="produkt-karte">
                        <a href="produkt.php?id=<?= (int) $produkt->produktId /* Produkt-ID für den Detail-Link */ ?>">
                            <?php if ($produkt->bildPfad): // nur ein Bild anzeigen, wenn ein Pfad hinterlegt ist ?>
                                <img src="assets/img/produkte/<?= htmlspecialchars(basename($produkt->bildPfad)) /* Dateinamen sicher ausgeben (schützt vor XSS) */ ?>" alt="<?= htmlspecialchars($produkt->bezeichnung) /* Bildbeschreibung sicher ausgeben */ ?>">
                            <?php else: // kein Bild hinterlegt ?>
                                <p>Kein Bild vorhanden</p>
                            <?php endif; ?>
                            <h2><?= htmlspecialchars($produkt->bezeichnung) /* Produktname sicher ausgeben */ ?></h2>
                            <p><?= number_format($produkt->preis, 2, ',', '.') /* Preis mit Komma und 2 Nachkommastellen formatieren */ ?> €</p>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
