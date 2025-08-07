<?php
require __DIR__ . '/layout.php';

use App\Alias;
use App\Domains;

// ID validieren
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if (!$id || !Alias::getById($id)) {
    echo "<div class='alert alert-danger'>Ungültige Alias-ID.</div>";
    require __DIR__ . '/footer.php';
    exit;
}

// Formulardaten auslesen
$source_username = trim($_POST['source_username'] ?? '');
$source_domain = trim($_POST['source_domain'] ?? '');
$destination_username = trim($_POST['destination_username'] ?? '');
$destination_domain = trim($_POST['destination_domain'] ?? '');
$enabled = isset($_POST['enabled']) ? (int)trim($_POST['enabled']) : 0;


// Eingaben prüfen
$errors = [];

if ($source_username === '') {
    $errors[] = 'Alias-Name darf nicht leer sein.';
}

if (!in_array($source_domain, Domains::allowed(), true)) {
    $errors[] = 'Die gewählte Alias-Domain ist nicht erlaubt.';
}

if ($destination_username === '') {
    $errors[] = 'Zieladresse (lokaler Teil) darf nicht leer sein.';
}

if ($destination_domain === '') {
    $errors[] = 'Zieladresse (Domain) darf nicht leer sein.';
}

if (!is_int($enabled)) {
    $errors[] = 'Aktiv muss eine Zahl sein.';
}

if ($errors) {
    echo "<div class='alert alert-danger'><ul>";
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul></div>";
    require __DIR__ . '/footer.php';
    exit;
}

// Alias aktualisieren
$success = Alias::update($id, [
    'source_username' => $source_username,
    'source_domain' => $source_domain,
    'destination_username' => $destination_username,
    'destination_domain' => $destination_domain,
	'enabled' => $enabled,
]);

if ($success) {
    echo "<div class='alert alert-success'>Alias erfolgreich aktualisiert.</div>";
    echo "<p><a href='?page=aliases' class='btn btn-primary'>Zurück zur Alias-Übersicht</a></p>";
} else {
    echo "<div class='alert alert-danger'>Fehler beim Speichern des Alias.</div>";
}

require __DIR__ . '/footer.php';
