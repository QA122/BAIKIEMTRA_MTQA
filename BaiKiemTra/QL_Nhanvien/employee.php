<?php
session_start();

if (!isset($_SESSION['user'])) {
    echo 'unauthorized';
    exit();
}

// Đọc dữ liệu từ file JSON
$dataFile = 'employees.json';
$employees = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];

// Xử lý GET - Lấy danh sách nhân viên
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($employees);
    exit();
}

// Xử lý POST - Thêm nhân viên mới
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $place = $_POST['place'];
    $department = $_POST['department'];
    $salary = $_POST['salary'];

    $newId = count($employees) > 0 ? end($employees)['id'] + 1 : 1;
    $newEmployee = ["id" => $newId, "name" => $name, "gender" => $gender, "place" => $place, "department" => $department, "salary" => $salary];

    $employees[] = $newEmployee;

    // Lưu vào file JSON
    file_put_contents($dataFile, json_encode($employees));

    echo json_encode(["status" => "success", "message" => "Đã thêm nhân viên!", "employee" => $newEmployee]);
    exit();
}

// Xử lý DELETE - Xóa nhân viên
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $idToDelete = $data['id'];

    // Lọc bỏ nhân viên cần xóa
    $employees = array_filter($employees, function($emp) use ($idToDelete) {
        return $emp['id'] != $idToDelete;
    });

    // Cập nhật lại file JSON sau khi xóa
    file_put_contents($dataFile, json_encode(array_values($employees)));

    echo json_encode(["status" => "success", "message" => "Đã xóa nhân viên có ID: $idToDelete"]);
    exit();
}
?>
