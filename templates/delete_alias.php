<?php
require __DIR__ . '/layout.php';

use App\Alias;
use App\Domains;

// ID aus POST oder GET ermitteln
$id = isset($_POST['id']) ? (int)$_POST['id'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);

if (!$id) {
    echo "<div class='alert alert-danger'>Keine Alias-ID angegeben.</div>";
    require __DIR__ . '/footer.php';
    exit;
}

$alias = Alias::getById($id);

if (!$alias) {
    echo "<div class='alert alert-danger'>Alias nicht gefunden.</div>";
    require __DIR__ . '/footer.php';
    exit;
}

// Prüfen, ob source_domain erlaubt ist
if (!in_array($alias['source_domain'], Domains::allowed(), true)) {
    echo "<div class='alert alert-danger'>Du hast keine Berechtigung, diesen Alias zu löschen.</div>";
    require __DIR__ . '/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Löschen
    $success = Alias::delete($id);
    if ($success) {
        echo "<div class='alert alert-success'>Alias erfolgreich gelöscht.</div>";
        echo "<p><a href='?page=aliases' class='btn btn-primary'>Zurück zur Alias-Übersicht</a></p>";
    } else {
        echo "<div class='alert alert-danger'>Fehler beim Löschen des Alias.</div>";
    }
    require __DIR__ . '/footer.php';
    exit;
}

?>

<h2>Alias löschen</h2>
<p>Möchtest du den Alias <strong><?=htmlspecialchars($alias['source_username'].'@'.$alias['source_domain'])?></strong> wirklich löschen?</p>

<form method="post" action="?page=delete-alias">
    <input type="hidden" name="id" value="<?= (int)$id ?>">
    <button type="submit" class="btn btn-danger">Ja, löschen</button>
    <a href="?page=aliases" class="btn btn-secondary">Abbrechen</a>
</form>

<?php require __DIR__ . '/footer.php'; ?>
