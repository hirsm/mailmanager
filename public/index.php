<?php

// ini_set('display_errors', 'on');
require_once __DIR__ . '/../vendor/autoload.php';

use App\DB;
use App\Domains;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'ALLOWED_DOMAINS'])->notEmpty();
$dotenv->ifPresent('DB_PORT')->isInteger();
// Maximal 50 GB
$dotenv->required('MAX_QUOTA_MB')->allowedRegexValues('(^(?:[1-9][0-9]{0,3}|[1-4][0-9]{4}|50[0-9]{3}|51[0-1][0-9]{2}|51200?)$)');
// Mindestens 15 Zeichen für automatisch generierte Passwörter
$dotenv->required('PASSWORD_LENGTH')->allowedRegexValues('(^(?:1[6-9]|[2-9][0-9]|[1-9][0-9]{2,})$)');

$page = $_GET['page'] ?? 'home';

// einfaches Routing
switch ($page) {
	 case 'home':
        require __DIR__ . '/../templates/home.php';
        break;
    case 'accounts':
        require __DIR__ . '/../templates/accounts_list.php';
        break;
    case 'account-new':
        require __DIR__ . '/../templates/account_form.php';
        break;
	case 'account-edit':
		require __DIR__ . '/../templates/account_edit.php';
		break;
	case 'create-account':
		require __DIR__ . '/../templates/create_account.php';
		break;
	case 'update-account':
		require __DIR__ . '/../templates/update_account.php';
		break;
    case 'aliases':
        require __DIR__ . '/../templates/aliases_list.php';
        break;
    case 'alias-form':
        require __DIR__ . '/../templates/alias_form.php';
        break;
	case 'create-alias':
        require __DIR__ . '/../templates/create_alias.php';
        break;
	case 'update-alias':
        require __DIR__ . '/../templates/update_alias.php';
        break;
	case 'delete-alias':
        require __DIR__ . '/../templates/delete_alias.php';
        break;
	case 'domain-list':
        require __DIR__ . '/../templates/domain_list.php';
        break;
	case 'domain-form':
        require __DIR__ . '/../templates/domain_form.php';
        break;
	case 'create-domain':
        require __DIR__ . '/../templates/create_domain.php';
        break;
	case 'delete-domain':
        require __DIR__ . '/../templates/delete_domain.php';
        break;
	case 'delete-account':
        require __DIR__ . '/../templates/delete_account.php';
        break;
    default:
        echo "Unbekannte Seite.";
}

