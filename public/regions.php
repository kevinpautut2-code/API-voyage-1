<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

$req = $pdo->query('SELECT * FROM regions ORDER BY name');
$data = $req->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>regions</title>
</head>
<body>
    <h1>Regions</h1>
    <p><a href="info.php">retour</a></p>
    <pre><?php echo htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT)); ?></pre>
</body>
</html>