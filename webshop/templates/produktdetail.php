<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produktdetail — Webshop</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- gemeinsames, responsives Stylesheet einbinden -->
    <style>
        .produkt-bild { max-width: 300px; } /* Detailbild auf sinnvolle Größe begrenzen, statt volle Containerbreite */
    </style>
</head>
<body>
    <?php require __DIR__ . '/partials/nav.php'; // bindet die gemeinsame Navigation mit Login-Status ein ?>
    <div class="container"> <!-- begrenzt die Inhaltsbreite und sorgt für Randabstand auf allen Bildschirmgrößen -->
        <?php if (!$produkt): // prüft, ob das Produkt gefunden wurde ?>
            <p>Produkt wurde nicht gefunden.</p>
        <?php else: // wenn das Produkt vorhanden ist ?>
            <?php if ($produkt->bildPfad): // nur ein Bild anzeigen, wenn ein Pfad hinterlegt ist ?>
                <img class="produkt-bild" src="assets/img/produkte/<?= htmlspecialchars(basename($produkt->bildPfad)) /* Dateinamen sicher ausgeben (schützt vor XSS) */ ?>" alt="<?= htmlspecialchars($produkt->bezeichnung) /* Bildbeschreibung sicher ausgeben */ ?>">
            <?php endif; ?>

            <h1><?= htmlspecialchars($produkt->bezeichnung) /* Produktname sicher ausgeben */ ?></h1>
            <p><strong><?= number_format($produkt->preis, 2, ',', '.') /* Preis formatiert ausgeben */ ?> €</strong></p>
            <p><?= nl2br(htmlspecialchars($produkt->beschreibung ?? '')) /* Beschreibung sicher ausgeben, Zeilenumbrüche erhalten */ ?></p>
            <p>Kategorie: <?= htmlspecialchars($kategorie->bezeichnung ?? 'unbekannt') /* Kategoriename sicher ausgeben, Fallback falls keine Kategorie gefunden wurde */ ?></p>
            <p>Lagerbestand: <?= (int) $produkt->lagerbestand /* verfügbare Stückzahl als Zahl ausgeben */ ?> Stück</p>

            <form action="warenkorb.php" method="post"> <!-- Formular zum Hinzufügen ins Warenkorb -->
                <input type="hidden" name="action" value="hinzufuegen"> <!-- legt die Aktion für den Controller fest -->
                <input type="hidden" name="produkt_id" value="<?= (int) $produkt->produktId /* betroffenes Produkt festlegen */ ?>">
                <label>Menge <input type="number" name="menge" min="1" value="1"></label> <!-- Eingabefeld für die gewünschte Menge -->
                <button type="submit">In den Warenkorb</button> <!-- sendet das Formular ab -->
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
