<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

$req = $pdo->query(
    'SELECT id, username, email, first_name, last_name, role, created_at FROM users ORDER BY id'
);
$data = $req->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>users</title>
</head>
<body>
    <h1>Users</h1>
    <p><a href="info.php">retour</a></p>
    <pre><?php echo htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT)); ?></pre>
</body>
</html>