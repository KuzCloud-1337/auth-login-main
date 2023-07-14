<!DOCTYPE html>
<html>
<head>
    <title>电子邮件验证</title>
    <style>
        body {
            background-color: #f7f7f7;
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-top: 50px;
        }
        p {
            text-align: center;
            color: #777;
        }
        a {
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <?php 
   $email = $_GET['email'];
   $code = $_GET['code'];

   $con = mysqli_connect('localhost', 'myDB_test', 'EnsBYAap53LnWAsR', 'mydb_test');

   if(mysqli_connect_errno()) { 
      echo "无法连接SQL: " . mysqli_connect_error(); 
      exit();
   } 

   $query = "SELECT * FROM users WHERE email='$email' AND verification_code='$code'";
   $result = mysqli_query($con, $query);

   if(mysqli_num_rows($result) == 1) {
      $update_query = "UPDATE users SET verified='1' WHERE email='$email' AND verification_code='$code' AND verified='0'";
      mysqli_query($con, $update_query);

      echo "您的邮箱已验证成功。";
   } else {
      echo "验证链接无效或已过期。请重新注册并获取新的验证链接。";
   }

   mysqli_close($con);
?>
    <p>返回 <a href="login.php">登录页面</a></p>
</body>
</html>