<?php
$servername = "localhost"; // имя сервера, например, localhost
$username = "root"; // ваше имя пользователя
$password = ""; // ваш пароль
$dbname = "perci"; // имя вашей базы данных

try {
    // Создаем соединение с базой данных
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    // Устанавливаем режим ошибок PDO
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL для создания таблицы
    $sql = "CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    // Выполняем SQL-запрос
    $conn->exec($sql);
    echo "Таблица users успешно создана.";
} catch (PDOException $e) {
    echo "Ошибка создания таблицы: " . $e->getMessage();
}

// Закрываем соединение
$conn = null;
?>
