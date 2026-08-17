<?php 
session_start();
include 'connect.php';

if(!isset($_SESSION['user_id'])){
    header('location: login.php');
    exit();

}
$id_user = $_SESSION['user_id'];

$product_id= $_GET['id'] ?? NULL;

if(!$product_id){
    header('location: my-products.php');
    exit();
}

$sql = "DELETE FROM product WHERE id = ? AND id_user = ?";

$stmt = $conn ->prepare($sql);
$stmt -> bind_param(
    "ii",
    $product_id,
    $id_user
);

$stmt -> execute();

if($stmt ->execute()){
    header('location:my-products.php');
    exit();
}else{
    echo"không xóa được sản phẩm" . $stmt -> error; 
}

?>