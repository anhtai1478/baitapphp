<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$id_user = $_SESSION['user_id'];

$sql = "SELECT * FROM product WHERE id_user = ?";

$tmt = $conn->prepare($sql);

$tmt->bind_param("i", $id_user);

$tmt->execute();

$result = $tmt->get_result();

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
                <div class="col-sm-3">
                    <div class="left-sidebar">
                        <h2>Account</h2>
                        <div class="panel-group category-products" id="accordian"><!--category-productsr-->


                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title"><a href="account.php">account</a></h4>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title"><a href="my-products.php">My product</a></h4>
                                </div>
                            </div>


                        </div><!--/category-products-->


                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="table-responsive cart_info">
                        <table class="table table-condensed">
                            <thead>
                                <tr class="cart_menu">
                                    <td class="image">image</td>
                                    <td class="description">name</td>
                                    <td class="price">price</td>

                                    <td class="total">action</td>

                                </tr>
                            </thead>
                            <tbody>

                                <?php if ($result->num_rows == 0): ?>

                                    <tr>
                                        <td colspan="4" style="text-align: center;">
                                            Bạn chưa có sản phẩm nào
                                        </td>
                                    </tr>

                                <?php else: ?>

                                    <?php while ($product = $result->fetch_assoc()): ?>

                                        <tr>

                                            <!-- IMAGE -->
                                            <td class="cart_product">

                                                <a href="#">

                                                    <!-- htmlspecialchars: chuyển các ký tự đặc biệt thành dạng an toàn khi đưa dữ liệu ra HTML -->
                                                    <img src="../uploads/<?= htmlspecialchars($product['image']) ?>"
                                                        alt="<?= htmlspecialchars($product['title']) ?>" width="75">
                                                </a>

                                            </td>


                                            <!-- TITLE -->
                                            <td class="cart_description">

                                                <h4>
                                                    <?= htmlspecialchars($product['title']) ?>
                                                </h4>

                                            </td>


                                            <!-- PRICE -->
                                            <td class="cart_price">

                                                <p>
                                                    <?= number_format($product['price']) ?> VNĐ
                                                </p>

                                            </td>


                                            <!-- ACTION -->
                                            <td class="cart_total">

                                                <a href="edit-product.php?id=<?= $product['id'] ?>">
                                                    Edit
                                                </a>

                                                &nbsp;

                                                <a href="delete-product.php?id=<?= $product['id'] ?>"
                                                    onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này không?')">
                                                    Delete
                                                </a>

                                            </td>

                                        </tr>

                                    <?php endwhile; ?>

                                <?php endif; ?>
                            </tbody>
                        </table>
                        <button class="btn btn-primary">
                            <a href="addproducts.php" style="color: white;">Add product</a>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>