<?php
require_once __DIR__ . '/config/database.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');

    if (!empty($title)) {
        $stmt = $pdo->prepare("UPDATE todos SET title = :title WHERE id = :id");
        $stmt->execute([':title' => $title, ':id' => $id]);
    }

    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM todos WHERE id = :id");
$stmt->execute([':id' => $id]);
$todo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$todo) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Todo</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit Task</h1>
        <form action="edit.php?id=<?= $todo['id'] ?>" method="POST" class="edit-form">
            <input type="text" name="title" value="<?= htmlspecialchars($todo['title']) ?>" required>
            <button type="submit">Update Task</button>
        </form>
        <a href="index.php" class="back-link">← Back to Todo List</a>
    </div>
</body>
</html>
