<?php

session_start();

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true) ?? [];
$product_id = intval($data['id'] ?? 0);
$action = $data['action'] ?? 'update';

if ($product_id < 1 || !isset($_SESSION['cart'][$product_id])) {
    echo json_encode([
        'success' => false,
        'message' => 'Sản phẩm không có trong giỏ hàng'
    ]);
    exit();
}

if ($action === 'delete') {
    unset($_SESSION['cart'][$product_id]);

    echo json_encode([
        'success' => true,
        'message' => 'Xóa sản phẩm thành công'
    ]);
    exit();
}

if (!isset($data['qty'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Thiếu qty'
    ]);
    exit();
}

$qty = intval($data['qty']);

if ($qty < 1) {
    echo json_encode([
        'success' => false,
        'message' => 'Số lượng phải lớn hơn hoặc bằng 1'
    ]);
    exit();
}

$_SESSION['cart'][$product_id] = $qty;

echo json_encode([
    'success' => true,
    'message' => 'Cập nhật số lượng thành công',
    'id' => $product_id,
    'qty' => $qty
]);