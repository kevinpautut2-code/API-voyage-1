<?php

if ($methode === 'GET') {
    if ($id !== null) {
        $stmt = $pdo->prepare(
            'SELECT c.*, u.username 
             FROM comments c 
             JOIN users u ON c.user_id = u.id 
             WHERE c.id = ?'
        );
        $stmt->execute([$id]);
        $ligne = $stmt->fetch();
        if (!$ligne) {
            http_response_code(404);
            echo json_encode(['erreur' => 'Commentaire introuvable']);
            exit;
        }
        echo json_encode($ligne);
        exit;
    }

    $parUser = $_GET['user_id'] ?? null;
    if ($parUser !== null) {
        $stmt = $pdo->prepare(
            'SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id 
             WHERE c.user_id = ? ORDER BY c.created_at DESC'
        );
        $stmt->execute([$parUser]);
        echo json_encode($stmt->fetchAll());
        exit;
    }

    $dest = $_GET['destination_id'] ?? null;
    if ($dest !== null) {
        $stmt = $pdo->prepare(
            'SELECT c.*, u.username 
             FROM comments c 
             JOIN users u ON c.user_id = u.id 
             WHERE c.destination_id = ? 
             ORDER BY c.created_at DESC'
        );
        $stmt->execute([$dest]);
        echo json_encode($stmt->fetchAll());
        exit;
    }

    $stmt = $pdo->query(
        'SELECT c.*, u.username 
         FROM comments c 
         JOIN users u ON c.user_id = u.id 
         ORDER BY c.created_at DESC'
    );
    echo json_encode($stmt->fetchAll());
    exit;
}

if ($methode === 'POST') {
    $data = lire_json();
    if (empty($data['user_id']) || empty($data['destination_id']) || empty($data['content'])) {
        http_response_code(400);
        echo json_encode(['erreur' => 'user_id, destination_id et content obligatoires']);
        exit;
    }

    $note = $data['rating'] ?? null;
    if ($note !== null && ($note < 1 || $note > 5)) {
        http_response_code(400);
        echo json_encode(['erreur' => 'La note doit etre entre 1 et 5']);
        exit;
    }

    $stmt = $pdo->prepare(
        'INSERT INTO comments (user_id, destination_id, content, rating, created_at) 
         VALUES (?, ?, ?, ?, NOW())'
    );
    $stmt->execute([
        $data['user_id'],
        $data['destination_id'],
        $data['content'],
        $note,
    ]);
    http_response_code(201);
    echo json_encode(['id' => (int) $pdo->lastInsertId()]);
    exit;
}

if ($methode === 'DELETE' && $id !== null) {
    $stmt = $pdo->prepare('DELETE FROM comments WHERE id = ?');
    $stmt->execute([$id]);
    echo json_encode(['message' => 'ok']);
    exit;
}

http_response_code(405);
echo json_encode(['erreur' => 'Methode pas autorisee']);