<?php
header('Content-Type: application/json');

// Đọc dữ liệu JSON từ fetch
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Dữ liệu không hợp lệ']);
    exit;
}

$fullname = $data['fullname'] ?? null;
$phone = $data['phone'] ?? null;
$email = $data['email'] ?? null;
$train_program = $data['train_program'] ?? null;
$note = $data['note'] ?? null;

if (!$fullname || !$phone || !$email) {
    echo json_encode(['success' => false, 'error' => 'Thiếu thông tin bắt buộc']);
    exit;
}

$conn = new mysqli("localhost", "root", "", "phq");
$conn->set_charset("utf8");

// Kiểm tra kết nối
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Kết nối thất bại']);
    exit;
}

// Lưu vào cơ sở dữ liệu
$stmt = $conn->prepare("INSERT INTO contact_form (fullname, phone, email, train_program, note, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("sssss", $fullname, $phone, $email, $train_program, $note);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Không thể lưu dữ liệu']);
}

$stmt->close();
$conn->close();
