<?php
session_start();

include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$id_user = $_SESSION['user_id'];

$error = [
    'title' => '',
    'price' => '',
    'image' => ''
];

$title = '';
$price = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // lấy dữ liệu
    $title = $_POST['title'];
    $price = $_POST['price'];

    //kiểm tra dữ liệu
    if (empty($title)) {
        $error['title'] = 'Vui lòng nhập tên sản phẩm';
    }

    if (empty($price)) {
        $error['price'] = 'Vui lòng nhập giá sản phẩm';
    }

    if (empty($_FILES['image']['name'])) {
        $error['image'] = 'Vui lòng chọn ảnh sản phẩm';
    } else {
        $img = $_FILES['image'];
        $maxsize = 1024 * 1024;
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if ($img['size'] > $maxsize) {
            $error['image'] = 'ảnh sản phẩm không được vượt quá 1MB';
        }
        // chuyển định dang ảnh sang chữ thường
        $img_ext = pathinfo($img['name'], PATHINFO_EXTENSION);
    
        if (!in_array($img_ext, $allowed)) {
            $error['image'] = 'Chỉ cho phép tải lên ảnh có định dạng JPG, PNG, GIF';
        }

        if (empty($error['title']) && empty($error['price']) && empty($error['image'])) {


            $img_name = basename($img['name']);

            // thư mục lưu trữ ảnh
            $img_dir = '../uploads/';

            $upload_file = $img_dir . $img_name;

            if (move_uploaded_file($img['tmp_name'], $upload_file)) {

                echo 'Upload ảnh thành công';

                $sql = "INSERT INTO product  (id_user, title, price, image) VALUES (?, ?, ?, ?)";


                $stmt = $conn->prepare($sql);
                // gán giá trị cho các tham số
                $stmt->bind_param("isds", $id_user, $title, $price, $img_name);

                $stmt->execute();
                header('Location: my-products.php');
                exit();
            } else {
                $error['image'] = 'Upload ảnh thất bại';
            }

        }

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Login | E-Shopper</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/prettyPhoto.css" rel="stylesheet">
    <link href="../css/price-range.css" rel="stylesheet">
    <link href="../css/animate.css" rel="stylesheet">
    <link href="../css/main.css" rel="stylesheet">
    <link href="../css/responsive.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="../images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../images/ico/apple-touch-icon-57-precomposed.png">
</head>

<body>
    <section>
        <div class="container">
            <div class="row">

                <!-- MENU BÊN TRÁI -->
                <div class="col-sm-3">
                    <div class="left-sidebar">

                        <h2>Account</h2>

                        <div class="panel-group category-products" id="accordian">

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="account.php">Account</a>
                                    </h4>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="my-product.php">My Product</a>
                                    </h4>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>


                <!-- FORM BÊN PHẢI -->
                <div class="col-sm-9">

                    <div class="blog-post-area">

                        <h2 class="title text-center">
                            Add Product
                        </h2>

                        <div class="signup-form">

                            <h2>New Product</h2>

                            <form method="POST" enctype="multipart/form-data">

                                <!-- TITLE -->
                                <input type="text" name="title" placeholder="Product Title"
                                    value="<?= htmlspecialchars($title) ?>">

                                <?php if (!empty($error['title'])): ?>
                                    <span style="color:red;">
                                        <?= $error['title'] ?>
                                    </span>
                                <?php endif; ?>


                                <!-- PRICE -->
                                <input type="text" name="price" placeholder="Product Price"
                                    value="<?= htmlspecialchars($price) ?>">

                                <?php if (!empty($error['price'])): ?>
                                    <span style="color:red;">
                                        <?= $error['price'] ?>
                                    </span>
                                <?php endif; ?>


                                <!-- IMAGE -->
                                <input type="file" name="image" accept="image/*">

                                <?php if (!empty($error['image'])): ?>
                                    <span style="color:red;">
                                        <?= $error['image'] ?>
                                    </span>
                                <?php endif; ?>


                                <!-- BUTTON -->
                                <button type="submit" class="btn btn-default">
                                    Add Product
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </section>
</body>

</html>