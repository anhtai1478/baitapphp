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
                    <div class="col-md-4 clearfix">
                        <div class="logo pull-left">
                            <a href="index.php"><img src="../images/home/logo.png" alt="" /></a>
                        </div>
                    </div>
                    <div class="col-md-8 clearfix">
                        <div class="shop-menu clearfix pull-right">
                            <ul class="nav navbar-nav">
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <li><a href="account.php"><i class="fa fa-user"></i> Account</a></li>
                                <?php endif; ?>
                                <li><a href="#"><i class="fa fa-star"></i> Wishlist</a></li>
                                <li><a href="#"><i class="fa fa-crosshairs"></i> Checkout</a></li>
                                <li><a href="cart.php"><i class="fa fa-shopping-cart"></i> Cart</a></li>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <li><a href="logout.php"><i class="fa fa-lock"></i> Logout</a></li>
                                <?php else: ?>
                                    <li><a href="register.php"><i class="fa fa-user"></i> Register</a></li>
                                    <li><a href="login.php"><i class="fa fa-lock"></i> Login</a></li>
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
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                        <div class="mainmenu pull-left">
                            <ul class="nav navbar-nav collapse navbar-collapse">
                                <li><a href="index.php" class="active">Home</a></li>
                                <li><a href="my-products.php">My Products</a></li>
                                <li><a href="addproducts.php">Add Product</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="search_box pull-right">
                            <input type="text" placeholder="Search" />
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
                    <li><a href="index.php">Home</a></li>
                    <li class="active">Shopping Cart</li>
                </ol>
            </div>

            <?php if (empty($products_in_cart)): ?>
                <div class="alert alert-warning text-center" style="margin: 40px 0;">
                    <h3>Giỏ hàng của bạn đang trống!</h3>
                    <a href="index.php" class="btn btn-default check_out" style="margin-top: 15px;">Tiếp tục mua sắm</a>
                </div>
            <?php else: ?>
                <div class="table-responsive cart_info">
                    <table class="table table-condensed">
                        <thead>
                            <tr class="cart_menu">
                                <td class="image">Item</td>
                                <td class="description">Title</td>
                                <td class="price">Price</td>
                                <td class="quantity">Quantity</td>
                                <td class="total">Total</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products_in_cart as $product): 
                                $qty = $cart[$product['id']];
                                $total = $product['price'] * $qty;
                                $grand_total += $total;
                            ?>
                                <tr id="product-row-<?= $product['id'] ?>" data-id="<?= $product['id'] ?>">
                                    <td class="cart_product">
                                        <a href=""><img src="<?= htmlspecialchars($product['image']) ?>" alt="" width="80"></a>
                                    </td>
                                    <td class="cart_description">
                                        <h4><a href=""><?= htmlspecialchars($product['title']) ?></a></h4>
                                        <p>Web ID: <?= $product['id'] ?></p>
                                    </td>
                                    <td class="cart_price">
                                        <p><?= number_format($product['price'], 0, ',', '.') ?> đ</p>
                                    </td>
                                    <td class="cart_quantity">
                                        <div class="cart_quantity_button">
                                            <a class="cart_quantity_up" href="#"> + </a>
                                            <input class="cart_quantity_input" type="text" name="quantity" value="<?= $qty ?>" autocomplete="off" size="2" readonly>
                                            <a class="cart_quantity_down" href="#"> - </a>
                                        </div>
                                    </td>
                                    <td class="cart_total">
                                        <p class="cart_total_price"><?= number_format($total, 0, ',', '.') ?> đ</p>
                                    </td>
                                    <td class="cart_delete">
                                        <a class="cart_quantity_delete" href="#"><i class="fa fa-times"></i></a>
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
                            <li>Cart Sub Total <span><?= number_format($grand_total, 0, ',', '.') ?> đ</span></li>
                            <li>Eco Tax <span>0 đ</span></li>
                            <li>Shipping Cost <span>Free</span></li>
                            <li>Total <span><?= number_format($grand_total, 0, ',', '.') ?> đ</span></li>
                        </ul>
                        <a class="btn btn-default update" href="cart.php">Update</a>
                        <a class="btn btn-default check_out" href="#">Check Out</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <footer id="footer">
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <p class="pull-left">Copyright © 2026 E-SHOPPER Inc. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/Cart.js"></script>
</body>
</html>