<?php

require __DIR__ . '/layout.php';

use App\Domains;

$domains = Domains::allowed();
$maxQuota = isset($_ENV['MAX_QUOTA_MB']) ? (int) $_ENV['MAX_QUOTA_MB'] : 0;
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Neuen Account erstellen</title>
    <link href="/assets/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h1>Neuen Account erstellen</h1>

    <form method="post" action="?page=create-account">
        <div class="mb-3">
            <label for="username" class="form-label">Benutzername</label>
            <div class="input-group">
                <input type="text" class="form-control" id="username" name="username" required>
                <span class="input-group-text">@</span>
                <select class="form-select" id="domain" name="domain" required>
                    <?php foreach ($domains as $domain): ?>
                        <option value="<?= htmlspecialchars($domain) ?>"><?= htmlspecialchars($domain) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label for="display_name" class="form-label">Anzeigename</label>
            <input type="text" class="form-control" id="display_name" name="display_name" required>
        </div>

        <div class="mb-3">
            <label for="quota" class="form-label">Speicherplatz (MB)</label>
            <input type="number" class="form-control" id="quota" name="quota" min="1" value="1024"
                <?= $maxQuota > 0 ? 'max="' . $maxQuota . '"' : '' ?>>
            <?php if ($maxQuota > 0): ?>
                <div class="form-text">Maximale erlaubte Quota: <?= $maxQuota ?> MB</div>
            <?php endif; ?>
        </div>
		
		<div class="d-flex justify-content-between">
			<div>
				<button type="submit" class="btn btn-primary">Account erstellen</button>
				<a href="?page=accounts" class="btn btn-secondary">Abbrechen</a>
			</div>
		</div>
    </form>

</body>
</html>

<?php require __DIR__ . '/footer.php'; ?>
