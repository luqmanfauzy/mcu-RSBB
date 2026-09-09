<?php
/*
|--------------------------------------------------------------------------
| pasien-list.php
|--------------------------------------------------------------------------
| Halaman untuk menampilkan daftar seluruh pasien MCU
| Mengambil data dari tabel data_mcu dan menampilkan dalam bentuk tabel
|--------------------------------------------------------------------------
*/

ygfkdbwkdhbkwj

require 'config.php';
require 'auth.php';

/* Query ambil data pasien */
$stmt = $pdo->query("SELECT uuid, no_mcu, nama FROM data_mcu ORDER BY id DESC");
$pasien = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>List Pasien MCU</title>

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
    max-width: 1000px;
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
    height: auto; /* menjaga rasio gambar */
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

thead {
    background: #2c3e50;
    color: white;
}

th, td {
    padding: 12px 15px;
    text-align: left;
}

tbody tr {
    border-bottom: 1px solid #eee;
}

tbody tr:hover {
    background: #f9f9f9;
}

/* ================================
   BUTTON LINK
================================ */
.btn-detail {
    display: inline-block;
    padding: 6px 12px;
    background: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
}

.btn-detail:hover {
    background: #2980b9;
}

/* ================================
   HEADER ACTION
================================ */
.header-action {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.btn-add {
    display: inline-block;
    padding: 10px 16px;
    background: #27ae60;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    transition: 0.2s ease-in-out;
}

.btn-add:hover {
    background: #1e8449;
}

/* ================================
   TOP BAR
================================ */
.top-bar {
    display: flex;
    justify-content: right;
    align-items: center;
    margin-bottom: 25px;
}

/* ================================
   LOGOUT BUTTON
================================ */
.btn-logout {
    padding: 8px 16px;
    background: #e74c3c;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    transition: 0.2s ease-in-out;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.btn-logout:hover {
    background: #c0392b;
}

.alert-error {
    background: #fdecea;
    color: #c0392b;
    padding: 12px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    border-left: 5px solid #e74c3c;
    font-size: 14px;
}

.btn-delete {
    background: #e74c3c;
    border: none;
    padding: 6px 12px;
    color: white;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    transition: 0.2s;
}

.btn-delete:hover {
    background: #c0392b;
}

.btn-edit {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: #f39c12;
    color: #ffffff;
    text-decoration: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    transition: 0.2s ease-in-out;
}

.btn-edit:hover {
    background: #d68910;
    transform: translateY(-1px);
}

.btn-edit .icon {
    font-size: 13px;
}

.btn-barcode {
    background-color: #28a745;
    color: white;
    padding: 6px 12px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
}
.btn-barcode:hover {
    opacity: 0.9;
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

<div class="top-bar">
    <a href="logout.php" class="btn-logout">Logout</a>
</div>

<div class="header-action">
    <h2>Daftar Pasien MCU</h2>
    <a href="pasien-form.php" class="btn-add">+ Tambah Pasien</a>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert-error">
        <?= htmlspecialchars($_SESSION['error']) ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>No MCU</th>
            <th>Nama</th>
            <th>Hasil Detail MCU</th>
            <th>QR Code</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

    <?php if (count($pasien) > 0): ?>
        <?php foreach ($pasien as $index => $p): ?>
        <tr>
            <td><?= $index + 1 ?></td>
            <td><?= htmlspecialchars($p['no_mcu']) ?></td>
            <td><?= htmlspecialchars($p['nama']) ?></td>
            <td>
                <!-- Link menuju halaman detail berdasarkan UUID -->
                <a class="btn-detail" 
                   href="pasien-detail.php?uuid=<?= $p['uuid'] ?>">
                   Lihat Detail
                </a>
            </td>
            <!-- download qr code -->
            <td>
                <a href="download-qr.php?uuid=<?= $p['uuid'] ?>&no_mcu=<?= $p['no_mcu'] ?>">
                    <button class="btn-barcode">Download QR</button>
                </a>
            </td>
            <td>
                <a href="pasien-edit.php?uuid=<?= $p['uuid'] ?>" class="btn-edit">Ubah</a>
                <form method="POST" action="pasien-delete.php" 
                    onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                    style="display:inline;">
                    <input type="hidden" name="uuid" value="<?= htmlspecialchars($p['uuid']) ?>">
                    <button type="submit" class="btn-delete">
                        Hapus
                    </button>
                </form>

            </td>   
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">Belum ada data pasien.</td>
        </tr>
    <?php endif; ?>

    </tbody>
</table>

</div>

</body>
</html>
