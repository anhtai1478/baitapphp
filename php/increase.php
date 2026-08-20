<?php
ini_set('display_errors', 0);
header('Content-Type: application/json');
session_start();

// Đọc dữ liệu JSON gửi từ JS
$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id'] ?? 0);

if ($id > 0) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Tăng số lượng lên 1
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'ID sản phẩm không hợp lệ']);
}
exit();