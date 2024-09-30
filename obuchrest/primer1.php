<?php
header('Content-Type: application/json');

// Подключение к базе данных
$host = 'localhost';
$db = 'perci';
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['error' => 'Не удалось подключиться к базе данных: ' . $e->getMessage()]));
}

// Удаление пользователя
// Проверяем, является ли метод запроса DELETE и передан ли параметр id в URL
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {
    // Приводим id к целочисленному типу для безопасности
    $id = intval($_GET['id']);

    // Готовим SQL-запрос на удаление пользователя с заданным id
    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");

    // Привязываем параметр :id к переменной $id
    // Указываем, что тип параметра - целое число
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Выполняем подготовленный запрос
    if ($stmt->execute()) {
        // Если запрос выполнен успешно, возвращаем сообщение об успехе в формате JSON
        echo json_encode(['message' => 'Пользователь успешно удалён']);
    } else {
        // Если произошла ошибка при выполнении запроса, возвращаем сообщение об ошибке в формате JSON
        echo json_encode(['error' => 'Ошибка при удалении пользователя']);
    }
}
?>


// Закрытие соединения
$conn = null;
?>
