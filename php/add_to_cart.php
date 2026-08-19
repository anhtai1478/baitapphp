<?php 
session_start();
include 'connect.php';

header('Content-Type: application/json');


if (!isset($_POST['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Không có id sản phẩm'
    ]);
    exit();
}
// chyển số nguyên
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
    'số lượng' => $_SESSION['cart'][$product_id],
    'số id' => $product_id,
    'success' => true
]);
?>