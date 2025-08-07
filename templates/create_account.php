<?php

require __DIR__ . '/layout.php';

use App\DB;
use App\Utils;
use App\Domains;

require_once __DIR__ . '/../vendor/autoload.php';

$db = DB::getConnection();

// Konfiguration aus .env
$passwordLength = (int) ($_ENV['PASSWORD_LENGTH'] ?? 16);
$maxQuota = isset($_ENV['MAX_QUOTA_MB']) ? (int) $_ENV['MAX_QUOTA_MB'] : 0;

// Eingaben aus POST
$username = trim($_POST['username'] ?? '');
$domain = trim($_POST['domain'] ?? '');
$displayName = trim($_POST['display_name'] ?? '');
$quota = isset($_POST['quota']) ? (int) $_POST['quota'] : 1024;

// Validierung
if (!Domains::allowed($domain)) {
    die('Ungültige Domain.');
}

if (!preg_match('/^[a-zA-Z0-9._%+-]+$/', $username)) {
    die('Ungültiger Benutzername.');
}

if ($maxQuota > 0 && $quota > $maxQuota) {
    $quota = $maxQuota;
}

// Passwort generieren
$passwordPlain = Utils::generatePassword($passwordLength);
$passwordHash = password_hash($passwordPlain, PASSWORD_ARGON2I);

// Account in Datenbank einfügen
$stmt = $db->prepare('
    INSERT INTO accounts (username, domain, password, display_name, quota)
    VALUES (?, ?, ?, ?, ?)
');

$stmt->execute([
    $username,
    $domain,
    $passwordHash,
    $displayName,
    $quota
]);

// Passwort anzeigen (nur dieses eine Mal)
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Account erstellt</title>
    <link href="/assets/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Account erfolgreich erstellt</h1>

    <p>Der neue Account <code><?= htmlspecialchars($username . '@' . $domain) ?></code> wurde angelegt.</p>

    <p>Das generierte Passwort lautet:</p>

    <div class="alert alert-info">
        <strong><?= htmlspecialchars($passwordPlain) ?></strong>
    </div>

    <p><strong>Bitte notiere dieses Passwort jetzt.</strong> Es kann später nicht erneut angezeigt werden!</p>

    <a href="?page=accounts" class="btn btn-primary">Zurück zur Übersicht</a>
</body>
</html>
<?php require __DIR__ . '/footer.php'; ?>
