<?php

ini_set('display_errors', 0);

header('Content-Type: application/json');

session_start();

$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id'] ?? 0);


if (!isset($_SESSION['cart'][$id])) {

    echo json_encode([
        'success' => false,
        'message' => 'Sản phẩm không có trong giỏ hàng'
    ]);
    exit();
}


// Xóa sản phẩm khỏi Session
unset($_SESSION['cart'][$id]);


echo json_encode([
    'success' => true,
    'message' => 'Xóa sản phẩm thành công'
]);

exit();