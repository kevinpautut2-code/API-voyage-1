<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/db.php';

function lire_json()
{
    $contenu = file_get_contents('php://input');
    if ($contenu === false || $contenu === '') {
        return [];
    }
    $data = json_decode($contenu, true);
    return is_array($data) ? $data : [];
}

$url = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$morceaux = $url === '' ? [] : explode('/', $url);

$routes = ['regions', 'destinations', 'users', 'comments', 'contact', 'favorites'];
$ressource = '';
$id = null;

foreach ($morceaux as $i => $m) {
    if (in_array($m, $routes)) {
        $ressource = $m;
        if (isset($morceaux[$i + 1]) && is_numeric($morceaux[$i + 1])) {
            $id = $morceaux[$i + 1];
        }
        break;
    }
}

if ($ressource === '' && !empty($_GET['r']) && in_array($_GET['r'], $routes)) {
    $ressource = $_GET['r'];
    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
    }
}

$methode = $_SERVER['REQUEST_METHOD'];

if ($ressource === '' || $ressource === 'index.php') {
    echo json_encode(['api' => 'voyage']);
    exit;
}

$fichier = __DIR__ . '/../routes/' . $ressource . '.php';
if (!file_exists($fichier)) {
    http_response_code(404);
    echo json_encode(['erreur' => 'route pas trouvee']);
    exit;
}

require $fichier;