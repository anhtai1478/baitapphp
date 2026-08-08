<?php
session_start();
include 'connect.php'; // Kết nối CSDL

$error = [
    'email' => '',
    'password' => '',
];


$email = $password = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"] ?? '');
    $password = trim($_POST["password"] ?? '');


    if (empty($email)) {

        $error['email'] = "Vui lòng nhập email";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = "Định dạng email không hợp lệ";
    }

    if (empty($password)) {
        $error['password'] = "Mật khẩu là bắt buộc";
    }

    if (empty($error['email']) && empty($error['password'])) {
        // Xử lý đăng nhập

        $password_hash = md5($password); // Mã hóa mật khẩu bằng MD5


        $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
        //Chuẩn bị
        $stmt = $conn->prepare($sql);
        //gán pasword, email vào tham số
        $stmt->bind_param("ss", $email, $password_hash);
        // chạy lệnh
        $stmt->execute();
        // Lấy kết quả
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            // Lấy thông tin người dùng từ database
            $user = $result->fetch_assoc();

            // Lưu thông tin người dùng vào database 

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_avatar'] = $user['avatar'];

            $_SESSION['success_msg'] = "Đăng nhập thành công!";

            header("Location: ../index.php");
            exit();
        } else {
            $error['password'] = "Email hoặc mật khẩu không đúng";
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
    <div class="login-form">
        <h2>Login to your account</h2>
        <form action="#">
            <input type="email" placeholder="Email Address" />
            <?php if (!empty($error['email'])): ?>
                <span><?php echo $error['email']; ?></span>
            <?php endif; ?>
            <input type="password" placeholder="Password" />
            <?php if (!empty($error['password'])): ?>
                <span><?php echo $error['password']; ?></span>
            <?php endif; ?>
            <span>
                <input type="checkbox" class="checkbox">
                Keep me signed in
            </span>
            <button type="submit" class="btn btn-default">Login</button>
        </form>
    </div>
    </div>
</body>

</html>