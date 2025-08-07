<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>Mailmanager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="?">Mailmanager</a>
        <div class="navbar-nav">
            <a class="nav-link" href="?page=accounts">Accounts</a>
            <a class="nav-link" href="?page=aliases">Aliase</a>
			<?php if (trim($_ENV['ALLOWED_DOMAINS'] ?? '') === '*'): ?>
			<a class="nav-link" href="?page=domain-list">Alle Domains</a>
			<?php endif; ?>
        </div>
    </div>
</nav>

<main class="container">

