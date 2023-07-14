<!DOCTYPE html> 
<html> 
<head> 
    <title>注册</title> 
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
        input[type=text], input[type=password], input[type=email] { 
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
    <h2>注册</h2> 
    <?php
    $msg = '';
    if (isset($_POST['register'])) {
        $con = mysqli_connect('localhost', 'myDB_test', 'EnsBYAap53LnWAsR', 'mydb_test');
        if (mysqli_connect_errno()) {
            $msg = "无法连接SQL: " . mysqli_connect_error();
        }

        $username = mysqli_real_escape_string($con, $_POST['username']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $ip = mysqli_real_escape_string($con, $_SERVER['REMOTE_ADDR']);

        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^w]@', $password);

        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            $msg = "密码不符合复杂性要求，请确保密码至少包含一个数字、一个小写字母、一个大写字母，一个特殊字符，长度至少为8个字符。";

        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = mysqli_prepare($con, "SELECT * FROM users WHERE username=? OR email=?");
            mysqli_stmt_bind_param($stmt, "ss", $username, $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 0) {
                $verification_code = substr(md5(uniqid(rand(), true)), 0, 8);
                $to = $email;
                $google_email = "";
                $google_password = "";
                $subject = "=?UTF-8?B?" . base64_encode("电子邮件验证"). "?=";
                $message = "请单击以下链接以验证您的电子邮件: http://X.X.X.X/verify.php?email=$email&code=$verification_code";
                $from = "";
                $headers = "From: $fromrn";
                $headers .= "Reply-To: $fromrn";
                $headers .= "MIME-Version: 1.0rn";
                $headers .= "Content-Type: text/html; charset=UTF-8rn";


                require_once 'PHPMailer/PHPMailerAutoload.php';
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->SMTPDebug = 0;
                $mail->SMTPSecure = 'tls';
                $mail->Host = 'smtp.xxx.com';
                $mail->Port = XXX;
                $mail->SMTPAuth = true;
                $mail->Username = $google_email;
                $mail->Password = $google_password;
                $mail->setFrom($from, '电子邮件验证');
                $mail->addAddress($to);
                $mail->Subject = $subject;
                $mail->Body = $message;
                if (!$mail->send()) {
                    $msg = "无法发送验证邮件，请检查您的电子邮件是否正确。";
                } else {
                    $stmt = mysqli_prepare($con, "INSERT INTO users (username, password, email, ip_address, verification_code) VALUES (?, ?, ?, ?, ?)");
                    mysqli_stmt_bind_param($stmt, "sssss", $username, $password, $email, $ip, $verification_code);
                    mysqli_stmt_execute($stmt);
                    $msg = "我们已向您的电子邮件发送了验证链接，请检查您的收件箱或垃圾邮件箱。";
                }
            } else {
                $msg = "此用户名或邮箱已存在。请尝试另一个。";
            }
            mysqli_close($con);
        }
    }
    ?>
    <p><?php echo $msg; ?></p> 
    <form action="" method="post"> 
        <label>用户名:</label> 
        <input type="text" name="username" required><br><br> 
        <label>密码:</label> 
        <input type="password" name="password" required><br><br> 
        <label>邮箱:</label>

<input type="email" name="email" required><br><br> 
        <input type="submit" name="register" value="注册"> 
    </form> 
    <br> 
    <p>已经有一个帐户？ <a href="login.php">在此登录。</a></p> 
</body> 
</html>
