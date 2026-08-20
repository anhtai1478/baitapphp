<?php
ini_set('display_errors', 0);
header('Content-Type: application/json');
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id'] ?? 0);

if ($id > 0 && isset($_SESSION['cart'][$id])) {
    // Xóa sản phẩm khỏi giỏ
    unset($_SESSION['cart'][$id]);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Không thể xóa sản phẩm']);
}
exit();