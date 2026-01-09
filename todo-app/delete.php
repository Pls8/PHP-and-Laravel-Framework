<?
require_once __DIR__ . '/config/database.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM todos WHERE id = :id");
    $stmt->execute([':id' => $id]);
}
header('Location: index.php');
exit;

