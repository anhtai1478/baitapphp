<?php
ini_set('display_errors', 0);
header('Content-Type: application/json');
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id'] ?? 0);

if ($id > 0 && isset($_SESSION['cart'][$id])) {
    // Giảm số lượng đi 1
    $_SESSION['cart'][$id]--;

    // Nếu số lượng về 0 hoặc nhỏ hơn thì xóa luôn khỏi giỏ
    if ($_SESSION['cart'][$id] <= 0) {
        unset($_SESSION['cart'][$id]);
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Không thể giảm số lượng']);
}
exit();