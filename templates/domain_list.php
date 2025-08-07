<?php
require __DIR__ . '/layout.php';

use App\Domains;

$allowed = $_ENV['ALLOWED_DOMAINS'] ?? '';
if (trim($allowed) !== '*') {
    http_response_code(403);
    echo "<p>Der Zugriff auf diese Seite ist nicht erlaubt.</p>";
    require __DIR__ . '/footer.php';
    exit;
}

$domains = Domains::getAllFull();
?>

<h2>Alle Domains</h2>

<p>
    <a href="?page=domain-form" class="btn btn-primary">Neue Domain hinzufügen</a>
</p>

<div class="list-group">
    <?php foreach ($domains as $domain): ?>
        <a href="?page=domain-form&id=<?= (int)$domain['id'] ?>"
           class="list-group-item list-group-item-action">
            <?= htmlspecialchars($domain['domain']) ?>
        </a>
    <?php endforeach; ?>
</div>


<?php require __DIR__ . '/footer.php'; ?>
