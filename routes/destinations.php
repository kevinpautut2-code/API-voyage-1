<?php

if ($methode === 'GET') {
    if ($id !== null) {
        $sql = 'SELECT d.*, r.name AS region_name 
                FROM destinations d 
                LEFT JOIN regions r ON d.region_id = r.id 
                WHERE d.id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $ligne = $stmt->fetch();
        if (!$ligne) {
            http_response_code(404);
            echo json_encode(['erreur' => 'Destination introuvable']);
            exit;
        }
        echo json_encode($ligne);
        exit;
    }

    $region = $_GET['region_id'] ?? null;
    if ($region !== null && $region !== '') {
        $stmt = $pdo->prepare(
            'SELECT d.*, r.name AS region_name FROM destinations d 
             LEFT JOIN regions r ON d.region_id = r.id WHERE d.region_id = ? ORDER BY d.name'
        );
        $stmt->execute([$region]);
        echo json_encode($stmt->fetchAll());
        exit;
    }

    $stmt = $pdo->query(
        'SELECT d.*, r.name AS region_name 
         FROM destinations d 
         LEFT JOIN regions r ON d.region_id = r.id 
         ORDER BY d.name'
    );
    echo json_encode($stmt->fetchAll());
    exit;
}

if ($methode === 'POST') {
    $data = lire_json();
    $champs = ['slug', 'name', 'region_id', 'created_by'];
    foreach ($champs as $c) {
        if (!isset($data[$c]) || $data[$c] === '') {
            http_response_code(400);
            echo json_encode(['erreur' => 'champs manquants']);
            exit;
        }
    }

    $stmt = $pdo->prepare(
        'INSERT INTO destinations 
        (slug, name, region_id, capital, description, image, lat, `long`, created_by) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        $data['slug'],
        $data['name'],
        $data['region_id'],
        $data['capital'] ?? null,
        $data['description'] ?? null,
        $data['image'] ?? null,
        $data['lat'] ?? null,
        $data['long'] ?? null,
        $data['created_by'],
    ]);
    http_response_code(201);
    echo json_encode(['id' => (int) $pdo->lastInsertId()]);
    exit;
}

if ($methode === 'DELETE' && $id !== null) {
    $stmt = $pdo->prepare('DELETE FROM destinations WHERE id = ?');
    $stmt->execute([$id]);
    echo json_encode(['message' => 'Supprime']);
    exit;
}

http_response_code(405);
echo json_encode(['erreur' => 'Methode pas autorisee']);