<?php
include "db.php"; // الاتصال بقاعدة البيانات
ob_start();
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // البحث عن المستخدم في قاعدة البيانات
    $sql = "SELECT id, username, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        // تسجيل الدخول بنجاح
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;

        // إعادة التوجيه إلى index.html
        header("Location: index.html");
        exit();
    } else {
        $error = "❌ البريد الإلكتروني أو كلمة المرور غير صحيحة!";
    }

    $stmt->close();
}
?> -

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>تسجيل الدخول - متجر الرياضة</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="index.css" />
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <img src="images/logo3.jpg" alt="صورة رياضية" />
        </div>
        <div class="right-panel">
            <div class="main">
                <h2>تسجيل الدخول</h2>
                <p>مرحبًا بك في متجر المعدات الرياضية</p>

                <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

                <form method="POST">
                    <input type="email" name="email" placeholder="البريد الإلكتروني" required />
                    <input type="password" name="password" placeholder="كلمة المرور" required />
                    <button type="submit">تسجيل الدخول</button>

                    <p class="or-text">أو تابع عبر:</p>

                    <div class="social-login">
                        <button class="google">Google</button>
                        <button class="facebook">Facebook</button>
                        <button class="instagram">Instagram</button>
                    </div>

                    <p class="register-text">لا تملك حساب؟ <a href="register.php">إنشاء حساب</a></p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
