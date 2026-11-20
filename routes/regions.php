<?php

if ($methode === 'GET') {
    if ($id !== null) {
        $stmt = $pdo->prepare('SELECT * FROM regions WHERE id = ?');
        $stmt->execute([$id]);
        $ligne = $stmt->fetch();
        if (!$ligne) {
            http_response_code(404);
            echo json_encode(['erreur' => 'Region introuvable']);
            exit;
        }
        echo json_encode($ligne);
        exit;
    }

    $stmt = $pdo->query('SELECT * FROM regions ORDER BY name');
    echo json_encode($stmt->fetchAll());
    exit;
}

if ($methode === 'POST') {
    $data = lire_json();
    if (empty($data['slug']) || empty($data['name'])) {
        http_response_code(400);
        echo json_encode(['erreur' => 'slug et name obligatoires']);
        exit;
    }
    $stmt = $pdo->prepare('INSERT INTO regions (slug, name) VALUES (?, ?)');
    $stmt->execute([$data['slug'], $data['name']]);
    http_response_code(201);
    echo json_encode(['id' => (int) $pdo->lastInsertId(), 'message' => 'Region ajoutee']);
    exit;
}

http_response_code(405);
echo json_encode(['erreur' => 'Methode pas autorisee']);