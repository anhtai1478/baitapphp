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
    <header id="header">

        <div class="header_top">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="contactinfo">
                            <ul class="nav nav-pills">
                                <li><a href="#"><i class="fa fa-phone"></i> +2 95 01 88 821</a></li>
                                <li><a href="#"><i class="fa fa-envelope"></i> info@domain.com</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="social-icons pull-right">
                            <ul class="nav navbar-nav">
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="#"><i class="fa fa-dribbble"></i></a></li>
                                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="header-middle">
            <div class="container">
                <div class="row">

                    <div class="col-sm-4">
                        <div class="logo pull-left">
                            <a href="index.php">
                                <h2>E-Shopper</h2>
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-8">

                        <div class="shop-menu pull-right">

                            <ul class="nav navbar-nav">

                                <li>
                                    <a href="index.php">
                                        <i class="fa fa-home"></i>
                                        Home
                                    </a>
                                </li>

                                <?php if (isset($_SESSION['user_id'])): ?>


                                    <li>
                                        <a href="account.php">
                                            <i class="fa fa-user"></i>
                                            Account
                                        </a>
                                    </li>

                                    <li><a href="cart.html"><i class="fa fa-shopping-cart"></i> Cart</a></li>
                                    <li><a href=""><i class="fa fa-star"></i> Wishlist</a></li>


                                    <li><a href="checkout.html"><i class="fa fa-crosshairs"></i> Checkout</a></li>

                                    <li>
                                        <a href="logout.php">
                                            <i class="fa fa-sign-out"></i>
                                            Logout
                                        </a>
                                    </li>

                                <?php else: ?>


                                    <li>
                                        <a href="login.php">
                                            <i class="fa fa-lock"></i>
                                            Login
                                        </a>
                                    </li>

                                    <li>
                                        <a href="register.php">
                                            <i class="fa fa-user"></i>
                                            Register
                                        </a>
                                    </li>

                                <?php endif; ?>

                            </ul>

                        </div>

                    </div>

                </div>
            </div>
        </div>

    </header>
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

    <footer id="footer"><!--Footer-->
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="companyinfo">
                            <h2><span>e</span>-shopper</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit,sed do eiusmod tempor</p>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="col-sm-3">
                            <div class="video-gallery text-center">
                                <a href="#">
                                    <div class="iframe-img">
                                        <img src="images/home/iframe1.png" alt="" />
                                    </div>
                                    <div class="overlay-icon">
                                        <i class="fa fa-play-circle-o"></i>
                                    </div>
                                </a>
                                <p>Circle of Hands</p>
                                <h2>24 DEC 2014</h2>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="video-gallery text-center">
                                <a href="#">
                                    <div class="iframe-img">
                                        <img src="images/home/iframe2.png" alt="" />
                                    </div>
                                    <div class="overlay-icon">
                                        <i class="fa fa-play-circle-o"></i>
                                    </div>
                                </a>
                                <p>Circle of Hands</p>
                                <h2>24 DEC 2014</h2>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="video-gallery text-center">
                                <a href="#">
                                    <div class="iframe-img">
                                        <img src="images/home/iframe3.png" alt="" />
                                    </div>
                                    <div class="overlay-icon">
                                        <i class="fa fa-play-circle-o"></i>
                                    </div>
                                </a>
                                <p>Circle of Hands</p>
                                <h2>24 DEC 2014</h2>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="video-gallery text-center">
                                <a href="#">
                                    <div class="iframe-img">
                                        <img src="images/home/iframe4.png" alt="" />
                                    </div>
                                    <div class="overlay-icon">
                                        <i class="fa fa-play-circle-o"></i>
                                    </div>
                                </a>
                                <p>Circle of Hands</p>
                                <h2>24 DEC 2014</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="address">
                            <img src="images/home/map.png" alt="" />
                            <p>505 S Atlantic Ave Virginia Beach, VA(Virginia)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-widget">
            <div class="container">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>Service</h2>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="#">Online Help</a></li>
                                <li><a href="#">Contact Us</a></li>
                                <li><a href="#">Order Status</a></li>
                                <li><a href="#">Change Location</a></li>
                                <li><a href="#">FAQ’s</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>Quock Shop</h2>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="#">T-Shirt</a></li>
                                <li><a href="#">Mens</a></li>
                                <li><a href="#">Womens</a></li>
                                <li><a href="#">Gift Cards</a></li>
                                <li><a href="#">Shoes</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>Policies</h2>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="#">Terms of Use</a></li>
                                <li><a href="#">Privecy Policy</a></li>
                                <li><a href="#">Refund Policy</a></li>
                                <li><a href="#">Billing System</a></li>
                                <li><a href="#">Ticket System</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>About Shopper</h2>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="#">Company Information</a></li>
                                <li><a href="#">Careers</a></li>
                                <li><a href="#">Store Location</a></li>
                                <li><a href="#">Affillate Program</a></li>
                                <li><a href="#">Copyright</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-3 col-sm-offset-1">
                        <div class="single-widget">
                            <h2>About Shopper</h2>
                            <form action="#" class="searchform">
                                <input type="text" placeholder="Your email address" />
                                <button type="submit" class="btn btn-default"><i
                                        class="fa fa-arrow-circle-o-right"></i></button>
                                <p>Get the most recent updates from <br />our site and be updated your self...</p>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <p class="pull-left">Copyright © 2013 E-SHOPPER Inc. All rights reserved.</p>
                    <p class="pull-right">Designed by <span><a target="_blank"
                                href="http://www.themeum.com">Themeum</a></span></p>
                </div>
            </div>
        </div>

    </footer>
</body>

</html>