<?php

if ($methode === 'GET' && $id === null) {
    $stmt = $pdo->query('SELECT * FROM contact_requests ORDER BY created_at DESC');
    echo json_encode($stmt->fetchAll());
    exit;
}

if ($methode === 'POST') {
    $data = lire_json();
    $oblig = ['user_id', 'name', 'email', 'subject', 'message'];
    foreach ($oblig as $o) {
        if (empty($data[$o])) {
            http_response_code(400);
            echo json_encode(['erreur' => 'Tous les champs sont obligatoires']);
            exit;
        }
    }

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['erreur' => 'Email invalide']);
        exit;
    }

    $stmt = $pdo->prepare(
        'INSERT INTO contact_requests (user_id, name, email, subject, message, created_at) 
         VALUES (?, ?, ?, ?, ?, NOW())'
    );
    $stmt->execute([
        $data['user_id'],
        $data['name'],
        $data['email'],
        $data['subject'],
        $data['message'],
    ]);
    http_response_code(201);
    echo json_encode(['id' => (int) $pdo->lastInsertId(), 'message' => 'Demande enregistree']);
    exit;
}

http_response_code(405);
echo json_encode(['erreur' => 'Methode pas autorisee']);