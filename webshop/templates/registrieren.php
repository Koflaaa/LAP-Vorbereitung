<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrieren — Webshop</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- gemeinsames, responsives Stylesheet einbinden -->
</head>
<body>
    <?php require __DIR__ . '/partials/nav.php'; // bindet die gemeinsame Navigation mit Login-Status ein ?>
    <div class="container"> <!-- begrenzt die Inhaltsbreite und sorgt für Randabstand auf allen Bildschirmgrößen -->
        <p class="nav-links"><a href="login.php">Bereits registriert? Zum Login</a></p>
        <h1>Kundenkonto erstellen</h1>

        <?php if (!empty($fehler)): // wenn Validierungsfehler vorhanden sind ?>
            <ul class="fehler">
                <?php foreach ($fehler as $meldung): // jede Fehlermeldung ausgeben ?>
                    <li><?= htmlspecialchars($meldung) /* Fehlermeldung sicher ausgeben */ ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action="registrieren.php" method="post"> <!-- Formular zur Registrierung -->
            <fieldset>
                <legend>Kundenkonto</legend>
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
                <label>Passwort (mind. 8 Zeichen)
                    <input type="password" name="passwort"> <!-- Passwörter werden aus Sicherheitsgründen nie vorbelegt -->
                </label>
                <label>Passwort wiederholen
                    <input type="password" name="passwort_wiederholen">
                </label>
            </fieldset>

            <button type="submit">Konto erstellen</button> <!-- sendet das Formular ab -->
        </form>
    </div>
</body>
</html>
