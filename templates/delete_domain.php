<?php
// delete_domain.php

require __DIR__ . '/layout.php';

use App\Domains;

if ($_ENV['ALLOWED_DOMAINS'] !== '*' || $_ENV['DELETE_DOMAIN'] !== 'true') {
    http_response_code(403);
    echo '<div class="alert alert-danger m-3">Zugriff nicht erlaubt.</div>';
    require __DIR__ . '/footer.php';
    exit;
}

// ID aus GET oder POST
$id = isset($_GET['id']) ? (int)$_GET['id'] : ($_POST['id'] ?? null);

// Wenn kein ID übergeben wurde
if (!$id) {
    echo '<div class="alert alert-danger m-3">Ungültige oder fehlende Domain-ID.</div>';
    echo '<div class="m-3"><a href="?page=domain-list" class="btn btn-primary">Zurück zur Domain-Übersicht</a></div>';
    require __DIR__ . '/footer.php';
    exit;
}

try {
    $domain = Domains::getById($id);

    if (!$domain) {
        echo '<div class="alert alert-danger m-3">Domain nicht gefunden.</div>';
        echo '<div class="m-3"><a href="?page=domain-list" class="btn btn-primary">Zurück zur Domain-Übersicht</a></div>';
        require __DIR__ . '/../templates/footer.php';
        exit;
    }

    // Wenn POST: Domain löschen
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        Domains::delete($id);
        echo '<div class="alert alert-success m-3">Die Domain wurde erfolgreich gelöscht.</div>';
        echo '<div class="m-3"><a href="?page=domain-list" class="btn btn-primary">Zurück zur Domain-Übersicht</a></div>';
        require __DIR__ . '/footer.php';
        exit;
    }

    // Wenn GET: Bestätigung anzeigen
    ?>
    <div class="alert alert-warning m-3">
        Willst du die Domain <strong><?= htmlspecialchars($domain['domain']) ?></strong> wirklich löschen? Es werden auch alle dazugehörigen E-Mail-Accounts und Aliase gelöscht. Die Mails bleiben im Dateisystem erhalten und müssen seoarat gelöscht werden.
        <form method="post" class="mt-3">
            <input type="hidden" name="id" value="<?= (int)$id ?>">
            <button type="submit" class="btn btn-danger">Ja, löschen</button>
            <a href="?page=domain-list" class="btn btn-secondary">Abbrechen</a>
        </form>
    </div>
    <?php

} catch (Exception $e) {
    echo '<div class="alert alert-danger m-3">Fehler beim Löschen: ' . htmlspecialchars($e->getMessage()) . '</div>';
    echo '<div class="m-3"><a href="?page=domain-list" class="btn btn-primary">Zurück zur Domain-Übersicht</a></div>';
}

require __DIR__ . '/footer.php';
