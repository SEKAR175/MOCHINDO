<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $koneksi->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = $_POST['amount'];
    $method = $_POST['method'];

    $insert = $koneksi->prepare("INSERT INTO payments (user_id, name, email, amount, method) VALUES (?, ?, ?, ?, ?)");
    $insert->bind_param("issds", $user_id, $user_data['name'], $user_data['email'], $amount, $method);

    if ($insert->execute()) {
        echo "<script>
                alert('Pembayaran telah dikonfirmasi!');
                window.location.href = 'payment.php';
              </script>";
    } else {
        echo "<script>
                alert('Terjadi kesalahan saat menyimpan data pembayaran: " . $insert->error . "');
                window.location.href = 'payment.php';
              </script>";
    }
    $insert->close();
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mochindo - Pembayaran</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="main-header">
        <div class="header-content">
            <div class="logo">MOCHINDO</div>
            <nav class="main-nav">
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="menu.html">Menu</a></li>
                    <li><a href="cart.html">Keranjang</a></li>
                    <li><a href="login.html">Login</a></li>
                </ul>
            </nav>
            <div class="contact-info">
                <span>0859 5413 8582</span>
                <button class="small-button">Order</button>
            </div>
        </div>
    </header>

    <main class="payment-page">
        <div class="form-card">
            <h2>Konfirmasi Pembayaran</h2>
            <form action="payment.php" method="POST">
                <div class="payment-section">
                    <h3>Data Pelanggan</h3>
                    <div class="info-group">
                        <label>Nama</label>
                        <input type="text" value="<?php echo htmlspecialchars($user_data['name']); ?>" disabled>
                    </div>
                    <div class="info-group">
                        <label>Email</label>
                        <input type="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" disabled>
                    </div>
                </div>

                <div class="payment-section">
                    <h3>Detail Pembayaran</h3>
                    <div class="info-group">
                        <label>Nominal Pembayaran (Rp)</label>
                        <input type="number" name="amount" id="nominal-bayar" placeholder="Masukkan jumlah pembayaran" required>
                    </div>
                    <div class="info-group">
                        <label>Metode Pembayaran</label>
                        <select name="method">
                            <option value="transfer">Transfer Bank</option>
                            <option value="ewallet">E-Wallet</option>
                            <option value="cod">COD (Cash On Delivery)</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="button primary-button">Konfirmasi</button>
            </form>
        </div>
    </main>

    <footer class="main-footer">
        <p>&copy; 2025 Mochindo. All Rights Reserved.</p>
    </footer>
</body>
</html>