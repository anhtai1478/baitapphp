<?php

session_start();

include 'connect.php';


if (!isset($_SESSION['user_id'])) {

    header('Location: login.php');
    exit();

}

$id_user = $_SESSION['user_id'];


$product_id = $_GET['id'] ?? null;


if (!$product_id) {

    header('Location: my_product.php');
    exit();

}


$sql = "SELECT * FROM product
        WHERE id = ? AND id_user = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ii",
    $product_id,
    $id_user
);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows == 0) {

    echo "Không tìm thấy sản phẩm";

    exit();

}


$product = $result->fetch_assoc();


$title = $product['title'];

$price = $product['price'];

$image = $product['image'];



$err = [

    'title' => '',

    'price' => '',

    'image' => ''

];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    // Lấy dữ liệu
    $title = trim($_POST['title'] ?? '');

    $price = trim($_POST['price'] ?? '');


    // ==================================
    // KIỂM TRA TITLE
    // ==================================

    if (empty($title)) {

        $err['title'] =
            'Vui lòng nhập tên sản phẩm';

    }

    if (empty($price)) {

        $err['price'] =
            'Vui lòng nhập giá sản phẩm';

    }

    if (!empty($_FILES['image']['name'])) {


        $img = $_FILES['image'];


        // Dung lượng tối đa 1MB
        $maxsize = 1024 * 1024;


        // Định dạng cho phép
        $allowed = [
            'jpg',
            'jpeg',
            'png',
            'gif'
        ];


        // Kiểm tra dung lượng
        if ($img['size'] > $maxsize) {

            $err['image'] =
                'Ảnh không được vượt quá 1MB';

        }


        // Lấy đuôi file
        $img_ext = strtolower(
            pathinfo(
                $img['name'],
                PATHINFO_EXTENSION
            )
        );


        // Kiểm tra định dạng
        if (!in_array($img_ext, $allowed)) {

            $err['image'] =
                'Ảnh phải có định dạng JPG, JPEG, PNG hoặc GIF';

        }


        // Kiểm tra lỗi upload
        if ($img['error'] !== UPLOAD_ERR_OK) {

            $err['image'] =
                'Có lỗi khi upload ảnh';

        }

    }


    if (
        empty($err['title']) &&
        empty($err['price']) &&
        empty($err['image'])
    ) {

        if (!empty($_FILES['image']['name'])) {


            $img = $_FILES['image'];


            // Tên ảnh
            $img_name = basename($img['name']);


            // Thư mục upload
            $upload_dir = '../uploads/';


            // Đường dẫn upload
            $upload_file = $upload_dir . $img_name;


            // Upload ảnh
            if (
                move_uploaded_file(
                    $img['tmp_name'],
                    $upload_file
                )
            ) {


                // Update cả ảnh
                $sql = "UPDATE product
                        SET title = ?,
                            price = ?,
                            image = ?
                        WHERE id = ?
                        AND id_user = ?";


                $stmt = $conn->prepare($sql);


                $stmt->bind_param(
                    "sdsii",
                    $title,
                    $price,
                    $img_name,
                    $product_id,
                    $id_user
                );


            } else {

                $err['image'] =
                    'Upload ảnh thất bại';

            }


        } else {

            $sql = "UPDATE product
                    SET title = ?,
                        price = ?
                    WHERE id = ?
                    AND id_user = ?";


            $stmt = $conn->prepare($sql);


            $stmt->bind_param(
                "sdii",
                $title,
                $price,
                $product_id,
                $id_user
            );

        }



        if (empty($err['image'])) {


            if ($stmt->execute()) {


                header(
                    'Location: my_product.php'
                );

                exit();


            } else {

                echo "Lỗi cập nhật: "
                    . $stmt->error;

            }

        }

    }

}

?>