<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Tài khoản đăng nhập: admin / 123
        if ($username === 'admin' && $password === '123') {
            $_SESSION['logged_in'] = true;
            header('Location: index.php');
            exit();
        } else {
            echo "<div class='alert alert-danger'>Sai tên đăng nhập hoặc mật khẩu!</div>";
        }
    }
?>
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Đăng nhập</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    </head>
    <body class="d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-sm">
            <h2 class="mb-3">Đăng nhập</h2>
            <form method="POST" action="">
                <input type="text" name="username" class="form-control mb-2" placeholder="Tên đăng nhập" required>
                <input type="password" name="password" class="form-control mb-2" placeholder="Mật khẩu" required>
                <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
            </form>
        </div>
    </body>
    </html>
<?php
    exit();
}

// Dữ liệu nhân viên từ file JSON
$dataFile = 'employees.json';
$employees = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];

// Xử lý thêm, xóa, sửa nhân viên
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'add') {
        $newId = count($employees) > 0 ? end($employees)['id'] + 1 : 1;
        $employees[] = [
            "id" => $newId,
            "name" => $_POST['name'],
            "gender" => $_POST['gender'],
            "place" => $_POST['place'],
            "department" => $_POST['department'],
            "salary" => $_POST['salary']
        ];
    }

    if ($_POST['action'] === 'edit') {
        foreach ($employees as &$emp) {
            if ($emp['id'] == $_POST['id']) {
                $emp['name'] = $_POST['name'];
                $emp['gender'] = $_POST['gender'];
                $emp['place'] = $_POST['place'];
                $emp['department'] = $_POST['department'];
                $emp['salary'] = $_POST['salary'];
                break;
            }
        }
    }

    file_put_contents($dataFile, json_encode($employees));
    header('Location: index.php');
    exit();
}

if (isset($_GET['delete'])) {
    $employees = array_filter($employees, fn($emp) => $emp['id'] != $_GET['delete']);
    file_put_contents($dataFile, json_encode(array_values($employees)));
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý nhân viên</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h2 class="mb-4">Quản lý nhân viên</h2>
    
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Giới tính</th>
                <th>Nơi sinh</th>
                <th>Phòng ban</th>
                <th>Lương</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $emp): ?>
                <tr>
                    <td><?= $emp['id'] ?></td>
                    <td><?= $emp['name'] ?></td>
                    <td><?= $emp['gender'] ?></td>
                    <td><?= $emp['place'] ?></td>
                    <td><?= $emp['department'] ?></td>
                    <td><?= $emp['salary'] ?></td>
                    <td>
                        <a href="?delete=<?= $emp['id'] ?>" class="btn btn-danger btn-sm">Xóa</a>
                        <button onclick="editEmployee(<?= htmlspecialchars(json_encode($emp)) ?>)" class="btn btn-warning btn-sm">Sửa</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Form thêm nhân viên -->
    <h4>Thêm nhân viên</h4>
    <form method="POST" class="mb-3">
        <input type="hidden" name="action" value="add">
        <input type="text" name="name" class="form-control mb-2" placeholder="Tên nhân viên" required>
        <input type="text" name="gender" class="form-control mb-2" placeholder="Giới tính" required>
        <input type="text" name="place" class="form-control mb-2" placeholder="Nơi sinh" required>
        <input type="text" name="department" class="form-control mb-2" placeholder="Phòng ban" required>
        <input type="number" name="salary" class="form-control mb-2" placeholder="Lương" required>
        <button type="submit" class="btn btn-success">Thêm</button>
    </form>

    
    </script>
</body>
</html>
