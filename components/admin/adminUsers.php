<?php
session_start();

// Проверка роли пользователя (только админ может просматривать заявки)
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Подключение к базе данных
$host = 'localhost';
$db = 'auth_demo';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Получение списка пользователей
$stmt = $pdo->query("SELECT id, username, email, created_at, role, avatar FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получение списка заявок
$stmt = $pdo->query("
    SELECT a.id, a.name, a.last_name, a.phone, a.email, a.position, a.created_at, u.username 
    FROM applications a
    JOIN users u ON a.user_id = u.id
");
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Одобрение заявки
if (isset($_GET['approve'])) {
    $applicationId = $_GET['approve'];
    $stmt = $pdo->prepare("UPDATE applications SET status = 'approved' WHERE id = :id");
    $stmt->execute(['id' => $applicationId]);
    header("Location: adminUsers.php"); // Перезагружаем страницу
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ Панель | Пользователи и Заявки</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Список пользователей</h2>
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Аватар</th>
                    <th>Имя пользователя</th>
                    <th>Email</th>
                    <th>Дата регистрации</th>
                    <th>Роль</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td>
                            <?php if (!empty($user['avatar'])): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($user['avatar']) ?>" alt="Аватар" class="rounded-circle" width="50" height="50">
                            <?php else: ?>
                                <img src="default-avatar.png" alt="Нет аватара" class="rounded-circle" width="50" height="50">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2 class="text-center mb-4 mt-5">Список заявок</h2>
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Фамилия</th>
                    <th>Телефон</th>
                    <th>Email</th>
                    <th>Вакансия</th>
                    <th>Дата заявки</th>
                    <th>Пользователь</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $application): ?>
                    <tr>
                        <td><?= htmlspecialchars($application['id']) ?></td>
                        <td><?= htmlspecialchars($application['name']) ?></td>
                        <td><?= htmlspecialchars($application['last_name']) ?></td>
                        <td><?= htmlspecialchars($application['phone']) ?></td>
                        <td><?= htmlspecialchars($application['email']) ?></td>
                        <td><?= htmlspecialchars($application['position']) ?></td>
                        <td><?= htmlspecialchars($application['created_at']) ?></td>
                        <td><?= htmlspecialchars($application['username']) ?></td>
                        <td>
                            <a href="adminUsers.php?approve=<?= $application['id'] ?>" class="btn btn-success">Одобрить</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>