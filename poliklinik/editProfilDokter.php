<?php 
// Include database connection
include 'koneksi.php';

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['nip'])) {
    header("Location: loginDokter.php");
    exit;
}

// Ambil data dokter yang sedang login
$nip = $_SESSION['nip'];
$query = mysqli_query($mysqli, "SELECT * FROM dokter WHERE nip='$nip'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Dokter tidak ditemukan.'); document.location='loginDokter.php';</script>";
    exit;
}

if (isset($_POST['simpanData'])) {
    $nama = mysqli_real_escape_string($mysqli, $_POST['nama']);
    $alamat = mysqli_real_escape_string($mysqli, $_POST['alamat']);
    $no_hp = mysqli_real_escape_string($mysqli, $_POST['no_hp']);
    $id_poli = mysqli_real_escape_string($mysqli, $_POST['id_poli']);
    $nip = mysqli_real_escape_string($mysqli, $_POST['nip']);
    $password = $_POST['password'];

    // Hash the password only if it is not empty
    $hashed_password = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : '';

    $id = $data['id']; // Ambil ID dokter dari data yang diambil

    $sql = "UPDATE dokter SET 
                nama='$nama', 
                alamat='$alamat', 
                no_hp='$no_hp', 
                id_poli='$id_poli', 
                nip='$nip' ";
    if (!empty($hashed_password)) {
        $sql .= ", password='$hashed_password' ";
    }
    $sql .= "WHERE id='$id'";
    $edit = mysqli_query($mysqli, $sql);

    if ($edit) {
        // Perbarui data sesi
        $_SESSION['nama'] = $nama; // Perbarui nama di sesi
        echo "<script> 
                alert('Data berhasil diupdate.');
                document.location='berandaDokter.php'; // Redirect ke beranda dokter
              </script>";
    } else {
        echo "<script> 
                alert('Gagal mengupdate data: " . mysqli_error($mysqli) . "');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Dokter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .header {
            background-color: #0056b3;
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .content {
            flex: 1;
            padding: 20px 0;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border: none;
            margin-bottom: 30px;
        }
        .footer {
            background-color: #0056b3;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Edit Profil Dokter</h1>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <!-- Tombol Kembali -->
                    <a href="berandaDokter.php" class="btn btn-secondary mb-3">
                        <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                    </a>
                    <form action="" method="POST" onsubmit="return(validate());">
                        <div class="mb-3">
                            <label class="form-label">Nama Dokter</label>
                            <input type="text" name="nama" class="form-control" required value="<?php echo $data['nama']; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <input type="text" name="alamat" class="form-control" required value="<?php echo $data['alamat']; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. HP</label>
                            <input type="text" name="no_hp" class="form-control" required value="<?php echo $data['no_hp']; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Poli <span class="text-danger">*</span></label>
                            <select name="id_poli" class="form-select" id="id_poli" required>
                                <option value="">Pilih Poli</option>
                                <?php
                                // Ambil id_poli dari data dokter yang sedang login
                                $current_id_poli = $data['id_poli']; // Pastikan $data sudah diambil sebelumnya

                                // Query untuk mengambil data poli
                                $query_poli = mysqli_query($mysqli, "SELECT id, nama_poli FROM poli");

                                // Cek apakah query berhasil dan ada hasil
                                if ($query_poli) {
                                    while ($poli = mysqli_fetch_assoc($query_poli)) {
                                        // Tentukan apakah poli ini yang sudah dipilih
                                        $selected = ($poli['id'] == $current_id_poli) ? 'selected' : '';
                                        echo "<option value='{$poli['id']}' $selected>{$poli['nama_poli']}</option>";
                                    }
                                } else {
                                    echo "<option value=''>Gagal memuat data poli</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIP</label>
                            <input type="text" name="nip" class="form-control" required value="<?php echo $data['nip']; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password (kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <button type="submit" name="simpanData" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2023 Sistem Informasi Dokter. All rights reserved.</p>
    </div>
</body>
</html>