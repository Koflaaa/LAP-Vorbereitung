<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $produkt ? 'Produkt bearbeiten' : 'Neues Produkt' /* Titel je nach Modus (Bearbeiten/Anlegen) */ ?> — Webshop</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- gemeinsames, responsives Stylesheet einbinden -->
</head>
<body>
    <?php require __DIR__ . '/partials/nav.php'; // bindet die gemeinsame Navigation mit Login-Status ein ?>
    <div class="container"> <!-- begrenzt die Inhaltsbreite und sorgt für Randabstand auf allen Bildschirmgrößen -->
        <h1><?= $produkt ? 'Produkt bearbeiten' : 'Neues Produkt anlegen' /* Überschrift je nach Modus */ ?></h1>

        <?php if (!empty($fehler)): // wenn Validierungsfehler vorhanden sind ?>
            <ul class="fehler">
                <?php foreach ($fehler as $meldung): // jede Fehlermeldung ausgeben ?>
                    <li><?= htmlspecialchars($meldung) /* Fehlermeldung sicher ausgeben */ ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action="produkt_formular.php" method="post" enctype="multipart/form-data"> <!-- multipart nötig für den Bild-Upload -->
            <input type="hidden" name="produkt_id" value="<?= (int) ($produkt->produktId ?? 0) /* bei Bearbeiten die ID mitsenden, sonst 0 */ ?>">

            <fieldset>
                <legend>Produktdaten</legend>
                <label>Bezeichnung
                    <input type="text" name="bezeichnung" value="<?= htmlspecialchars($eingabe['bezeichnung'] ?? $produkt->bezeichnung ?? '') /* eingegebenen oder gespeicherten Wert vorbelegen */ ?>">
                </label>
                <label>Beschreibung
                    <textarea name="beschreibung" rows="4"><?= htmlspecialchars($eingabe['beschreibung'] ?? $produkt->beschreibung ?? '') /* eingegebenen oder gespeicherten Wert vorbelegen */ ?></textarea>
                </label>
                <label>Preis (€)
                    <input type="text" name="preis" value="<?= htmlspecialchars($eingabe['preis'] ?? (string) ($produkt->preis ?? '') ) /* eingegebenen oder gespeicherten Wert vorbelegen */ ?>">
                </label>
                <label>Kategorie
                    <select name="kategorie_id"> <!-- Auswahlliste aller vorhandenen Kategorien -->
                        <?php foreach ($kategorien as $kategorie): // jede Kategorie als Option ausgeben ?>
                            <?php $ausgewaehlt = (int) ($eingabe['kategorie_id'] ?? $produkt->kategorieId ?? 0) === $kategorie->kategorieId; // prüft, ob diese Kategorie vorausgewählt sein soll ?>
                            <option value="<?= (int) $kategorie->kategorieId /* Wert der Option */ ?>" <?= $ausgewaehlt ? 'selected' : '' /* vorausgewählte Option markieren */ ?>>
                                <?= htmlspecialchars($kategorie->bezeichnung) /* Kategoriename sicher ausgeben */ ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Lagerbestand
                    <input type="text" name="lagerbestand" value="<?= htmlspecialchars($eingabe['lagerbestand'] ?? (string) ($produkt->lagerbestand ?? '0')) /* eingegebenen oder gespeicherten Wert vorbelegen */ ?>">
                </label>
                <label>
                    <input type="checkbox" name="aktiv" <?= ($produkt === null || $produkt->aktiv) ? 'checked' : '' /* bei neuem Produkt oder aktivem Produkt angehakt */ ?>>
                    im Shop sichtbar
                </label>
                <label>Produktbild <?= $produkt?->bildPfad ? '(vorhandenes Bild wird beibehalten, falls kein neues gewählt wird)' : '' /* Hinweis nur beim Bearbeiten mit vorhandenem Bild */ ?>
                    <input type="file" name="bild" accept="image/jpeg,image/png,image/webp"> <!-- optionaler Bild-Upload -->
                </label>
                <?php if ($produkt?->bildPfad): // aktuelles Bild anzeigen, falls vorhanden ?>
                    <img src="assets/img/produkte/<?= htmlspecialchars(basename($produkt->bildPfad)) /* Dateinamen sicher ausgeben */ ?>" alt="Aktuelles Produktbild" style="max-width:150px;margin-top:0.5rem;">
                <?php endif; ?>
            </fieldset>

            <button type="submit">Speichern</button> <!-- sendet das Formular ab -->
        </form>
    </div>
</body>
</html>
