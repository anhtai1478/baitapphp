<?php
session_start();

// xóa dữ liệu
session_unset();
// xóa session
session_destroy();

header("Location: login.php");
exit();
?>