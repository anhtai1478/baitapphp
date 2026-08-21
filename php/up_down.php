<?php

session_start();

header('Content-Type: application/json');

// json_decode: chuyển dữ liệu thành php
// file_get_contents("php://input"): đọc dữ liệu từ js gửi về 
$data = json_decode(file_get_contents("php://input"), true);


// Kiểm tra có id và qty hay chưa
if (!isset($data['id']) || !isset($data['qty'])) {

    echo json_encode([
        'success' => false,
        'message' => 'Thiếu id hoặc qty'
    ]);

    exit();
}


// Lấy id và qty
$product_id = intval($data['id']);
$qty = intval($data['qty']);

// Kiểm tra sản phẩm có trong giỏ hàng không
if (!isset($_SESSION['cart'][$product_id])) {

    echo json_encode([
        'success' => false,
        'message' => 'Sản phẩm không có trong giỏ hàng'
    ]);

    exit();
}


// Không cho qty nhỏ hơn 1
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

?>