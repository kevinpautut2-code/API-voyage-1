<?php

if ($methode === 'GET') {
    $user = $_GET['user_id'] ?? null;

    if ($id !== null) {
        $stmt = $pdo->prepare('SELECT * FROM favorites WHERE id = ?');
        $stmt->execute([$id]);
        $ligne = $stmt->fetch();
        if (!$ligne) {
            http_response_code(404);
            echo json_encode(['erreur' => 'Favori introuvable']);
            exit;
        }
        echo json_encode($ligne);
        exit;
    }

    if ($user !== null) {
        $stmt = $pdo->prepare('SELECT * FROM favorites WHERE user_id = ? ORDER BY created_at DESC');
        $stmt->execute([$user]);
        echo json_encode($stmt->fetchAll());
        exit;
    }

    $stmt = $pdo->query('SELECT * FROM favorites ORDER BY created_at DESC');
    echo json_encode($stmt->fetchAll());
    exit;
}

if ($methode === 'POST') {
    $data = lire_json();
    if (empty($data['user_id']) || empty($data['destination_id'])) {
        http_response_code(400);
        echo json_encode(['erreur' => 'user_id et destination_id requis']);
        exit;
    }

    $stmt = $pdo->prepare(
        'INSERT INTO favorites (user_id, destination_id, destination_title, destination_image, created_at) 
         VALUES (?, ?, ?, ?, NOW())'
    );
    $stmt->execute([
        $data['user_id'],
        (string) $data['destination_id'],
        $data['destination_title'] ?? null,
        $data['destination_image'] ?? null,
    ]);
    http_response_code(201);
    echo json_encode(['id' => (int) $pdo->lastInsertId()]);
    exit;
}

if ($methode === 'DELETE' && $id !== null) {
    $stmt = $pdo->prepare('DELETE FROM favorites WHERE id = ?');
    $stmt->execute([$id]);
    echo json_encode(['message' => 'Favori retire']);
    exit;
}

http_response_code(405);
echo json_encode(['erreur' => 'Methode pas autorisee']);