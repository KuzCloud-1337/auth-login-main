<?php
$msg = '';

if (isset($_POST['reset_password'])) {
    $con = mysqli_connect('localhost', 'myDB_test', 'EnsBYAap53LnWAsR', 'mydb_test');
    if (mysqli_connect_errno()) {
        $msg = "无法连接SQL: " . mysqli_connect_error();
    }

    $email = mysqli_real_escape_string($con, $_POST['email']);

    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^w]@', $password);

    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        $msg = "密码不符合复杂性要求，请确保密码至少包含一个数字、一个小写字母、一个大写字母，一个特殊字符，长度至少为8个字符。";
    } else {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($con, "SELECT * FROM users WHERE email=?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
            $verification_code = substr(md5(uniqid(rand(), true)), 0, 8);
            $to = $email;
            $google_email = "shuhaoys@gmail.com";
            $google_password = "dskyggnplykwixgt";
            $subject = "=?UTF-8?B?" . base64_encode("重置密码"). "?=";
            $message = "请单击以下链接以重置您的密码: http://8.222.147.252/reset_password.php?email=$email&code=$verification_code";
            $from = "shuhaoys@gmail.com";
            $headers = "From: $fromrn";
            $headers .= "Reply-To: $fromrn";
            $headers .= "MIME-Version: 1.0rn";
            $headers .= "Content-Type: text/html; charset=UTF-8rn";

            require_once 'PHPMailer/PHPMailerAutoload.php';
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            $mail->SMTPSecure = 'tls';
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->SMTPAuth = true;
            $mail->Username = $google_email;
            $mail->Password = $google_password;
            $mail->setFrom($from, '密码重置');
            $mail->addAddress($to);
            $mail->Subject = $subject;
            $mail->Body = $message;
            if (!$mail->send()) {
                $msg = "无法发送重置密码邮件，请检查您的电子邮件是否正确。";
            } else {
                $stmt = mysqli_prepare($con, "UPDATE users SET verification_code=? WHERE email=?");
                mysqli_stmt_bind_param($stmt, "ss", $verification_code, $email);
                mysqli_stmt_execute($stmt);
                $msg = "我们已向您的电子邮件发送了密码重置链接，请检查您的收件箱或垃圾邮件箱。";
            }
        } else {
            $msg = "该邮箱不存在于我们的系统中。";
        }
        mysqli_close($con);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>密码重置</title>
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

        input[type=text],
        input[type=password],
        input[type=email] {
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

        h1 {
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

<h1>密码重置</h1>

<?php
if (!empty($msg)) {
    echo '<p>' . $msg . '</p>';
}
?>

<form method="post" action="">
    <label for="email">电子邮件</label>
    <input type="email" name="email" id="email" required>
    <br>
    <label for="password">新密码</label>
    <input type="password" name="password" id="password" required>
    <br>
    <input type="submit" name="reset_password" value="重置密码">
</form>

</body>
</html>
