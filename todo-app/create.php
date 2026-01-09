<?php
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');

    if (!empty($title)) {
        $stmt = $pdo->prepare("INSERT INTO todos (title) VALUES (:title)");
        $stmt->execute([':title' => $title]);
    }

    header('Location: index.php');
    exit;
}