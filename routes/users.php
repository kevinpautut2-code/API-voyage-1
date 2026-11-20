<?php

if ($methode === 'GET') {
    if ($id !== null) {
        $stmt = $pdo->prepare(
            'SELECT id, username, email, first_name, last_name, gender, image, avatar, role, created_at 
             FROM users WHERE id = ?'
        );
        $stmt->execute([$id]);
        $ligne = $stmt->fetch();
        if (!$ligne) {
            http_response_code(404);
            echo json_encode(['erreur' => 'Utilisateur introuvable']);
            exit;
        }
        echo json_encode($ligne);
        exit;
    }

    $stmt = $pdo->query(
        'SELECT id, username, email, first_name, last_name, gender, image, avatar, role, created_at 
         FROM users ORDER BY id'
    );
    echo json_encode($stmt->fetchAll());
    exit;
}

if ($methode === 'POST') {
    $data = lire_json();
    if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
        http_response_code(400);
        echo json_encode(['erreur' => 'username, email et password requis']);
        exit;
    }

    $mdp = password_hash($data['password'], PASSWORD_DEFAULT);
    $role = $data['role'] ?? 'membre';
    if ($role !== 'admin' && $role !== 'membre') {
        $role = 'membre';
    }

    $stmt = $pdo->prepare(
        'INSERT INTO users 
        (username, email, password, first_name, last_name, gender, image, avatar, role, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())'
    );
    try {
        $stmt->execute([
            $data['username'],
            $data['email'],
            $mdp,
            $data['first_name'] ?? null,
            $data['last_name'] ?? null,
            $data['gender'] ?? null,
            $data['image'] ?? null,
            $data['avatar'] ?? null,
            $role,
        ]);
    } catch (PDOException $e) {
        http_response_code(409);
        echo json_encode(['erreur' => 'Email ou pseudo deja pris']);
        exit;
    }

    http_response_code(201);
    echo json_encode(['id' => (int) $pdo->lastInsertId()]);
    exit;
}

if ($methode === 'PUT' && $id !== null) {
    $data = lire_json();

    if (!empty($data['password'])) {
        $mdp = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
        $stmt->execute([$mdp, $id]);
        echo json_encode(['message' => 'mdp ok']);
        exit;
    }

    $stmt = $pdo->prepare(
        'UPDATE users SET username=?, email=?, first_name=?, last_name=?, gender=?, avatar=?, image=? WHERE id=?'
    );
    $stmt->execute([
        $data['username'],
        $data['email'],
        $data['first_name'] ?? null,
        $data['last_name'] ?? null,
        $data['gender'] ?? null,
        $data['avatar'] ?? null,
        $data['image'] ?? null,
        $id,
    ]);

    $stmt = $pdo->prepare(
        'SELECT id, username, email, first_name, last_name, gender, image, avatar, role, created_at FROM users WHERE id=?'
    );
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch());
    exit;
}

http_response_code(405);
echo json_encode(['erreur' => 'Methode pas autorisee']);