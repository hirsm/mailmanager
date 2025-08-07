<?php
require __DIR__ . '/layout.php';

use App\Alias;

$aliases = Alias::getAll();
?>

<h2>Aliase</h2>
<p><a href="?page=alias-form" class="btn btn-primary">Neuen Alias anlegen</a></p>

<div class="list-group">
    <?php foreach ($aliases as $alias): ?>
        <a href="?page=alias-form&id=<?= (int)$alias['id'] ?>" class="list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">
                    <?= htmlspecialchars($alias['source_username'] . '@' . $alias['source_domain']) ?>
                </h5>
            </div>
            <div class="d-flex w-100 justify-content-between small text-muted">
                <span>
                    <?= htmlspecialchars($alias['destination_username'] . '@' . $alias['destination_domain']) ?>
                </span>
                <span class="text-end">
                    <?= $alias['enabled'] ? '✔ aktiv' : '✖ inaktiv' ?>
                </span>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<?php require __DIR__ . '/footer.php'; ?>