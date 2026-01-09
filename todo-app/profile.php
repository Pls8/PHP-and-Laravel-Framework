<?php
session_start();
require_once __DIR__ . '/config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle category creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name'])) {
    $name = trim($_POST['category_name'] ?? '');
    $color = $_POST['category_color'] ?? '#007bff';

    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO categories (name, color, user_id) VALUES (:name, :color, :user_id)");
        $stmt->execute([':name' => $name, ':color' => $color, ':user_id' => $user_id]);
    }
}

// Get user's categories
$stmt = $pdo->prepare("SELECT * FROM categories WHERE user_id = :user_id ORDER BY name");
$stmt->execute([':user_id' => $user_id]);
$categories = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="dark-theme">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Todo Master</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-item nav-link" href="index.php">Back to Tasks</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-tags"></i> Create New Category</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Category Name</label>
                                <input type="text" name="category_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Color</label>
                                <input type="color" name="category_color" class="form-control form-control-color" value="#007bff">
                            </div>
                            <button type="submit" class="btn btn-primary">Create Category</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-list"></i> My Categories</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($categories)): ?>
                            <p class="text-muted">No categories yet. Create one!</p>
                        <?php else: ?>
                            <div class="list-group">
                                <?php foreach ($categories as $cat): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge me-2" style="background-color: <?= $cat['color'] ?>">&nbsp;&nbsp;&nbsp;</span>
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </div>
                                        <span class="text-muted"><?= $cat['color'] ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>