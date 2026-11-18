<?php
session_start();

if (isset($_SESSION['id_user'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIAKAD</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">

<div class="login-container">

    <div class="login-welcome">
        <div class="logo">
            <img src="" alt="logo">
        </div>
        <h1>UNIVERSITAS INFORMATION TECHNOLOGY</h1>
        <p>Sistem Informasi Akademik SIAKAD Mahasiswa Universitas Information Technology</p>
    </div>

    <div class="login-form">
        <h2>Login</h2>
        <p>Silakan masuk menggunakan akun SIAKAD Anda</p>

        <!-- FORM LOGIN -->
        <form action="login.php" method="POST">

        <!-- USERNAME -->
        <input type="text" name="Username" placeholder="masukkan username" required>

        <!-- PASSWORD -->
       <input type="password" name="Password" placeholder="masukkan password" required>

       <!-- TOMBOL LOGIN -->
        <button type="submit">Login</button>
        <p>&copy; 2026 UNITECH | <strong>Bagian Sistem Informasi</strong></p>
        </form>
    </div>

</div>

</body>
</html>