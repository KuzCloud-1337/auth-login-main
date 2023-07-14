<?php
session_start();
if(!isset($_SESSION['username'])) {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>欢迎页面</title>
<style>
    body {
        background-color: #f7f7f7;
        font-family: Arial, sans-serif;
    }
    .box {
        width: 400px;
        margin: 0 auto;
        background-color: #eee;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        text-align: center;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    h2 {
        color: #333;
        margin: 0 0 20px;
    }
    p {
        color: #777;
    }
    a {
        color: #4CAF50;
    }
</style>
</head>
<body>
    <div class="box">
        <h2>欢迎 <?php echo $_SESSION['username']; ?></h2>
        <br>
        <p><a href="logout.php">点我注销</a></p>
    </div>
</body>
</html>