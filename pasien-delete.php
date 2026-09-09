<?php
session_start();
require 'config.php';
require 'auth.php'; // pastikan hanya user login bisa delete

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$uuid = $_POST['uuid'] ?? '';

if (!$uuid) {
    $_SESSION['error'] = "UUID tidak valid.";
    header("Location: index.php");
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM data_mcu WHERE uuid = ?");
    $stmt->execute([$uuid]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = "Data pasien berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Data tidak ditemukan.";
    }

} catch (PDOException $e) {
    $_SESSION['error'] = "Gagal menghapus data.";
}

header("Location: index.php");
exit;
