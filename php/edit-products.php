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

    header('Location: my-product.php');
    exit();

}

$sql = "SELECT * FROM product WHERE id = ? AND id_user = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ii",
    $product_id,
    $id_user
);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows == 0) {

    echo "Không tìm thấy sản phẩm ";
    exit();

}


$product = $result->fetch_assoc();


$title = $product['title'];

$price = $product['price'];

$image = $product['image'];


$error = [

    'title' => '',

    'price' => '',

    'image' => ''

];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $title = trim($_POST['title'] ?? '');

    $price = trim($_POST['price'] ?? '');


    if (empty($title)) {

        $error['title'] =
            'Vui lòng nhập tên sản phẩm';

    }


    if (empty($price)) {

        $error['price'] =
            'Vui lòng nhập giá sản phẩm';

    } elseif (!is_numeric($price)) {

        $error['price'] =
            'Giá sản phẩm phải là số';

    } elseif ($price < 0) {

        $error['price'] =
            'Giá sản phẩm không được âm';

    }


    $new_image = $image;


    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE
    ) {


        $img = $_FILES['image'];


        if ($img['error'] !== UPLOAD_ERR_OK) {

            $error['image'] =
                'Upload ảnh thất bại';

        }

        $maxsize = 1024 * 1024;


        if ($img['size'] > $maxsize) {

            $error['image'] =
                'Ảnh không được vượt quá 1MB';

        }


        
        $allowed = [

            'jpg',
            'jpeg',
            'png',
            'gif'

        ];


        $img_ext = strtolower(
            pathinfo(
                $img['name'],
                PATHINFO_EXTENSION
            )
        );


        if (!in_array($img_ext, $allowed)) {

            $error['image'] =
                'Chỉ cho phép JPG, JPEG, PNG, GIF';

        }


        if (empty($error['image'])) {

            $new_image =
                time() . '_' . basename($img['name']);


            $upload_dir = '../uploads/';


            $upload_file =
                $upload_dir . $new_image;


            if (
                !move_uploaded_file(
                    $img['tmp_name'],
                    $upload_file
                )
            ) {

                $error['image'] =
                    'Upload ảnh thất bại';

            }

        }

    }


    if (
        empty($error['title']) &&
        empty($error['price']) &&
        empty($error['image'])
    ) {


        $sql = "
            UPDATE product
            SET
                title = ?,
                price = ?,
                image = ?
            WHERE
                id = ?
                AND id_user = ?
        ";


        $stmt = $conn->prepare($sql);


        $stmt->bind_param(
            "sdsii",
            $title,
            $price,
            $new_image,
            $product_id,
            $id_user
        );


        if ($stmt->execute()) {


            if (
                $new_image !== $image &&
                !empty($image)
            ) {


                $old_image =
                    '../uploads/' . $image;


                if (file_exists($old_image)) {

                    unlink($old_image);

                }

            }


            // Quay về my product

            header('Location: my_product.php');

            exit();


        } else {

            echo "Lỗi cập nhật sản phẩm: "
                . $stmt->error;

        }

    }

}

?>



<!DOCTYPE html>

<html lang="en">


<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Product</title>


    <!-- CSS -->

    <link
        href="../css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="../css/font-awesome.min.css"
        rel="stylesheet"
    >

    <link
        href="../css/main.css"
        rel="stylesheet"
    >

    <link
        href="../css/responsive.css"
        rel="stylesheet"
    >

</head>


<body>


<section>

    <div class="container">

        <div class="row">



            <div class="col-sm-3">

                <div class="left-sidebar">

                    <h2>Account</h2>


                    <div
                        class="panel-group category-products"
                        id="accordian"
                    >


                        <!-- ACCOUNT -->

                        <div class="panel panel-default">

                            <div class="panel-heading">

                                <h4 class="panel-title">

                                    <a href="account.php">

                                        ACCOUNT

                                    </a>

                                </h4>

                            </div>

                        </div>


                        <!-- MY PRODUCT -->

                        <div class="panel panel-default">

                            <div class="panel-heading">

                                <h4 class="panel-title">

                                    <a href="my_product.php">

                                        MY PRODUCT

                                    </a>

                                </h4>

                            </div>

                        </div>


                    </div>


                </div>

            </div>



            <div class="col-sm-9">


                <div class="signup-form">


                    <h2>
                        Edit Product
                    </h2>


                    <form
                        method="POST"
                        enctype="multipart/form-data"
                    >


                        <input
                            type="text"
                            name="title"
                            placeholder="Product Title"
                            value="<?= htmlspecialchars($title) ?>"
                        >


                        <?php if (!empty($error['title'])): ?>

                            <span
                                style="color:red;"
                            >

                                <?= $error['title'] ?>

                            </span>

                        <?php endif; ?>


                        <br>

                        <input
                            type="text"
                            name="price"
                            placeholder="Product Price"
                            value="<?= htmlspecialchars($price) ?>"
                        >


                        <?php if (!empty($error['price'])): ?>

                            <span
                                style="color:red;"
                            >

                                <?= $error['price'] ?>

                            </span>

                        <?php endif; ?>


                        <br>



                        <p>

                            <strong>
                                Ảnh hiện tại:
                            </strong>

                        </p>


                        <?php if (!empty($image)): ?>

                            <img
                                src="../uploads/<?= htmlspecialchars($image) ?>"
                                alt="Product image"
                                width="150"
                                style="
                                    display:block;
                                    margin-bottom:15px;
                                "
                            >

                        <?php else: ?>

                            <p>
                                Sản phẩm chưa có ảnh
                            </p>

                        <?php endif; ?>




                        <label>

                            Chọn ảnh mới:

                        </label>


                        <input
                            type="file"
                            name="image"
                            accept=".jpg,.jpeg,.png,.gif"
                        >


                        <?php if (!empty($error['image'])): ?>

                            <span
                                style="color:red;"
                            >

                                <?= $error['image'] ?>

                            </span>

                        <?php endif; ?>


                        <p>

                            <small>
                                Nếu không chọn ảnh mới,
                                ảnh cũ sẽ được giữ nguyên.
                            </small>

                        </p>



                        <button
                            type="submit"
                            class="btn btn-default"
                        >

                            Update Product

                        </button>


                       


                    </form>


                </div>


            </div>


        </div>

    </div>

</section>


</body>

</html>