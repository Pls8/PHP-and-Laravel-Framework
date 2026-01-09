<?php
// Connects to MySQL using PDO(php data objects)
$host = 'localhost';
$dbname = 'todo_app';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
    exit;
}

// CREATE TABLE IF NOT EXISTS users (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     username VARCHAR(50) UNIQUE NOT NULL,
//     email VARCHAR(100) UNIQUE NOT NULL,
//     password VARCHAR(255) NOT NULL,
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
// );

// CREATE TABLE IF NOT EXISTS categories (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     name VARCHAR(50) NOT NULL,
//     user_id INT,
//     color VARCHAR(7) DEFAULT '#007bff',
//     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
// );

// CREATE TABLE IF NOT EXISTS todos (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     title VARCHAR(255) NOT NULL,
//     description TEXT,
//     is_completed BOOLEAN DEFAULT FALSE,
//     priority ENUM('Low', 'Medium', 'High') DEFAULT 'Medium',
//     due_date DATE,
//     category_id INT,
//     user_id INT,
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
//     FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
//     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
// );
