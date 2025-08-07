<?php
// delete_account.php

require __DIR__ . '/layout.php';

use App\Account;

if ($_ENV['DELETE_ACCOUNT'] !== 'true') {
    http_response_code(403);
    echo '<div class="alert alert-danger m-3">Account-Löschung ist nicht erlaubt.</div>';
    require __DIR__ . '/footer.php';
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : ($_POST['id'] ?? null);

if (!$id) {
    echo '<div class="alert alert-danger m-3">Ungültige oder fehlende Account-ID.</div>';
    echo '<div class="m-3"><a href="?page=accounts" class="btn btn-primary">Zurück zur Account-Übersicht</a></div>';
    require __DIR__ . '/../templates/footer.php';
    exit;
}

try {
    $account = Account::find($id);

    if (!$account) {
        echo '<div class="alert alert-danger m-3">Account nicht gefunden.</div>';
        echo '<div class="m-3"><a href="?page=accounts" class="btn btn-primary">Zurück zur Account-Übersicht</a></div>';
        require __DIR__ . '/footer.php';
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        Account::delete($id);
        echo '<div class="alert alert-success m-3">Der Account wurde erfolgreich gelöscht.</div>';
        echo '<div class="m-3"><a href="?page=accounts" class="btn btn-primary">Zurück zur Account-Übersicht</a></div>';
        require __DIR__ . '/../templates/footer.php';
        exit;
    }

    // GET → Bestätigung
    ?>
    <div class="alert alert-warning m-3">
        Willst du den Account <strong><?= htmlspecialchars($account['username']) ?></strong> wirklich löschen?
        <form method="post" class="mt-3">
            <input type="hidden" name="id" value="<?= (int)$id ?>">
            <button type="submit" class="btn btn-danger">Ja, löschen</button>
            <a href="?page=accounts" class="btn btn-secondary">Abbrechen</a>
        </form>
    </div>
    <?php

} catch (Exception $e) {
    echo '<div class="alert alert-danger m-3">Fehler beim Löschen: ' . htmlspecialchars($e->getMessage()) . '</div>';
    echo '<div class="m-3"><a href="?page=accounts" class="btn btn-primary">Zurück zur Account-Übersicht</a></div>';
}

require __DIR__ . '/footer.php';
