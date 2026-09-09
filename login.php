<?php
session_start();

/* ===============================
   REDIRECT JIKA SUDAH LOGIN
================================ */
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

/* ===============================
   DATA USER (STATIC)
================================ */
$users = [
    [
        "username" => "linda",
        "password" => "datapasienmcursbb"
    ]
];

$error = '';
$username = '';

/* ===============================
   PROSES LOGIN
================================ */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    foreach ($users as $user) {
        if ($user['username'] === $username && $user['password'] === $password) {
            $_SESSION['user'] = $username;
            header("Location: index.php");
            exit;
        }
    }

    $error = "Username atau Password salah";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login Sistem MCU</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #2c3e50, #3498db);
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

.login-card {
    background: #ffffff;
    width: 380px;
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.25);
    text-align: center;
}

.logo {
    width: 80px;
    margin-bottom: 15px;
}

h2 {
    margin: 10px 0 5px 0;
    color: #2c3e50;
}

.subtitle {
    font-size: 14px;
    color: #7f8c8d;
    margin-bottom: 25px;
}

.form-group {
    text-align: left;
    margin-bottom: 18px;
}

label {
    font-size: 13px;
    color: #34495e;
    display: block;
    margin-bottom: 5px;
}

input {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #dcdde1;
    font-size: 14px;
    transition: 0.2s;
}

input:focus {
    border-color: #3498db;
    outline: none;
}

button {
    width: 100%;
    padding: 12px;
    background: #3498db;
    border: none;
    border-radius: 6px;
    color: white;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
}

button:hover {
    background: #2980b9;
}

.error {
    background: #fdecea;
    color: #c0392b;
    padding: 10px;
    border-radius: 6px;
    font-size: 13px;
    margin-bottom: 15px;
}

.footer-text {
    margin-top: 20px;
    font-size: 12px;
    color: #95a5a6;
}
</style>

</head>
<body>

<div class="login-card">

    <img src="assets/logo RSBB Baru.jpg" alt="Logo RSBB" class="logo">

    <h2>Login Data MCU</h2>
    <div class="subtitle">Rumah Sakit Balikpapan Baru</div>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">

        <div class="form-group">
            <label>Username</label>
            <input 
                type="text" 
                name="username" 
                value="<?= htmlspecialchars($username) ?>"
                autocomplete="username"
                required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input 
                type="password" 
                name="password"
                autocomplete="current-password"
                required>
        </div>

        <button type="submit">Login</button>

    </form>

    <div class="footer-text">
        © <?= date('Y') ?> RS Balikpapan Baru
    </div>

</div>

</body>
</html>
