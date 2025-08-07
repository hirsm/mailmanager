<?php
require __DIR__ . '/layout.php';

use App\Alias;
use App\Domains;

$source_domain = $_POST['source_domain'] ?? '';
$destination_domain = $_POST['destination_domain'] ?? '';
$enabled = isset($_POST['enabled']) ? (int)$_POST['enabled'] : 0;

if (!Domains::allowed($source_domain)) {
    echo '<div class="alert alert-danger">Quell-Domain nicht erlaubt.</div>';
    require __DIR__ . '/footer.php';
    exit;
}

Alias::create([
    'source_username' => $_POST['source_username'] ?? '',
    'source_domain' => $source_domain,
    'destination_username' => $_POST['destination_username'] ?? '',
    'destination_domain' => $destination_domain,
	'enabled' => $enabled,
]);
?>

<div class="alert alert-success">Alias erfolgreich erstellt.</div>
<p><a href="?page=aliases" class="btn btn-primary">Zurück zur Alias-Übersicht</a></p>

<?php require __DIR__ . '/footer.php'; ?>
