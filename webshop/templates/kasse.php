<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasse — Webshop</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- gemeinsames, responsives Stylesheet einbinden -->
</head>
<body>
    <?php require __DIR__ . '/partials/nav.php'; // bindet die gemeinsame Navigation mit Login-Status ein ?>
    <div class="container"> <!-- begrenzt die Inhaltsbreite und sorgt für Randabstand auf allen Bildschirmgrößen -->
        <h1>Kasse — Kundendaten</h1>

        <?php if ($eingeloggterKunde): // Kontaktdaten schon bekannt, nur kurz begrüßen ?>
            <p>Angemeldet als <?= htmlspecialchars($eingeloggterKunde->vorname . ' ' . $eingeloggterKunde->nachname) /* Vor- und Nachname des angemeldeten Kunden ausgeben */ ?> (<?= htmlspecialchars($eingeloggterKunde->email) /* E-Mail ausgeben */ ?>)</p>
        <?php endif; ?>

        <?php if (!empty($fehler)): // wenn Validierungsfehler vorhanden sind ?>
            <ul class="fehler">
                <?php foreach ($fehler as $meldung): // jede Fehlermeldung ausgeben ?>
                    <li><?= htmlspecialchars($meldung) /* Fehlermeldung sicher ausgeben */ ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action="kasse.php" method="post"> <!-- Formular für Kundendaten und Adressen -->
            <div class="formular-spalten"> <!-- ordnet die Blöcke auf breiten Bildschirmen nebeneinander, auf schmalen untereinander an -->
                <?php if (!$eingeloggterKunde): // Kontaktdaten nur bei Gastbestellung abfragen ?>
                    <fieldset>
                        <legend>Kontaktdaten</legend>
                        <label>Vorname
                            <input type="text" name="vorname" value="<?= htmlspecialchars($eingabe['vorname'] ?? '') /* zuletzt eingegebenen Wert vorbelegen */ ?>">
                        </label>
                        <label>Nachname
                            <input type="text" name="nachname" value="<?= htmlspecialchars($eingabe['nachname'] ?? '') /* zuletzt eingegebenen Wert vorbelegen */ ?>">
                        </label>
                        <label>E-Mail-Adresse
                            <input type="email" name="email" value="<?= htmlspecialchars($eingabe['email'] ?? '') /* zuletzt eingegebenen Wert vorbelegen */ ?>">
                        </label>
                        <label>Telefonnummer
                            <input type="text" name="telefon" value="<?= htmlspecialchars($eingabe['telefon'] ?? '') /* zuletzt eingegebenen Wert vorbelegen */ ?>">
                        </label>
                    </fieldset>
                <?php endif; ?>

                <fieldset>
                    <legend>Rechnungsadresse</legend>
                    <label>Straße
                        <input type="text" name="r_strasse" value="<?= htmlspecialchars($eingabe['r_strasse'] ?? $gespeicherteRechnung?->strasse ?? '') /* Eingabe, sonst gespeicherte Adresse, sonst leer */ ?>">
                    </label>
                    <label>Hausnummer
                        <input type="text" name="r_hausnummer" value="<?= htmlspecialchars($eingabe['r_hausnummer'] ?? $gespeicherteRechnung?->hausnummer ?? '') /* Eingabe, sonst gespeicherte Adresse, sonst leer */ ?>">
                    </label>
                    <label>PLZ
                        <input type="text" name="r_plz" value="<?= htmlspecialchars($eingabe['r_plz'] ?? $gespeicherteRechnung?->plz ?? '') /* Eingabe, sonst gespeicherte Adresse, sonst leer */ ?>">
                    </label>
                    <label>Ort
                        <input type="text" name="r_ort" value="<?= htmlspecialchars($eingabe['r_ort'] ?? $gespeicherteRechnung?->ort ?? '') /* Eingabe, sonst gespeicherte Adresse, sonst leer */ ?>">
                    </label>
                    <label>Land
                        <input type="text" name="r_land" value="<?= htmlspecialchars($eingabe['r_land'] ?? $gespeicherteRechnung?->land ?? 'Österreich') /* Eingabe, sonst gespeicherte Adresse, sonst Standardwert */ ?>">
                    </label>
                </fieldset>

                <fieldset id="lieferadresse-block">
                    <legend>Lieferadresse</legend>
                    <label>
                        <input type="checkbox" id="identisch" name="lieferadresse_identisch" <?= isset($eingabe['lieferadresse_identisch']) || $eingabe === [] ? 'checked' : '' /* beim ersten Aufruf oder wenn zuvor angehakt vorbelegen */ ?>>
                        entspricht der Rechnungsadresse
                    </label>
                    <div id="lieferadresse-felder"> <!-- die eigentlichen Felder, werden bei "identisch" ausgeblendet -->
                        <label>Straße
                            <input type="text" name="l_strasse" value="<?= htmlspecialchars($eingabe['l_strasse'] ?? $gespeicherteLieferung?->strasse ?? '') /* Eingabe, sonst gespeicherte Adresse, sonst leer */ ?>">
                        </label>
                        <label>Hausnummer
                            <input type="text" name="l_hausnummer" value="<?= htmlspecialchars($eingabe['l_hausnummer'] ?? $gespeicherteLieferung?->hausnummer ?? '') /* Eingabe, sonst gespeicherte Adresse, sonst leer */ ?>">
                        </label>
                        <label>PLZ
                            <input type="text" name="l_plz" value="<?= htmlspecialchars($eingabe['l_plz'] ?? $gespeicherteLieferung?->plz ?? '') /* Eingabe, sonst gespeicherte Adresse, sonst leer */ ?>">
                        </label>
                        <label>Ort
                            <input type="text" name="l_ort" value="<?= htmlspecialchars($eingabe['l_ort'] ?? $gespeicherteLieferung?->ort ?? '') /* Eingabe, sonst gespeicherte Adresse, sonst leer */ ?>">
                        </label>
                        <label>Land
                            <input type="text" name="l_land" value="<?= htmlspecialchars($eingabe['l_land'] ?? $gespeicherteLieferung?->land ?? 'Österreich') /* Eingabe, sonst gespeicherte Adresse, sonst Standardwert */ ?>">
                        </label>
                    </div>
                </fieldset>
            </div>

            <button type="submit">Weiter</button> <!-- sendet das Formular ab -->
        </form>

        <script>
            var checkbox = document.getElementById('identisch'); // Checkbox-Element holen
            var felder = document.getElementById('lieferadresse-felder'); // Block mit den Lieferadress-Feldern holen

            function felder_umschalten() { // blendet die Lieferadress-Felder je nach Checkbox ein oder aus
                felder.hidden = checkbox.checked; // ausblenden, wenn Checkbox angehakt ist
            }

            checkbox.addEventListener('change', felder_umschalten); // bei jeder Änderung neu umschalten
            felder_umschalten(); // Zustand beim Laden der Seite einmal setzen
        </script>
    </div>
</body>
</html>
