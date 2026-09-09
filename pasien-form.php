<?php
/* |-------------------------------------------------------------------------- | pasien-form.php |-------------------------------------------------------------------------- | Form input data MCU pasien | Menyimpan data ke tabel DATA_MCU |-------------------------------------------------------------------------- */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config.php';
require 'auth.php';

/* ===============================
 PROSES INSERT DATA ================================ */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    function generateUUID()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    $uuid = generateUUID();

    /* ===============================
     PROSES UPLOAD FOTO
     ================================ */

    $foto_name = null;

    if (isset($_FILES['foto_pasien']) && $_FILES['foto_pasien']['error'] === 0) {

        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $file_type = $_FILES['foto_pasien']['type'];
        $file_size = $_FILES['foto_pasien']['size'];

        if (!in_array($file_type, $allowed_types)) {
            die("Format foto tidak valid. Gunakan JPG atau PNG.");
        }

        if ($file_size > 2 * 1024 * 1024) {
            die("Ukuran foto maksimal 2MB.");
        }

        $extension = pathinfo($_FILES['foto_pasien']['name'], PATHINFO_EXTENSION);
        $foto_name = $uuid . "." . $extension;

        move_uploaded_file(
            $_FILES['foto_pasien']['tmp_name'],
            "uploads/" . $foto_name
        );
    }

    /* ===============================
     INSERT DATABASE
     ================================ */

    $stmt = $pdo->prepare("
        INSERT INTO data_mcu
        (uuid, no_mcu, tgl_mcu, nama, tgl_lahir, posisi, departemen, perusahaan, foto_pasien, pemeriksaan_laboratorium, pemeriksaan_non_laboratorium)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $uuid,
        $_POST['no_mcu'],
        $_POST['tgl_mcu'],
        $_POST['nama'],
        $_POST['tgl_lahir'],
        $_POST['posisi'],
        $_POST['departemen'],
        $_POST['perusahaan'],
        $foto_name,
        $_POST['pemeriksaan_laboratorium'],
        $_POST['pemeriksaan_non_laboratorium']
    ]);

    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Input Data Pasien MCU</title>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6f9;
    padding: 40px;
}

.container {
    max-width: 800px;
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

form {
    display: grid;
    gap: 15px;
}

label {
    font-weight: 600;
}

input, textarea {
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
    width: 100%;
}

textarea {
    resize: vertical;
    min-height: 80px;
}

button {
    padding: 10px;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 15px;
}

button:hover {
    background: #2980b9;
}

.btn-back {
    display: inline-block;
    margin-bottom: 20px;
    padding: 8px 14px;
    background: #2c3e50;
    color: white;
    text-decoration: none;
    border-radius: 4px;
}
</style>

</head>
<body>

<div class="container">

<a href="index.php" class="btn-back">← Kembali</a>

<h2>Form Input Data MCU</h2>

<form method="POST" enctype="multipart/form-data">

    <div>
        <label>No MCU</label>
        <input type="text" name="no_mcu" required>
    </div>

    <div>
        <label>Tanggal MCU</label>
        <input type="date" name="tgl_mcu" required>
    </div>

    <div>
        <label>Nama</label>
        <input type="text" name="nama" required>
    </div>

    <div>
        <label>Tanggal Lahir</label>
        <input type="date" name="tgl_lahir" required>
    </div>

    <div>
        <label>Posisi</label>
        <input type="text" name="posisi">
    </div>

    <div>
        <label>Departemen</label>
        <input type="text" name="departemen">
    </div>

    <div>
        <label>Perusahaan</label>
        <input type="text" name="perusahaan">
    </div>

    <div>
        <label>Foto Pasien</label>
        <input type="file" name="foto_pasien" accept="image/*" required>
    </div>

    <div>
        <label>Pemeriksaan Laboratorium</label>
        <textarea name="pemeriksaan_laboratorium" placeholder="Pisahkan dengan koma atau enter"></textarea>
    </div>

    <div>
        <label>Pemeriksaan Non Laboratorium</label>
        <textarea name="pemeriksaan_non_laboratorium" placeholder="Pisahkan dengan koma atau enter"></textarea>
    </div>

    <button type="submit">Simpan Data</button>

</form>

</div>

</body>
</html>
