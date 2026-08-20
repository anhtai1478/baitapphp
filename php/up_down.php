<?php

ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');
session_start();

// Đọc dữ liệu gửi từ JS (Fetch Payload JSON)
$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);


if (!$data) {
    $data = $_POST;
}

$id = isset($data['id']) ? intval($data['id']) : 0;
$qty = isset($data['qty']) ? intval($data['qty']) : 0;

if ($id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'ID sản phẩm không hợp lệ!'
    ]);
    exit();
}


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


if ($qty > 0) {
    $_SESSION['cart'][$id] = $qty;
} else {
    unset($_SESSION['cart'][$id]); 
}

echo json_encode([
    'success' => true,
    'message' => 'Cập nhật thành công!'
]);
exit();