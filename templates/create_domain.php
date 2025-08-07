<?php
// create_domain.php

require __DIR__ . '/layout.php';

use App\Domains;

if ($_ENV['ALLOWED_DOMAINS'] !== '*') {
    http_response_code(403);
    echo '<div class="alert alert-danger m-3">Zugriff nicht erlaubt.</div>';
    require __DIR__ . '/footer.php';
    exit;
}

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $domain = trim($_POST['domain'] ?? '');

    if ($domain === '') {
        $error = 'Bitte gib eine Domain ein.';
    } else {
        try {
            Domains::create($domain);
            $success = 'Die Domain wurde erfolgreich hinzugefügt.';
        } catch (Exception $e) {
            $error = 'Fehler beim Erstellen: ' . $e->getMessage();
        }
    }
}

// Erfolg oder Fehler anzeigen
if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
        <?= htmlspecialchars($error) ?>
    </div>
<?php elseif ($success): ?>
    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<div class="m-3">
    <a href="?page=domain-list" class="btn btn-primary">Zurück zur Domain-Übersicht</a>
</div>

<?php require __DIR__ . '/footer.php'; ?>
