<?php
require __DIR__ . '/layout.php';

use App\DB;
use App\Account;
use App\Domains;
use App\Utils;

if (!isset($_GET['id'])) {
    header('Location: ?page=accounts');
    exit;
}

$id = (int)$_GET['id'];
$account = Account::find($id);
if (!$account) {
    echo "<p>Account nicht gefunden.</p>";
    require __DIR__ . '/footer.php';
    exit;
}

$maxQuota = $_ENV['MAX_QUOTA_MB'];
$domains = Domains::allowed();
?>

<h2>Account bearbeiten: <?=htmlspecialchars($account['username'].'@'.$account['domain'])?></h2>

<form method="post" action="?page=update-account">
    <input type="hidden" name="id" value="<?= $id ?>">

    <div class="mb-3">
        <label for="display_name" class="form-label">Anzeigename</label>
        <input type="text" id="display_name" name="display_name" class="form-control" value="<?= htmlspecialchars($account['display_name']) ?>">
    </div>

    <div class="mb-3">
        <label for="quota" class="form-label">Quota (MB, max <?= $maxQuota ?>)</label>
        <input type="number" id="quota" name="quota" class="form-control" value="<?= htmlspecialchars($account['quota']) ?>" min="0" max="<?= $maxQuota ?>">
    </div>

    <div class="form-check mb-3">
        <input type="checkbox" id="reset_password" name="reset_password" class="form-check-input">
        <label for="reset_password" class="form-check-label">Passwort zurücksetzen</label>
    </div>

    <div class="form-check mb-3">
        <input type="checkbox" id="enabled" name="enabled" class="form-check-input" <?= $account['enabled'] ? 'checked' : '' ?>>
        <label for="enabled" class="form-check-label">Aktiv</label>
    </div>

    <div class="form-check mb-3">
        <input type="checkbox" id="sendonly" name="sendonly" class="form-check-input" <?= $account['sendonly'] ? 'checked' : '' ?>>
        <label for="sendonly" class="form-check-label">Nur senden</label>
    </div>
	
	<div class="d-flex justify-content-between">
        <div>
			<button type="submit" class="btn btn-primary">Speichern</button>
			<a href="?page=accounts" class="btn btn-secondary">Abbrechen</a>
		</div>
		<div>
			<?php if($_ENV['DELETE_ACCOUNT'] === 'true'): ?>
			<a href="?page=delete-account&id=<?= (int) $_GET['id'] ?>" class="btn btn-danger">
				Account löschen
			</a>
			<?php endif; ?>
		</div>
</form>

<?php require __DIR__ . '/footer.php'; ?>
