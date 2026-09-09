<?php

require_once 'config.php';

if (!isset($_GET['uuid']) || empty($_GET['uuid'])) {
    die('Invalid request');
}

$uuid = $_GET['uuid'];
$no_mcu = $_GET['no_mcu'] ?? 'qr';

// VALIDASI UUID KE DATABASE (PDO VERSION)
$stmt = $pdo->prepare("SELECT uuid FROM data_mcu WHERE uuid = ?");
$stmt->execute([$uuid]);
$result = $stmt->fetch();

if ($result === false) {
    die('UUID tidak ditemukan');
}

// Generate QR
$url = "http://mcu.rsbalikpapanbaru.co.id:8879/pasien-detail.php?uuid=" . $uuid;
$qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($url);

// Force download
header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="qr-'.$no_mcu.'.png"');

echo file_get_contents($qrUrl);
exit;
