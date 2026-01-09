<?php
require_once __DIR__ . '/config/database.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("UPDATE todos SET is_completed = NOT is_completed WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

header('Location: index.php');
exit;