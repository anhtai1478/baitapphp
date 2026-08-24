<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit();
}

$cart = $_SESSION['cart'] ?? [];
$products_in_cart = [];
$grand_total = 0;
$cart_count = array_sum($cart);

if (!empty($cart)) {
    $product_ids = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    $types = str_repeat('i', count($product_ids));

    $sql = "SELECT * FROM product WHERE id IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$product_ids);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $products_in_cart[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cart | E-Shopper</title>

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/prettyPhoto.css" rel="stylesheet">
    <link href="../css/price-range.css" rel="stylesheet">
    <link href="../css/animate.css" rel="stylesheet">
    <link href="../css/main.css" rel="stylesheet">
    <link href="../css/responsive.css" rel="stylesheet">

    <link rel="shortcut icon" href="../images/ico/favicon.ico">
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
                                        +2 95 01 88 821
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <i class="fa fa-envelope"></i>
                                        info@domain.com
                                    </a>
                                </li>

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

                    <div class="col-md-4 clearfix">

                        <div class="logo pull-left">

                            <a href="index.php">
                                <img src="../images/home/logo.png" alt="">
                            </a>

                        </div>

                    </div>


                    <div class="col-md-8 clearfix">

                        <div class="shop-menu clearfix pull-right">

                            <ul class="nav navbar-nav">

                                <?php if (isset($_SESSION['user_id'])): ?>

                                    <li>
                                        <a href="account.php">
                                            <i class="fa fa-user"></i>
                                            Account
                                        </a>
                                    </li>

                                <?php endif; ?>


                                <li>
                                    <a href="#">
                                        <i class="fa fa-star"></i>
                                        Wishlist
                                    </a>
                                </li>


                                <li>
                                    <a href="#">
                                        <i class="fa fa-crosshairs"></i>
                                        Checkout
                                    </a>
                                </li>


                                <li><a href="cart.php">
                                        <i class="fa fa-shopping-cart">

                                        </i>
                                        Cart
                                        <span id="cart_count"><?= $cart_count ?></span>
                                    </a>
                                </li>


                                <?php if (isset($_SESSION['user_id'])): ?>

                                    <li>
                                        <a href="logout.php">
                                            <i class="fa fa-lock"></i>
                                            Logout
                                        </a>
                                    </li>

                                <?php else: ?>

                                    <li>
                                        <a href="register.php">
                                            <i class="fa fa-user"></i>
                                            Register
                                        </a>
                                    </li>

                                    <li>
                                        <a href="login.php">
                                            <i class="fa fa-lock"></i>
                                            Login
                                        </a>
                                    </li>

                                <?php endif; ?>

                            </ul>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="header-bottom">

            <div class="container">

                <div class="row">

                    <div class="col-sm-9">

                        <div class="navbar-header">

                            <button type="button" class="navbar-toggle" data-toggle="collapse"
                                data-target=".navbar-collapse">

                                <span class="sr-only">Toggle navigation</span>

                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>

                            </button>

                        </div>


                        <div class="mainmenu pull-left">

                            <ul class="nav navbar-nav collapse navbar-collapse">

                                <li>
                                    <a href="index.php">
                                        Home
                                    </a>
                                </li>

                                <li>
                                    <a href="my-products.php">
                                        My Products
                                    </a>
                                </li>

                                <li>
                                    <a href="addproducts.php">
                                        Add Product
                                    </a>
                                </li>

                            </ul>

                        </div>

                    </div>


                    <div class="col-sm-3">

                        <div class="search_box pull-right">

                            <input type="text" placeholder="Search">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </header>



    <section id="cart_items">

        <div class="container">

            <div class="breadcrumbs">

                <ol class="breadcrumb">

                    <li>
                        <a href="index.php">
                            Home
                        </a>
                    </li>

                    <li class="active">
                        Shopping Cart
                    </li>

                </ol>

            </div>


            <?php if (empty($products_in_cart)): ?>

                <div class="alert alert-warning text-center" style="margin: 40px 0;">

                    <h3>
                        Giỏ hàng của bạn đang trống!
                    </h3>

                    <a href="index.php" class="btn btn-default check_out" style="margin-top: 15px;">

                        Tiếp tục mua sắm

                    </a>

                </div>


            <?php else: ?>


                <div class="table-responsive cart_info">

                    <table class="table table-condensed">

                        <thead>

                            <tr class="cart_menu">

                                <td class="image">
                                    Item
                                </td>

                                <td class="description">
                                    Title
                                </td>

                                <td class="price">
                                    Price
                                </td>

                                <td class="quantity">
                                    Quantity
                                </td>

                                <td class="total">
                                    Total
                                </td>

                                <td>
                                    Action
                                </td>

                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach ($products_in_cart as $product): ?>

                                <?php
                                $qty = $cart[$product['id']];
                                $total = $product['price'] * $qty;
                                $grand_total += $total;
                                ?>

                                <tr id="product-row-<?= $product['id'] ?>" data-id="<?= $product['id'] ?>"
                                    data-price="<?= $product['price'] ?>">  

                                    <td class="cart_product">

                                        <a href="javascript:void(0)">

                                            <img src="<?= htmlspecialchars($product['image']) ?>" alt="" width="80">

                                        </a>

                                    </td>


                                    <td class="cart_description">

                                        <h4>

                                            <a href="javascript:void(0)">

                                                <?= htmlspecialchars($product['title']) ?>

                                            </a>

                                        </h4>

                                        <p>
                                            Web ID: <?= $product['id'] ?>
                                        </p>

                                    </td>


                                    <td class="cart_price">

                                        <p class="product_price">

                                            <?= number_format(
                                                $product['price'],
                                                0,
                                                ',',
                                                '.'
                                            ) ?>
                                            đ
                                        </p>

                                    </td>


                                    <td class="cart_quantity">

                                        <div class="cart_quantity_button">


                                            <!-- NÚT + -->

                                            <a class="cart_quantity_up" href="javascript:void(0)">
                                                +
                                            </a>


                                            <!-- QTY -->

                                            <input class="cart_quantity_input" type="text" name="quantity" value="<?= $qty ?>"
                                                autocomplete="off" size="2" readonly>


                                            <!-- NÚT - -->

                                            <a class="cart_quantity_down" href="javascript:void(0)">
                                                -
                                            </a>


                                        </div>

                                    </td>


                                    <td class="cart_total">

                                        <p class="cart_total_price">

                                            <?= number_format($total, 0, ',', '.') ?>

                                            đ

                                        </p>

                                    </td>


                                    <td class="cart_delete">

                                        <a class="cart_quantity_delete" href="javascript:void(0)">

                                            <i class="fa fa-times"></i>

                                        </a>

                                    </td>


                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>


            <?php endif; ?>

        </div>

    </section>



    <?php if (!empty($products_in_cart)): ?>


        <section id="do_action">

            <div class="container">

                <div class="row">

                    <div class="col-sm-6"></div>


                    <div class="col-sm-6">

                        <div class="total_area">

                            <ul>

                                <li>
                                    Cart Sub Total
                                    <span id="cart_sub_total">
                                        <?= number_format($grand_total, 0, ',', '.') ?> đ
                                    </span>
                                </li>

                                <li>

                                    Eco Tax

                                    <span>
                                        0 đ
                                    </span>

                                </li>


                                <li>

                                    Shipping Cost

                                    <span>
                                        Free
                                    </span>

                                </li>


                                <li>

                                    Total

                                    <span id="cart_grand_total">

                                        <?= number_format(
                                            $grand_total,
                                            0,
                                            ',',
                                            '.'
                                        ) ?>

                                        đ

                                    </span>

                                </li>

                            </ul>


                            <a class="btn btn-default update" href="javascript:void(0)">

                                Update

                            </a>


                            <a class="btn btn-default check_out" href="javascript:void(0)">

                                Check Out

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </section>


    <?php endif; ?>



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



    <script src="../js/jquery.js"></script>

    <script src="../js/bootstrap.min.js"></script>

    <script src="../js/Cart.js"></script>


</body>

</html>