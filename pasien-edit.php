<?php
require 'config.php';
require 'auth.php';

/* ===============================
   VALIDASI UUID
================================ */
if (!isset($_GET['uuid'])) {
    die("UUID tidak ditemukan.");
}

$uuid = $_GET['uuid'];

if (!preg_match('/^[0-9a-fA-F-]{36}$/', $uuid)) {
    die("Format UUID tidak valid.");
}

/* ===============================
   AMBIL DATA PASIEN
================================ */
$stmt = $pdo->prepare("SELECT * FROM data_mcu WHERE uuid = ? LIMIT 1");
$stmt->execute([$uuid]);
$pasien = $stmt->fetch();

if (!$pasien) {
    die("Data pasien tidak ditemukan.");
}

/* ===============================
   PROSES UPDATE
================================ */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $foto_name = $pasien['foto_pasien']; // default pakai foto lama

    /* ========= PROSES UPLOAD FOTO BARU ========= */
    if (isset($_FILES['foto_pasien']) && $_FILES['foto_pasien']['error'] === 0) {

        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $file_type = $_FILES['foto_pasien']['type'];
        $file_size = $_FILES['foto_pasien']['size'];

        if (!in_array($file_type, $allowed_types)) {
            die("Format foto harus JPG atau PNG.");
        }

        if ($file_size > 2 * 1024 * 1024) {
            die("Ukuran foto maksimal 2MB.");
        }

        // Hapus foto lama jika ada
        if (!empty($pasien['foto_pasien'])) {
            $old_file = "uploads/" . $pasien['foto_pasien'];
            if (file_exists($old_file)) {
                unlink($old_file);
            }
        }

        $extension = pathinfo($_FILES['foto_pasien']['name'], PATHINFO_EXTENSION);
        $foto_name = $uuid . "." . $extension;

        move_uploaded_file(
            $_FILES['foto_pasien']['tmp_name'],
            "uploads/" . $foto_name
        );
    }

    /* ========= UPDATE DATABASE ========= */
    $stmt = $pdo->prepare("
        UPDATE data_mcu SET
        no_mcu = ?,
        tgl_mcu = ?,
        nama = ?,
        tgl_lahir = ?,
        posisi = ?,
        departemen = ?,
        perusahaan = ?,
        foto_pasien = ?,
        pemeriksaan_laboratorium = ?,
        pemeriksaan_non_laboratorium = ?
        WHERE uuid = ?
        LIMIT 1
    ");

    $stmt->execute([
        $_POST['no_mcu'],
        $_POST['tgl_mcu'],
        $_POST['nama'],
        $_POST['tgl_lahir'],
        $_POST['posisi'],
        $_POST['departemen'],
        $_POST['perusahaan'],
        $foto_name,
        $_POST['pemeriksaan_laboratorium'],
        $_POST['pemeriksaan_non_laboratorium'],
        $uuid
    ]);

    header("Location: pasien-detail.php?uuid=" . $uuid);
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Pasien MCU</title>

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
}

input, textarea {
    width: 100%;
    padding: 8px;
    margin-top: 4px;
    margin-bottom: 15px;
}

button {
    padding: 10px 16px;
    background: #2980b9;
    color: white;
    border: none;
    border-radius: 4px;
}

button:hover {
    background: #1f6391;
}

/* ================================
   BACK BUTTON
================================ */
.btn-back {
    display: inline-block;
    margin-bottom: 20px;
    padding: 8px 14px;
    background: #2c3e50;
    color: white;
    text-decoration: none;
    border-radius: 4px;
}

.btn-back:hover {
    background: #1a252f;
}

</style>
</head>
<body>

<div class="container">
<h2>Edit Data Pasien</h2>

<form method="POST" enctype="multipart/form-data">

<label>No MCU</label>
<input type="text" name="no_mcu" value="<?= htmlspecialchars($pasien['no_mcu']) ?>" required>

<label>Tanggal MCU</label>
<input type="date" name="tgl_mcu" value="<?= htmlspecialchars($pasien['tgl_mcu']) ?>" required>

<label>Nama</label>
<input type="text" name="nama" value="<?= htmlspecialchars($pasien['nama']) ?>" required>

<label>Tanggal Lahir</label>
<input type="date" name="tgl_lahir" value="<?= htmlspecialchars($pasien['tgl_lahir']) ?>" required>

<label>Posisi</label>
<input type="text" name="posisi" value="<?= htmlspecialchars($pasien['posisi']) ?>">

<label>Departemen</label>
<input type="text" name="departemen" value="<?= htmlspecialchars($pasien['departemen']) ?>">

<label>Perusahaan</label>
<input type="text" name="perusahaan" value="<?= htmlspecialchars($pasien['perusahaan']) ?>">

<label>Foto Pasien (Upload jika ingin ganti)</label>
<input type="file" name="foto_pasien" accept="image/*">

<?php if (!empty($pasien['foto_pasien'])): ?>
    <p>Foto Saat Ini:</p>
    <img src="uploads/<?= htmlspecialchars($pasien['foto_pasien']) ?>" 
         style="max-width:150px; height:auto;">
         <br>
<?php endif; ?>

<label style="margin-top: 10px;">Pemeriksaan Laboratorium</label>
<textarea name="pemeriksaan_laboratorium"><?= htmlspecialchars($pasien['pemeriksaan_laboratorium']) ?></textarea>

<label style="margin-top: 10px;">Pemeriksaan Non Laboratorium</label>
<textarea name="pemeriksaan_non_laboratorium"><?= htmlspecialchars($pasien['pemeriksaan_non_laboratorium']) ?></textarea>

<div style="margin-top: 20px;">
<button type="submit">Update Data</button>
<a href="index.php" class="btn-back">Batal</a>
</div>

</form>
</div>

</body>
</html>
