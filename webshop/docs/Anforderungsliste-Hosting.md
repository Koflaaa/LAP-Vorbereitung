# Anforderungsliste Hostingumgebung

Damit der Webshop funktioniert und sicher betrieben werden kann, muss die Hostingumgebung folgende Punkte erfüllen.

## Software / Laufzeitumgebung

- **PHP ≥ 8.1** (verwendet typisierte Konstruktor-Properties und `readonly`-fähige Syntax)
- PHP-Erweiterung **`pdo_mysql`** aktiviert (Datenbankzugriff)
- PHP-Erweiterung **`fileinfo`** aktiviert (Validierung hochgeladener Produktbilder)
- **MySQL ≥ 8.0 oder MariaDB ≥ 10.4** (Unterstützung für `utf8mb4`, `ON UPDATE CURRENT_TIMESTAMP`)
- Schreibzugriff des Webservers auf `public/assets/img/produkte/` (Produktbild-Uploads, Zusatzaufgabe a)

## php.ini-Einstellungen

- `upload_max_filesize` und `post_max_size` ausreichend groß für Produktbilder (z. B. mind. 4 MB)
- `display_errors = Off` und `log_errors = On` im Produktivbetrieb (keine Fehlerdetails an Besucher ausliefern)
- `session.cookie_httponly = On`, `session.cookie_secure = On`, `session.cookie_samesite = Lax` (Session-Cookie gegen XSS/CSRF absichern)
- `expose_php = Off` (keine PHP-Version in HTTP-Headern preisgeben)

## Netzwerk / Server

- **HTTPS mit gültigem TLS-Zertifikat** (Kundendaten, Adressen und potenziell Zahlungsauswahl werden übertragen)
- Datenbankserver **nicht öffentlich erreichbar**, nur aus dem internen Netz des Webservers
- Firewall, die nur benötigte Ports (80/443) nach außen freigibt
- Ausreichend Speicherplatz für Datenbank-Wachstum und Produktbilder

## Datenschutz / Sicherheit

- Es werden personenbezogene Daten gespeichert (Name, Adresse, E-Mail, Telefonnummer, Passwort-Hash) → **DSGVO-konformer Betrieb** erforderlich (Serverstandort/AVV beachten)
- Passwörter werden nur als Hash (`password_hash()`, bcrypt) gespeichert, nie im Klartext — die Hostingumgebung muss dafür keine zusätzliche Vorkehrung treffen, darf aber keine Klartext-Logs der POST-Daten anlegen
- Regelmäßige Sicherheitsupdates für PHP, MySQL/MariaDB und den Webserver (Apache/nginx)

## Backup / Verfügbarkeit

- Tägliches Datenbank-Backup (Produkte, Kunden, Bestellungen sind sonst unwiederbringlich verloren)
- Backup des Bilderverzeichnisses `public/assets/img/produkte/`
- Zugriff auf Server-/Fehlerlogs zur Fehlersuche im Betrieb

## Domain

- Registrierte Domain mit DNS-Eintrag auf den Webserver
- Optional: E-Mail-Versand (SMTP) für zukünftige automatische Bestellbestätigungen per Mail (aktuell wird die Bestätigung nur im Browser angezeigt)
