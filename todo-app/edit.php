<?php
session_start();
require_once __DIR__ . '/config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$id) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $priority = $_POST['priority'] ?? 'Medium';
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;

    if (!empty($title)) {
        $stmt = $pdo->prepare("
            UPDATE todos 
            SET title = :title, description = :description, priority = :priority, 
                due_date = :due_date, category_id = :category_id 
            WHERE id = :id AND user_id = :user_id
        ");
        
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':priority' => $priority,
            ':due_date' => $due_date,
            ':category_id' => $category_id,
            ':id' => $id,
            ':user_id' => $user_id
        ]);
    }

    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM todos WHERE id = :id AND user_id = :user_id");
$stmt->execute([':id' => $id, ':user_id' => $user_id]);
$todo = $stmt->fetch();

if (!$todo) {
    header('Location: index.php');
    exit;
}

// Get categories
$categories_stmt = $pdo->prepare("SELECT * FROM categories WHERE user_id = :user_id ORDER BY name");
$categories_stmt->execute([':user_id' => $user_id]);
$categories = $categories_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="dark-theme">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Task</h4>
                    </div>
                    <div class="card-body">
                        <form action="edit.php?id=<?= $todo['id'] ?>" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Task Title</label>
                                <input type="text" name="title" class="form-control" 
                                       value="<?= htmlspecialchars($todo['title']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($todo['description'] ?? '') ?></textarea>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Priority</label>
                                    <select name="priority" class="form-select">
                                        <option value="Low" <?= $todo['priority'] === 'Low' ? 'selected' : '' ?>>Low</option>
                                        <option value="Medium" <?= $todo['priority'] === 'Medium' ? 'selected' : '' ?>>Medium</option>
                                        <option value="High" <?= $todo['priority'] === 'High' ? 'selected' : '' ?>>High</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Due Date</label>
                                    <input type="date" name="due_date" class="form-control" 
                                           value="<?= $todo['due_date'] ? htmlspecialchars($todo['due_date']) : '' ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="form-select">
                                        <option value="">No Category</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id'] ?>" <?= $todo['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cat['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg"></i> Update Task
                                </button>
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="bi bi-x-lg"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>