<!DOCTYPE html>
<html>
<head>
    <title>登陆</title>
<style>
    body {
        background-color: #f7f7f7;
        font-family: Arial, sans-serif;
    }
    form {
        width: 400px;
        margin: 0 auto;
        background-color: #eee;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    }
    input[type=text], input[type=password] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    input[type=email], input[type=password] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    input[type=submit] {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    input[type=submit]:hover {
        background-color: #45a049;
    }
    p {
        text-align: center;
        color: #777;
    }
    h2 {
        text-align: center;
        color: #333;
        margin-top: 50px;
    }
    a {
        color: #4CAF50;
    }
</style>
</head>
<body>
    <h2>登陆</h2>
    <?php
    session_start();
    $msg = '';
    if(isset($_POST['login'])) {
        $con = mysqli_connect('localhost', 'myDB', 'T8fs4ftyXaGNY3py', 'mydb');
        if(mysqli_connect_errno()) {
            $msg = "无法连接到 MySQL: " . mysqli_connect_error();
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $query = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($con, $query);

        if(mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            if(password_verify($password, $row['password'])) {
                $_SESSION['username'] = $row['username'];
                header("Location: welcome.php");
            } else {
                $msg = "无效的密码。请再试一次。";
            }
        } else {
            $msg = "无效的邮箱或密码。请再试一次。";
        }
        mysqli_close($con);
    }
    ?>
    <p><?php echo $msg; ?></p>
    <form action="" method="post">
        <label>邮箱:</label>
        <input type="email" name="email" required><br><br>
        <label>密码:</label>
        <input type="password" name="password" required><br><br>
        <input type="submit" name="login" value="登录">
    </form>
    <br>
    <p>还没有帐户吗？ <a href="register.php">在这里注册。</a></p>
</body>
</html>

