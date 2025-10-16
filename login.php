<?php
session_start();
include 'connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $koneksi->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            echo "<script>
                    alert('Login berhasil! Selamat datang, {$user['name']}');
                    window.location.href = 'index.html';
                  </script>";
        } else {
            echo "<script>alert('Kata sandi salah!'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('Email tidak ditemukan!'); window.location.href='login.html';</script>";
    }

    $stmt->close();
    $koneksi->close();
}
?>
