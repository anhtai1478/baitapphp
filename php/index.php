<?php
session_start();

include 'connect.php';

$sql = "SELECT * FROM product ORDER BY id DESC";

$result = $conn->query($sql);

if (!$result) {
    die("Lỗi " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Home | E-Shopper</title>

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/prettyPhoto.css" rel="stylesheet">
    <link href="../css/price-range.css" rel="stylesheet">
    <link href="../css/animate.css" rel="stylesheet">
    <link href="../css/main.css" rel="stylesheet">
    <link href="../css/responsive.css" rel="stylesheet">
</head>

<body>

    <header id="header">

        <div class="header_top">
            <div class="container">
                <div class="row">

                    <div class="col-sm-6">
                        <div class="contactinfo">
                            <ul class="nav nav-pills">
                                <li>
                                    <a href="#">
                                        <i class="fa fa-phone"></i>
                                        0123456789
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <i class="fa fa-envelope"></i>
                                        example@gmail.com
                                    </a>
                                </li>
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

                                    <!-- Khi đã đăng nhập -->
                                    <li>
                                        <a href="account.php">
                                            <i class="fa fa-user"></i>
                                            Account
                                        </a>
                                    </li>

                                    <li>
                                        <a href="my_product.php">
                                            <i class="fa fa-shopping-bag"></i>
                                            My Product
                                        </a>
                                    </li>

                                    <li>
                                        <a href="logout.php">
                                            <i class="fa fa-sign-out"></i>
                                            Logout
                                        </a>
                                    </li>

                                <?php else: ?>

                                    <!-- Khi chưa đăng nhập -->
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

                <div class="col-sm-3">

                    <div class="left-sidebar">

                        <h2>Category</h2>

                        <div class="panel-group category-products">

                            <div class="panel panel-default">

                                <div class="panel-heading">

                                    <h4 class="panel-title">

                                        <a href="index.php">
                                            All Product
                                        </a>

                                    </h4>

                                </div>

                            </div>

                            <?php if (isset($_SESSION['user_id'])): ?>

                                <div class="panel panel-default">

                                    <div class="panel-heading">

                                        <h4 class="panel-title">

                                            <a href="account.php">
                                                Account
                                            </a>

                                        </h4>

                                    </div>

                                </div>

                                <div class="panel panel-default">

                                    <div class="panel-heading">

                                        <h4 class="panel-title">

                                            <a href="my_product.php">
                                                My Product
                                            </a>

                                        </h4>

                                    </div>

                                </div>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>


                <div class="col-sm-9">

                    <div class="features_items">

                        <h2 class="title text-center">
                            Products
                        </h2>


                        <?php if ($result->num_rows == 0): ?>


                            <div style="text-align: center; padding: 30px;">

                                <h3>
                                    Chưa có sản phẩm nào
                                </h3>

                            </div>


                        <?php else: ?>



                            <?php while ($product = $result->fetch_assoc()): ?>

                                <div class="col-sm-4">

                                    <div class="product-image-wrapper">

                                        <div class="single-products">

                                            <div class="productinfo text-center">


                                                <img
                                                    src="../uploads/<?= htmlspecialchars($product['image']) ?>"
                                                    alt="<?= htmlspecialchars($product['title']) ?>"
                                                    
                                                >



                                                <h2>

                                                    <?= number_format($product['price']) ?>

                                                    VNĐ

                                                </h2>



                                                <p>

                                                    <?= htmlspecialchars($product['title']) ?>

                                                </p>



                                                <a
                                                    href="#"
                                                    class="btn btn-default add-to-cart"
                                                >

                                                    <i class="fa fa-shopping-cart"></i>

                                                    Add to cart

                                                </a>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            <?php endwhile; ?>


                        <?php endif; ?>


                    </div>

                </div>

            </div>

        </div>

    </section>
   

    <footer id="footer">

        <div class="footer-bottom">

            <div class="container">

                <div class="row">

                    <div class="col-sm-12">

                        <p class="text-center">
                            © 2026 E-Shopper
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </footer>

  

    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.scrollUp.min.js"></script>
    <script src="../js/price-range.js"></script>
    <script src="../js/jquery.prettyPhoto.js"></script>
    <script src="../js/main.js"></script>

</body>

</html>