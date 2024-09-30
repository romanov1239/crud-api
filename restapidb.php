<?php


// Подключение к базе данных
$host = 'localhost';
$db = 'perci';
$user = 'roo';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['error' => 'Не удалось подключиться к базе данных: ' . $e->getMessage()]));
}

// Получение всех пользователей
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
}

// Получение пользователя по ID
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'Пользователь не найден']);
    }
}

// Создание нового пользователя
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Проверка наличия необходимых полей
    if (isset($data['name']) && isset($data['email'])) {
        $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Пользователь успешно создан', 'id' => $conn->lastInsertId()]);
        } else {
            echo json_encode(['error' => 'Ошибка при создании пользователя']);
        }
    } else {
        echo json_encode(['error' => 'Необходимы поля name и email']);
    }
}

// Обновление пользователя
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $data = json_decode(file_get_contents('php://input'), true);
    $fields = [];
    $params = [':id' => $id];

    if (isset($data['name'])) {
        $fields[] = "name = :name";
        $params[':name'] = $data['name'];
    }

    if (isset($data['email'])) {
        $fields[] = "email = :email";
        $params[':email'] = $data['email'];
    }

    if ($fields) {
        $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = :id";

        $stmt = $conn->prepare($sql);

        if ($stmt->execute($params)) {
            echo json_encode(['message' => 'Пользователь успешно обновлён']);
        } else {
            echo json_encode(['error' => 'Ошибка при обновлении пользователя']);
        }
    } else {
        echo json_encode(['error' => 'Необходимы поля name или email для обновления']);
    }
}

// Удаление пользователя
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Пользователь успешно удалён']);
    } else {
        echo json_encode(['error' => 'Ошибка при удалении пользователя']);
    }
}

// Закрытие соединения
$conn = null;
?>
