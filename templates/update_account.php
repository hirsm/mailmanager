<?php

use App\Account;
use App\Utils;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ?page=accounts');
    exit;
}

$id = (int)($_POST['id'] ?? null);
$account = Account::find($id);
if (is_null($id) || !$account) {
    die("Account nicht gefunden.");
}

$display_name = trim($_POST['display_name'] ?? '');
$quota = (int)($_POST['quota'] ?? 0);
$enabled = isset($_POST['enabled']) ? 1 : 0;
$sendonly = isset($_POST['sendonly']) ? 1 : 0;
$reset_password = isset($_POST['reset_password']);

$maxQuota = $_ENV['MAX_QUOTA_MB'];
if ($quota > $maxQuota) {
    $quota = $maxQuota;
}

$updateData = [
    'display_name' => $display_name,
    'quota' => $quota,
    'enabled' => $enabled,
    'sendonly' => $sendonly,
];

$newPassword = null;
if ($reset_password) {
    $newPassword = Utils::generatePassword($_ENV['PASSWORD_LENGTH']);
    $hashedPassword = password_hash($newPassword, PASSWORD_ARGON2I);
    $updateData['password'] = $hashedPassword;
}

Account::update($id, $updateData);

require __DIR__ . '/layout.php';
?>

<h2>Account erfolgreich aktualisiert</h2>

<?php if ($reset_password): ?>
    <div class="alert alert-info">
        <strong>Passwort zurückgesetzt!</strong><br>
        Neues Passwort: <code><?= htmlspecialchars($newPassword) ?></code><br>
        Bitte notiere es dir, da es nicht nochmal angezeigt wird!
    </div>
<?php else: ?>
    <div class="alert alert-success">
        Die Änderungen wurden gespeichert.
    </div>
<?php endif; ?>

<p><a href="?page=accounts" class="btn btn-primary">Zurück zur Übersicht</a></p>

<?php require __DIR__ . '/footer.php'; ?>
