<?php
// alias_form.php
require __DIR__ . '/layout.php';

use App\Alias;
use App\Domains;

$allowedDomains = Domains::allowed();

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$alias = $id ? Alias::getById($id) : [];

// Fallbacks, falls leer
$source_username = $alias['source_username'] ?? '';
$source_domain = $alias['source_domain'] ?? '';
$destination_username = $alias['destination_username'] ?? '';
$destination_domain = $alias['destination_domain'] ?? '';
$enabled = isset($alias['enabled']) ? (bool)$alias['enabled'] : true;

$action = $id ? '?page=update-alias' : '?page=create-alias';
?>

<h2><?= $id ? 'Alias bearbeiten' : 'Neuen Alias anlegen' ?></h2>

<form method="post" action="<?= $action ?>">
    <?php if ($id): ?>
        <input type="hidden" name="id" value="<?= (int)$id ?>">
    <?php endif; ?>

    <div class="mb-3">
        <label for="source_username" class="form-label">Alias-Name (lokaler Teil)</label>
        <input type="text" class="form-control" id="source_username" name="source_username"
               value="<?= htmlspecialchars($source_username) ?>" required>
    </div>

    <div class="mb-3">
        <label for="source_domain" class="form-label">Alias-Domain</label>
        <select class="form-select" id="source_domain" name="source_domain" required>
            <?php foreach ($allowedDomains as $domain): ?>
                <option value="<?= htmlspecialchars($domain) ?>" <?= $domain === $source_domain ? 'selected' : '' ?>>
                    <?= htmlspecialchars($domain) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="destination_username" class="form-label">Zieladresse (lokaler Teil)</label>
        <input type="text" class="form-control" id="destination_username" name="destination_username"
               value="<?= htmlspecialchars($destination_username) ?>" required>
    </div>

    <div class="mb-3">
        <label for="destination_domain" class="form-label">Zieladresse (Domain)</label>
        <input type="text" class="form-control" id="destination_domain" name="destination_domain"
               value="<?= htmlspecialchars($destination_domain) ?>" required>
    </div>

    <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" id="enabled" name="enabled" value="1" <?= $enabled ? 'checked' : '' ?>>
        <label class="form-check-label" for="enabled">
            Aktiv
        </label>
    </div>

    <div class="d-flex justify-content-between">
        <div>
            <button type="submit" class="btn btn-success">Speichern</button>
            <a href="?page=aliases" class="btn btn-secondary">Abbrechen</a>
        </div>

        <?php if ($id): ?>
            <a href="?page=delete-alias&id=<?= (int)$id ?>" class="btn btn-danger">
                Löschen
            </a>
        <?php endif; ?>
    </div>
</form>

<?php require __DIR__ . '/footer.php'; ?>
