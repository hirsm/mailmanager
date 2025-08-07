<?php
require __DIR__ . '/layout.php';

use App\Account;

$accounts = Account::findAll();
?>

<h2>Accounts</h2>
<p><a href="?page=account-new" class="btn btn-primary">Neuen Account anlegen</a></p>

<div class="list-group">
    <?php foreach ($accounts as $account): ?>
        <a href="?page=account-edit&id=<?= (int)$account['id'] ?>" class="list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1"><?= htmlspecialchars($account['username'] . '@' . $account['domain']) ?></h5>
            </div>
            <p class="mb-1"><?= htmlspecialchars($account['display_name']) ?></p>
            <div class="d-flex justify-content-between">
                <small>Quota: <?= (int)$account['quota'] ?> MB</small>
                <small class="text-end">
                    <?= $account['enabled'] ? '✔ Aktiv' : '✖ Inaktiv' ?> ·
                    <?= $account['sendonly'] ? 'Nur senden' : 'Empfangen erlaubt' ?>
                </small>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<?php require __DIR__ . '/footer.php'; ?>
