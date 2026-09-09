<?php
/*
|--------------------------------------------------------------------------
| pasien-detail.php
|--------------------------------------------------------------------------
| Halaman untuk menampilkan detail 1 pasien berdasarkan UUID
| UUID dikirim melalui query parameter (?uuid=...)
|--------------------------------------------------------------------------
*/

require 'config.php';

/* Validasi UUID */
if (!isset($_GET['uuid'])) {
    die("UUID tidak ditemukan.");
}

$uuid = $_GET['uuid'];

/* Validasi format UUID */
if (!preg_match('/^[0-9a-fA-F-]{36}$/', $uuid)) {
    die("Format UUID tidak valid.");
}

/* Query detail pasien */
$stmt = $pdo->prepare("SELECT * FROM data_mcu WHERE uuid = ?");
$stmt->execute([$uuid]);
$pasien = $stmt->fetch();

if (!$pasien) {
    die("Data pasien tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Pasien MCU</title>

<style>
/* ================================
   GLOBAL STYLE
================================ */
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6f9;
    margin: 0;
    padding: 40px;
}

.container {
    max-width: 900px;
    margin: auto;
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

h2 {
    margin-bottom: 20px;
    color: #2c3e50;
}

/* ================================
   HEADER RS STYLE
================================ */

.header-rs {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #eee;
}

.logo-rs {
    width: 80px;
    height: auto; /* menjaga proporsi asli */
}

.title-rs {
    text-align: center;
}

.title-rs h1 {
    margin: 0;
    font-size: 24px;
    color: #2c3e50;
}

.title-rs p {
    margin: 4px 0 0 0;
    font-size: 14px;
    color: #7f8c8d;
}

/* ================================
   TABLE STYLE
================================ */
table {
    width: 100%;
    border-collapse: collapse;
}

th {
    width: 35%;
    background: #ecf0f1;
    padding: 12px;
    text-align: left;
}

td {
    padding: 12px;
}

tr {
    border-bottom: 1px solid #eee;
}

/* ================================
   FOTO PASIEN
================================ */
.foto-container {
    text-align: center;
    margin-bottom: 25px;
}

.foto-pasien {
    max-width: 180px;   /* batas lebar */
    height: auto;       /* menjaga rasio asli */
    border-radius: 8px;
    border: 3px solid #ecf0f1;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.foto-empty {
    width: 160px;
    height: 160px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #ecf0f1;
    color: #7f8c8d;
    border-radius: 8px;
    font-size: 14px;
}

</style>

</head>
<body>

<div class="container">

<!-- ===============================
     HEADER RS
================================ -->
<div class="header-rs">
    <img src="assets/logo RSBB Baru.jpg" alt="Logo RSBB" class="logo-rs">
    <div class="title-rs">
        <h1>Rumah Sakit Balikpapan Baru</h1>
    </div>
</div>


<!-- ===============================
FOTO PASIEN
================================ -->
<div class="foto-container">
    <?php if (!empty($pasien['foto_pasien']) && file_exists('uploads/' . $pasien['foto_pasien'])): ?>
        <img 
        src="uploads/<?= htmlspecialchars($pasien['foto_pasien']) ?>" 
        alt="Foto Pasien" 
        class="foto-pasien"
        >
        <?php else: ?>
            <div class="foto-empty">
                Tidak Ada Foto
            </div>
            <?php endif; ?>
        </div>
        
        <h2>Detail MCU Pasien</h2>
<table>
    <tr>
        <th>No MCU</th>
        <td><?= htmlspecialchars($pasien['no_mcu']) ?></td>
    </tr>
    <tr>
        <th>Nama</th>
        <td><?= htmlspecialchars($pasien['nama']) ?></td>
    </tr>
    <tr>
        <th>Tanggal Lahir</th>
        <td><?= htmlspecialchars($pasien['tgl_lahir']) ?></td>
    </tr>
    <tr>
        <th>Posisi</th>
        <td><?= htmlspecialchars($pasien['posisi']) ?></td>
    </tr>
    <tr>
        <th>Departemen</th>
        <td><?= htmlspecialchars($pasien['departemen']) ?></td>
    </tr>
    <tr>
        <th>Perusahaan</th>
        <td><?= htmlspecialchars($pasien['perusahaan']) ?></td>
    </tr>
    <tr>
        <th>Pemeriksaan Laboratorium</th>
        <td><?= nl2br(htmlspecialchars($pasien['pemeriksaan_laboratorium'])) ?></td>
    </tr>
    <tr>
        <th>Pemeriksaan Non Laboratorium</th>
        <td><?= nl2br(htmlspecialchars($pasien['pemeriksaan_non_laboratorium'])) ?></td>
    </tr>
</table>

</div>

</body>
</html>
