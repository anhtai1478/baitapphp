<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
// kiểm tra user có hay không 
$id = $_SESSION['user_id'];

$sql = "SELECT * FROM user WHERE id = ?";
// Chuẩn bị câu lệnh SQL
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Lỗi SQL: " . $conn->error);
}
// Kiểm tra lỗi chuẩn bị câu lệnh
$stmt->bind_param("i", $id);
$stmt->execute();
// Lấy kết quả
$result = $stmt->get_result();

$user = $result->fetch_assoc();

if (!$user) {
    die("Không tìm thấy user có ID: " . $id);
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
        <div class="header_top"><!--header_top-->
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
        </div><!--/header_top-->

        <div class="header-middle"><!--header-middle-->
            <div class="container">
                <div class="row">
                    <div class="col-md-4 clearfix">
                        <div class="logo pull-left">
                            <a href="index.html"><img src="../images/home/logo.png"></a>
                        </div>
                        <div class="btn-group pull-right clearfix">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle usa"
                                    data-toggle="dropdown">
                                    USA
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="">Canada</a></li>
                                    <li><a href="">UK</a></li>
                                </ul>
                            </div>

                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle usa"
                                    data-toggle="dropdown">
                                    DOLLAR
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="">Canadian Dollar</a></li>
                                    <li><a href="">Pound</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 clearfix">
                        <div class="shop-menu clearfix pull-right">
                            <ul class="nav navbar-nav">
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <li>
                                        <a href="../php/account.php">
                                            <i class="fa fa-user"></i> Account
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li>
                                    <a href="">
                                        <i class="fa fa-star"></i> Wishlist
                                    </a>
                                </li>

                                <li>
                                    <a href="checkout.html">
                                        <i class="fa fa-crosshairs"></i> Checkout
                                    </a>
                                </li>

                                <li>
                                    <a href="cart.html">
                                        <i class="fa fa-shopping-cart"></i> Cart
                                    </a>
                                </li>

                                <?php if (isset($_SESSION['user_id'])): ?>

                                    <!-- Đã đăng nhập -->
                                    <li>
                                        <a href="../php/logout.php">
                                            <i class="fa fa-lock"></i> Logout
                                        </a>
                                    </li>

                                <?php else: ?>

                                    <!-- Chưa đăng nhập -->
                                    <li>
                                        <a href="../php/register.php">
                                            <i class="fa fa-user"></i> Register

                                    </li>

                                    <li>
                                        <a href="../php/login.php">
                                            <i class="fa fa-lock"></i> Login
                                        </a>
                                    </li>


                                <?php endif; ?>

                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div><!--/header-middle-->

        <div class="header-bottom"><!--header-bottom-->
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
                                <li><a href="index.html" class="active">Home</a></li>
                                <li class="dropdown"><a href="#">Shop<i class="fa fa-angle-down"></i></a>
                                    <ul role="menu" class="sub-menu">
                                        <li><a href="shop.html">Products</a></li>
                                        <li><a href="product-details.html">Product Details</a></li>
                                        <li><a href="checkout.html">Checkout</a></li>
                                        <li><a href="cart.html">Cart</a></li>
                                        <li><a href="../php/login.php">Login</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown"><a href="#">Blog<i class="fa fa-angle-down"></i></a>
                                    <ul role="menu" class="sub-menu">
                                        <li><a href="blog.html">Blog List</a></li>
                                        <li><a href="blog-detail.html">Blog Single</a></li>
                                    </ul>
                                </li>
                                <li><a href="404.html">404</a></li>
                                <li><a href="contact-us.html">Contact</a></li>
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
        </div><!--/header-bottom-->
    </header>
    <section>
        <div class="container">
            <div class="row">

                <!-- Sidebar -->
                <div class="col-sm-3">
                    <div class="left-sidebar">

                        <h2>Account</h2>

                        <div class="panel-group category-products" id="accordian">

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="#">Account</a>
                                    </h4>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="#">My product</a>
                                    </h4>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>


                <!-- Account -->
                <div class="col-sm-9">

                    <div class="blog-post-area">

                        <h2 class="title text-center">
                            Account Information
                        </h2>


                        <!-- Thông tin Account -->
                        <div class="signup-form">

                            <h2>Account Information</h2>

                            <p>
                                <strong>ID:</strong>

                                <!-- htmlspecialchars: chuyển đổi các ký tự đặc biệt thành các entity HTML -->
                                <?php echo htmlspecialchars($user['id']); ?>
                            </p>

                            <p>
                                <strong>Name:</strong>
                                <?php echo htmlspecialchars($user['name']); ?>
                            </p>

                            <p>
                                <strong>Email:</strong>
                                <?php echo htmlspecialchars($user['email']); ?>
                            </p>


                            <!-- Avatar -->
                            <?php if (!empty($user['avatar'])): ?>

                                <div style="margin: 20px 0;">

                                    <p>
                                        <strong>Avatar:</strong>
                                    </p>

                                    <img src="../uploads/<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar"
                                        style="
                                        width: 120px;
                                        height: 120px;
                                        border-radius: 50%;
                                    ">

                                </div>

                            <?php endif; ?>

                        </div>


                        <!-- Update User -->
                        <h2 class="title text-center">
                            Update User
                        </h2>

                        <div class="signup-form">

                            <h2>Update Information</h2>

                            <form action="update_user.php" method="POST" enctype="multipart/form-data">

                                <!-- Name -->
                                <input type="text" name="name" placeholder="Name"
                                    value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" />


                                <!-- Email -->
                                <input type="email" name="email" placeholder="Email Address"
                                    value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" />


                                <!-- Password -->
                                <input type="password" name="password" placeholder="Password" />


                                <!-- Avatar -->
                                <input type="file" name="avatar" accept="image/*" />


                                <button type="submit" class="btn btn-default">
                                    Update
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