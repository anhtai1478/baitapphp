<?php 
session_start();
include 'connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Bạn chưa đăng nhập'
    ]);
    exit();
}

if (!isset($_POST['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Không có id sản phẩm'
    ]);
    exit();
}

$product_id = intval($_POST['id']);

$sql = "SELECT * FROM product WHERE id = ?";
$stmt = $conn->prepare($sql);

$stmt->bind_param('i', $product_id);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Không tìm thấy sản phẩm'
    ]);
    exit();
}

$product = $result->fetch_assoc();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]++;
} else {
    $_SESSION['cart'][$product_id] = 1;
}

echo json_encode([
    'success' => true,
    'message' => 'Thêm sản phẩm vào giỏ hàng thành công'
]);
?>