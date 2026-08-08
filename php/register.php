<?php

include 'connect.php'; // Kết nối CSDL

$error = [
    'email' => '',
    'password' => '',
    'name' => '',
    'avatar' => '',
];

$email = $password = $name = "";
//$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"] ?? '');
    $name = trim($_POST["name"] ?? '');
    $password = trim($_POST["password"] ?? '');

    if (empty($email)) {
        $error['email'] = "vui lòng nhập email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = "Định dạng email không hợp lệ";
    }

    if (empty($name)) {
        $error['name'] = "Tên là bắt buộc";
    }

    if (empty($password)) {
        $error['password'] = "Mật khẩu là bắt buộc";
    }

    if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] == UPLOAD_ERR_NO_FILE) {
        $error['avatar'] = "Vui lòng chọn ảnh đại diện";
    } else {

        // Xử lý file ảnh đại diện
        $file = $_FILES['avatar'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];

        // Lấy đuôi file
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if ($fileError !== 0) {
            $error['avatar'] = "Có lỗi xảy ra khi tải file!";
        } elseif (!in_array($fileExt, $allowedExts)) {
            $error['avatar'] = "Chỉ chấp nhận file hình ảnh (jpg, jpeg, png, gif, webp)!";
        } elseif ($fileSize > 1024 * 1024) { // < 1MB
            $error['avatar'] = "Dung lượng ảnh phải nhỏ hơn 1MB!";
        }
    }

    if (!array_filter($error)) {
        // Yêu cầu: avatar lấy tên file insert vào
        $uploadDir = '../uploads/';
        $targetfilepath = $uploadDir . $fileName;

        // Kiểm tra nếu thư mục uploads chưa tồn tại thì tạo mới
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($fileTmpName, $targetfilepath)) {
            $passwordHard = md5($password);

            $stmt = $conn->prepare("INSERT INTO user (email, password, name, avatar) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $email, $passwordHard, $name, $fileName);

            if ($stmt->execute()) {
                //$success_msg = "Đăng ký thành công!";
                $email = $password = $name = "";
                header("Location: login.php");

            } else {
                $error['email'] = "Email này đã tồn tại hoặc có lỗi CSDL!";
            }
            $stmt->close();
        } else {
            $error['avatar'] = "Có lỗi xảy ra khi tải file lên server!";
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
    <div class="col-sm-4">
        <div class="signup-form"><!--sign up form-->
            <h2>New User Signup!</h2>

            <?php if (!empty($success_msg)): ?>
                <p style="color: green;"><?php echo $success_msg; ?></p>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="text" id="name" name="name" placeholder="Name"
                    value="<?php echo htmlspecialchars($name); ?>" />
                <?php if (!empty($error['name'])): ?>
                    <span><?php echo $error['name']; ?></span>
                <?php endif; ?>

                <input type="email" id="email" name="email" placeholder="Email Address"
                    value="<?php echo htmlspecialchars($email); ?>" />
                <?php if (!empty($error['email'])): ?>
                    <span><?php echo $error['email']; ?></span>
                <?php endif; ?>

                <input type="password" id="password" name="password" placeholder="Password" />
                <?php if (!empty($error['password'])): ?>
                    <span><?php echo $error['password']; ?></span>
                <?php endif; ?>

                <input type="file" id="avatar" name="avatar" style="margin-top: 10px;" />
                <?php if (!empty($error['avatar'])): ?>
                    <span><?php echo $error['avatar']; ?></span>
                <?php endif; ?>

                <button type="submit" class="btn btn-default" style="margin-top: 10px;">Signup</button>
            </form>
        </div>
    </div>
</body>




</html>