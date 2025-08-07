<?php
require __DIR__ . '/layout.php';

use App\Domains;

if ($_ENV['ALLOWED_DOMAINS'] !== '*') {
    http_response_code(403);
    echo "<div class='alert alert-danger m-3'>Zugriff verweigert.</div>";
    require __DIR__ . '/templates/footer.php';
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$domain = $id ? Domains::getById($id) : null;

$domainName = $domain['domain'] ?? '';
$action = $id ? '?page=domain-form' : '?page=create-domain';
?>

<h2><?= $id ? 'Domain bearbeiten' : 'Neue Domain hinzufügen' ?></h2>

<form method="post" action="<?= $action ?>">
    <?php if ($id): ?>
        <input type="hidden" name="id" value="<?= $id ?>">
    <?php endif; ?>

    <div class="mb-3">
        <label for="domain" class="form-label">Domain</label>
        <input type="text" class="form-control" id="domain" name="domain" required
               value="<?= htmlspecialchars($domainName) ?>" <?= ($id) ? 'disabled' : '' ?>>
    </div>

    <div class="d-flex justify-content-between">
        <div>
			<?php if (!$id): ?>
            <button type="submit" class="btn btn-success">Speichern</button>
			<?php endif; ?>
            <a href="?page=domain-list" class="btn btn-secondary">Zurück</a>
        </div>

        <?php if ($id && $_ENV['DELETE_DOMAIN'] === 'true'): ?>
            <a href="?page=delete-domain&id=<?= $id ?>" class="btn btn-danger">
                Löschen
            </a>
        <?php endif; ?>
    </div>
</form>

<?php require __DIR__ . '/footer.php'; ?>
