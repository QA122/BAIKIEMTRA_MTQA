<?php
session_start();

if (!isset($_SESSION['logged_in'])) {
    $_SESSION['logged_in'] = false;  // Khởi tạo trạng thái đăng nhập nếu chưa có
}

// Kiểm tra nếu chưa đăng nhập, hiển thị form đăng nhập
if (!$_SESSION['logged_in']) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'], $_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Giả sử username là admin và password là 123 (thay thế bằng dữ liệu thực tế)
        if ($username === 'admin' && $password === '123') {
            $_SESSION['logged_in'] = true;
            header('Location: index.php'); // Chuyển hướng sau khi đăng nhập thành công
            exit();
        } else {
            echo "<p style='color:red;'>Sai tên đăng nhập hoặc mật khẩu!</p>";
        }
    }

    // Hiển thị form đăng nhập
    echo '
    <form method="POST" action="">
        <h2>Đăng nhập</h2>
        <input type="text" name="username" placeholder="Tên đăng nhập" class="form-control mb-2" required>
        <input type="password" name="password" placeholder="Mật khẩu" class="form-control mb-2" required>
        <button type="submit" class="btn btn-primary">Đăng nhập</button>
    </form>';
    exit();
}

// Xử lý đăng xuất
if (isset($_GET['logout'])) {
    $_SESSION['logged_in'] = false;
    session_destroy();
    header('Location: index.php');
    exit();
}
?>
