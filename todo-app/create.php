<?php
session_start();
require_once __DIR__ . '/config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $priority = $_POST['priority'] ?? 'Medium';
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $user_id = $_SESSION['user_id'];

    if (!empty($title)) {
        $stmt = $pdo->prepare("
            INSERT INTO todos (title, description, priority, due_date, category_id, user_id) 
            VALUES (:title, :description, :priority, :due_date, :category_id, :user_id)
        ");
        
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':priority' => $priority,
            ':due_date' => $due_date,
            ':category_id' => $category_id,
            ':user_id' => $user_id
        ]);
    }

    header('Location: index.php');
    exit;
}
?>