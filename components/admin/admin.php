<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "auth_demo";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add"])) {
        $name = $_POST["name"];
        $price = $_POST["price"];
        $category = $_POST["category"]; // Новое поле
        $image = addslashes(file_get_contents($_FILES["image"]["tmp_name"]));
        
        // Добавляем поле category в SQL-запрос
        $sql = "INSERT INTO catalog (name, price, images, category) VALUES ('$name', '$price', '$image', '$category')";
        $conn->query($sql);
    }
    if (isset($_POST["update"])) {
        $id = $_POST["id"];
        $name = $_POST["name"];
        $price = $_POST["price"];
        $category = $_POST["category"]; // Новое поле
        
        if (!empty($_FILES["image"]["tmp_name"])) {
            $image = addslashes(file_get_contents($_FILES["image"]["tmp_name"]));
            // Добавляем поле category в SQL-запрос
            $sql = "UPDATE catalog SET name='$name', price='$price', images='$image', category='$category' WHERE id=$id";
        } else {
            // Добавляем поле category в SQL-запрос
            $sql = "UPDATE catalog SET name='$name', price='$price', category='$category' WHERE id=$id";
        }
        $conn->query($sql);
    }
    if (isset($_POST["delete"])) {
        $id = $_POST["id"];
        $sql = "DELETE FROM catalog WHERE id=$id";
        $conn->query($sql);
    }
}

$result = $conn->query("SELECT * FROM catalog");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2 class="text-center">Admin Panel</h2>
    <form method="POST" enctype="multipart/form-data" class="mb-4">
        <input type="text" name="name" placeholder="Product Name" required class="form-control mb-2">
        <input type="number" name="price" placeholder="Price" required class="form-control mb-2">
        <input type="text" name="category" placeholder="Category" required class="form-control mb-2"> <!-- Новое поле -->
        <input type="file" name="image" required class="form-control mb-2">
        <button type="submit" name="add" class="btn btn-primary">Добавить</button>
    </form>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Category</th> <!-- Новое поле -->
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['price'] ?></td>
            <td><?= $row['category'] ?></td> <!-- Новое поле -->
            <td><img src="data:image/jpeg;base64,<?= base64_encode($row['images']) ?>" width="50"></td>
            <td>
                <form method="POST" enctype="multipart/form-data" class="d-inline">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="text" name="name" value="<?= $row['name'] ?>" class="form-control mb-2">
                    <input type="number" name="price" value="<?= $row['price'] ?>" class="form-control mb-2">
                    <input type="text" name="category" value="<?= $row['category'] ?>" class="form-control mb-2"> <!-- Новое поле -->
                    <input type="file" name="image" class="form-control mb-2">
                    <button type="submit" name="update" class="btn btn-warning">Обновить</button>
                </form>
                <form method="POST" class="d-inline">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete" class="btn btn-danger">Удалить</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>