<?php
session_start();
require_once __DIR__ . '/config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle search/filter
$search = $_GET['search'] ?? '';
$priority = $_GET['priority'] ?? '';
$category_id = $_GET['category_id'] ?? '';
$show_completed = $_GET['show_completed'] ?? '1';

// Build query
$sql = "SELECT t.*, c.name as category_name, c.color as category_color 
        FROM todos t 
        LEFT JOIN categories c ON t.category_id = c.id 
        WHERE t.user_id = :user_id";

$params = [':user_id' => $user_id];

if (!empty($search)) {
    $sql .= " AND (t.title LIKE :search OR t.description LIKE :search)";
    $params[':search'] = "%$search%";
}

if (!empty($priority)) {
    $sql .= " AND t.priority = :priority";
    $params[':priority'] = $priority;
}

if (!empty($category_id)) {
    $sql .= " AND t.category_id = :category_id";
    $params[':category_id'] = $category_id;
}

if ($show_completed === '0') {
    $sql .= " AND t.is_completed = 0";
}

$sql .= " ORDER BY 
    CASE priority 
        WHEN 'High' THEN 1 
        WHEN 'Medium' THEN 2 
        WHEN 'Low' THEN 3 
    END, 
    due_date ASC, 
    created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$todos = $stmt->fetchAll();

// Get categories for filter dropdown
$categories_stmt = $pdo->prepare("SELECT * FROM categories WHERE user_id = :user_id ORDER BY name");
$categories_stmt->execute([':user_id' => $user_id]);
$categories = $categories_stmt->fetchAll();

// Get stats
$stats_stmt = $pdo->prepare("
SELECT 
        COUNT(*) as total,
        SUM(is_completed) as completed,
        SUM(priority = 'High' AND is_completed = 0) as `high_priority`
    FROM todos WHERE user_id = :user_id
");
$stats_stmt->execute([':user_id' => $user_id]);
$stats = $stats_stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body class="dark-theme">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-check2-circle me-2"></i>Todo Master
            </a>
            <div class="navbar-nav ms-auto">
                <span class="nav-item nav-link">Welcome, <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></span>
                <a class="nav-item nav-link" href="profile.php">Profile</a>
                <a class="nav-item nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Stats Card -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card stats-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-list-task"></i> Total Tasks</h5>
                        <p class="card-text display-6"><?= $stats['total'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stats-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-check2"></i> Completed</h5>
                        <p class="card-text display-6 text-success"><?= $stats['completed'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stats-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-exclamation-triangle"></i> High Priority</h5>
                        <p class="card-text display-6 text-danger"><?= $stats['high_priority'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-funnel"></i> Filters</h5>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search tasks..."
                            value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <div class="col-md-2">
                        <select name="priority" class="form-select">
                            <option value="">All Priorities</option>
                            <option value="High" <?= $priority === 'High' ? 'selected' : '' ?>>High</option>
                            <option value="Medium" <?= $priority === 'Medium' ? 'selected' : '' ?>>Medium</option>
                            <option value="Low" <?= $priority === 'Low' ? 'selected' : '' ?>>Low</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="category_id" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= $category_id == $cat['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="show_completed" class="form-select">
                            <option value="1" <?= $show_completed === '1' ? 'selected' : '' ?>>Show All</option>
                            <option value="0" <?= $show_completed === '0' ? 'selected' : '' ?>>Active Only</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Apply</button>
                        <a href="index.php" class="btn btn-secondary w-100 mt-2">Clear</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Add Todo Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Add New Task</h5>
            </div>
            <div class="card-body">
                <form action="create.php" method="POST" id="addForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="title" class="form-control" placeholder="Task title..." required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="description" class="form-control" placeholder="Description (optional)">
                        </div>
                        <div class="col-md-3">
                            <select name="priority" class="form-select">
                                <option value="Medium">Medium Priority</option>
                                <option value="High">High Priority</option>
                                <option value="Low">Low Priority</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="due_date" class="form-control" min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-3">
                            <select name="category_id" class="form-select">
                                <option value="">No Category</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-plus-lg"></i> Add Task
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Todo List -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-check"></i> My Tasks</h5>
            </div>
            <div class="card-body">
                <?php if (empty($todos)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-clipboard-check display-1 text-muted"></i>
                        <h4 class="mt-3">No tasks found</h4>
                        <p class="text-muted">Add your first task using the form above!</p>
                    </div>
                <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($todos as $todo): ?>
                            <div class="list-group-item todo-item <?= $todo['is_completed'] ? 'completed' : '' ?>">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <form action="complete.php" method="POST" class="me-3">
                                            <input type="hidden" name="id" value="<?= $todo['id'] ?>">
                                            <button type="submit" class="btn btn-sm <?= $todo['is_completed'] ? 'btn-secondary' : 'btn-outline-success' ?>">
                                                <i class="bi <?= $todo['is_completed'] ? 'bi-arrow-counterclockwise' : 'bi-check-lg' ?>"></i>
                                            </button>
                                        </form>
                                        <div>
                                            <h6 class="mb-1 <?= $todo['is_completed'] ? 'text-decoration-line-through text-muted' : '' ?>">
                                                <?= htmlspecialchars($todo['title']) ?>
                                                <?php if ($todo['description']): ?>
                                                    <small class="text-muted d-block"><?= htmlspecialchars($todo['description']) ?></small>
                                                <?php endif; ?>
                                            </h6>
                                            <div class="d-flex gap-2 mt-1">
                                                <span class="badge bg-<?=
                                                                        $todo['priority'] === 'High' ? 'danger' : ($todo['priority'] === 'Medium' ? 'warning' : 'info')
                                                                        ?>">
                                                    <?= $todo['priority'] ?>
                                                </span>
                                                <?php if ($todo['category_name']): ?>
                                                    <span class="badge" style="background-color: <?= $todo['category_color'] ?>">
                                                        <?= htmlspecialchars($todo['category_name']) ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($todo['due_date']): ?>
                                                    <span class="badge bg-<?=
                                                                            strtotime($todo['due_date']) < strtotime('+3 days') ? 'danger' : 'secondary'
                                                                            ?>">
                                                        <i class="bi bi-calendar"></i>
                                                        <?= date('M d, Y', strtotime($todo['due_date'])) ?>
                                                        <?php if (strtotime($todo['due_date']) < time() && !$todo['is_completed']): ?>
                                                            <span class="ms-1">(Overdue!)</span>
                                                        <?php endif; ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btn-group">
                                        <a href="edit.php?id=<?= $todo['id'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete.php?id=<?= $todo['id'] ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete this task?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>