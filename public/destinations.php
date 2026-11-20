<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

$sql = 'SELECT d.*, r.name AS region_name FROM destinations d
        LEFT JOIN regions r ON d.region_id = r.id ORDER BY d.name';
$req = $pdo->query($sql);
$data = $req->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>destinations</title>
</head>
<body>
    <h1>Destinations</h1>
    <p><a href="info.php">retour</a></p>
    <pre><?php echo htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT)); ?></pre>
</body>
</html>