<?php
include 'connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password)) {
        echo "Semua field harus diisi.";
        exit;
    }

    if ($password !== $confirm_password) {
        echo "Kata sandi tidak cocok. <a href='signup.html'>Kembali</a>";
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $check = $koneksi->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "Email sudah terdaftar. <a href='login.html'>Login di sini</a>";
        exit;
    }

    $stmt = $koneksi->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>
                alert('Pendaftaran berhasil! Silakan login.');
                window.location.href = 'login.html';
              </script>";
    } else {
        echo "Terjadi kesalahan: " . $stmt->error;
    }

    $stmt->close();
    $check->close();
    $koneksi->close();
}
?>
