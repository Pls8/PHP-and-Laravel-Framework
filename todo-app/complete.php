<?php
session_start();
require_once __DIR__ . '/config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $user_id = $_SESSION['user_id'];

    if ($id) {
        // Toggle completion status
        $stmt = $pdo->prepare("
            UPDATE todos 
            SET is_completed = NOT is_completed 
            WHERE id = :id AND user_id = :user_id
        ");
        $stmt->execute([':id' => $id, ':user_id' => $user_id]);
    }
}

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
exit;
?>