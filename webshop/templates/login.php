<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Webshop</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- gemeinsames, responsives Stylesheet einbinden -->
</head>
<body>
    <?php require __DIR__ . '/partials/nav.php'; // bindet die gemeinsame Navigation mit Login-Status ein ?>
    <div class="container"> <!-- begrenzt die Inhaltsbreite und sorgt für Randabstand auf allen Bildschirmgrößen -->
        <p class="nav-links"><a href="registrieren.php">Noch kein Konto? Jetzt registrieren</a></p>
        <h1>Login</h1>

        <?php if (!empty($fehler)): // wenn Validierungsfehler vorhanden sind ?>
            <ul class="fehler">
                <?php foreach ($fehler as $meldung): // jede Fehlermeldung ausgeben ?>
                    <li><?= htmlspecialchars($meldung) /* Fehlermeldung sicher ausgeben */ ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action="login.php" method="post"> <!-- Formular zum Einloggen -->
            <fieldset>
                <legend>Login</legend>
                <label>E-Mail-Adresse
                    <input type="email" name="email">
                </label>
                <label>Passwort
                    <input type="password" name="passwort">
                </label>
            </fieldset>

            <button type="submit">Einloggen</button> <!-- sendet das Formular ab -->
        </form>
    </div>
</body>
</html>
