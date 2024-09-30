<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Пользователей</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1, h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        pre {
            background-color: #f4f4f4;
            padding: 10px;
            border: 1px solid #ccc;
            overflow-x: auto;
        }
    </style>
</head>
<body>
<h1>API Пользователей</h1>

<h2>Запросы API</h2>

<h3>Получить всех пользователей</h3>
<button onclick="getAllUsers()">Получить пользователей</button>

<h2>Список пользователей</h2>
<table id="usersTable">
    <thead>
    <tr>
        <th>ID</th>
        <th>Имя</th>
        <th>Email</th>
        <th>Действия</th>
    </tr>
    </thead>
    <tbody id="usersBody">
    <!-- Пользователи будут добавлены сюда -->
    </tbody>
</table>

<h3>Создать нового пользователя</h3>
<input type="text" id="newUserName" placeholder="Имя">
<input type="email" id="newUserEmail" placeholder="Email">
<button onclick="createUser()">Создать пользователя</button>
<pre id="createUserResult"></pre>

<h3>Обновить пользователя</h3>
<input type="number" id="updateUserId" placeholder="ID пользователя">
<input type="text" id="updateUserName" placeholder="Новое имя">
<input type="email" id="updateUserEmail" placeholder="Новый Email">
<button onclick="updateUser()">Обновить пользователя</button>
<pre id="updateUserResult"></pre>

<h3>Удалить пользователя</h3>
<input type="number" id="deleteUserId" placeholder="ID пользователя">
<button onclick="deleteUser()">Удалить пользователя</button>
<pre id="deleteUserResult"></pre>

<script>
    const apiUrl = './restapidb.php'; // URL вашего API

    function getAllUsers() {
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                const usersBody = document.getElementById('usersBody');
                usersBody.innerHTML = ''; // Очищаем таблицу перед добавлением данных
                data.forEach(user => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                            <td>${user.id}</td>
                            <td>${user.name}</td>
                            <td>${user.email}</td>
                            <td>
                                <button onclick="populateUpdateForm(${user.id}, '${user.name}', '${user.email}')">Изменить</button>
                            </td>
                        `;
                    usersBody.appendChild(row);
                });
            });
    }

    function createUser() {
        const name = document.getElementById('newUserName').value;
        const email = document.getElementById('newUserEmail').value;

        fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name, email })
        })
            .then(response => response.json())
            .then(data => {
                document.getElementById('createUserResult').textContent = JSON.stringify(data, null, 2);
                getAllUsers(); // Обновляем таблицу после создания
            });
    }

    function populateUpdateForm(id, name, email) {
        document.getElementById('updateUserId').value = id;
        document.getElementById('updateUserName').value = name;
        document.getElementById('updateUserEmail').value = email;
    }

    function updateUser() {
        const id = document.getElementById('updateUserId').value;
        const name = document.getElementById('updateUserName').value;
        const email = document.getElementById('updateUserEmail').value;

        let body = {};
        if (name) body.name = name;
        if (email) body.email = email;

        fetch(`${apiUrl}?id=${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(body)
        })
            .then(response => response.json())
            .then(data => {
                document.getElementById('updateUserResult').textContent = JSON.stringify(data, null, 2);
                getAllUsers(); // Обновляем таблицу после обновления
            });
    }

    function deleteUser() {
        const id = document.getElementById('deleteUserId').value;

        fetch(`${apiUrl}?id=${id}`, {
            method: 'DELETE'
        })
            .then(response => response.json())
            .then(data => {
                document.getElementById('deleteUserResult').textContent = JSON.stringify(data, null, 2);
                getAllUsers(); // Обновляем таблицу после удаления
            });
    }
</script>
</body>
</html>
