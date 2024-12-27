<?php 
// Include database connection
include 'koneksi.php';

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: loginUser.php");
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

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = mysqli_real_escape_string($mysqli, $_POST['id']);
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
            echo "<script> 
                    alert('Data berhasil diupdate.');
                    document.location='dokter.php';
                  </script>";
        } else {
            echo "<script> 
                    alert('Gagal mengupdate data: " . mysqli_error($mysqli) . "');
                    document.location='dokter.php';
                  </script>";
        }
    } else {
        $sql = "INSERT INTO dokter (nama, alamat, no_hp, id_poli, nip, password) 
        VALUES ('$nama', '$alamat', '$no_hp', '$id_poli', '$nip', '$hashed_password')";
        $tambah = mysqli_query($mysqli, $sql);

        if ($tambah) {
            echo "<script> 
                    alert('Data berhasil ditambahkan.');
                    document.location='dokter.php';
                  </script>";
        } else {
            echo "<script> 
                    alert('Gagal menambahkan data: " . mysqli_error($mysqli) . "');
                    document.location='dokter.php';
                  </script>";
        }
    }
}

if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id = mysqli_real_escape_string($mysqli, $_GET['id']);
    $hapus = mysqli_query($mysqli, "DELETE FROM dokter WHERE id = '$id'");

    if ($hapus) {
        echo "<script> 
                alert('Data berhasil dihapus.');
                document.location='dokter.php';
              </script>";
    } else {
        echo "<script> 
                alert('Gagal menghapus data: " . mysqli_error($mysqli) . "');
                document.location='dokter.php';
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Data Dokter - Poliklinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

        .header i {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            color: #0056b3;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 20px 0;
        }

        .back-link:hover {
            background-color: #f8f9fa;
            color: #004494;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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

        .card-header {
            background-color: #0056b3;
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px;
            border: 1px solid #dee2e6;
        }

        .form-control:focus, .form-select:focus {
            border-color: #0056b3;
            box-shadow: 0 0 0 0.2rem rgba(0,86,179,0.25);
        }

        .table th {
            background-color: #0056b3;
            color: white;
            padding: 15px;
        }

        .table td {
            vertical-align: middle;
            padding: 15px;
        }

        .btn-action {
            width: 35px;
            height: 35px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin: 0 3px;
        }

        .footer {
            background-color: #0056b3;
            color: white;
            padding: 20px 0;
            text-align: center;
            margin-top: auto;
        }

        .footer a {
            color: white;
            text-decoration: none;
        }

        .footer a:hover {
            color: #ccc;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <i class="fas fa-user-md"></i>
        <h1>Manajemen Data Dokter</h1>
        <p>Kelola data dokter dengan mudah dan efisien</p>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="container">
            <!-- Back Button -->
            <a href="dashboardAdmin.php" class="back-link">
                <i class="fas fa-arrow-left me-2"></i>
                Kembali ke Dashboard
            </a>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Data Dokter</h3>
                </div>
                <div class="card-body">
                    <!-- Form Section -->
                    <form action="" method="POST" onsubmit="return(validate());">
                        <?php
                        $nama = $alamat = $no_hp = $id_poli = $nip = $password = '';
                        $buttonText = 'Tambah';

                        if (isset($_GET['id'])) {
                            $id = $_GET['id'];
                            $query = mysqli_query($mysqli, "SELECT * FROM dokter WHERE id='$id'");
                            $data = mysqli_fetch_assoc($query);
                            if ($data) {
                                $nama = $data['nama'];
                                $alamat = $data['alamat'];
                                $no_hp = $data['no_hp'];
                                $id_poli = $data['id_poli'];
                                $nip = $data['nip'];
                                $password = $data['password']; // You can display the hashed password, but don't show it in the input.
                                $buttonText = 'Update';
                                echo '<input type="hidden" name="id" value="'.$id.'">';
                            }
                        }
                        ?>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Dokter <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control" id="nama" required value="<?php echo $nama; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Alamat <span class="text-danger">*</span></label>
                                <input type="text" name="alamat" class="form-control" id="alamat" required value="<?php echo $alamat; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. HP <span class="text-danger">*</span></label>
                                <input type="text" name="no_hp" class="form-control" id="no_hp" required value="<?php echo $no_hp; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Poli <span class="text-danger">*</span></label>
                                <select name="id_poli" class="form-select" id="id_poli" required>
                                    <option value="">Pilih Poli</option>
                                    <?php
                                    // Query untuk mengambil data poli
                                    $query_poli = mysqli_query($mysqli, "SELECT id, nama_poli FROM poli");

                                    // Cek apakah query berhasil dan ada hasil
                                    if ($query_poli) {
                                        while ($poli = mysqli_fetch_assoc($query_poli)) {
                                            // Tentukan apakah poli ini yang sudah dipilih (jika ada id_poli yang terisi)
                                            $selected = ($poli['id'] == $id_poli) ? 'selected' : '';
                                            echo "<option value='{$poli['id']}' $selected>{$poli['nama_poli']}</option>";
                                        }
                                    } else {
                                        echo "<option value=''>Gagal memuat data poli</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NIP <span class="text-danger">*</span></label>
                                <input type="text" name="nip" class="form-control" id="nip" required value="<?php echo $nip; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" id="password" <?php echo isset($_GET['id']) ? '' : 'required'; ?>>
                            </div>
                        </div>
                        <button type="submit" name="simpanData" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i><?php echo $buttonText; ?>
                        </button>
                    </form>

                    <!-- Table Section -->
                    <div class="table-responsive mt-4">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>No. HP</th>
                                    <th>Poli</th>
                                    <th>NIP</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = mysqli_query($mysqli, "SELECT * FROM dokter");
                                $no = 1;
                                while ($data = mysqli_fetch_array($result)) :
                                ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $data['nama']; ?></td>
                                        <td><?php echo $data['alamat']; ?></td>
                                        <td><?php echo $data['no_hp']; ?></td>
                                        <td><?php echo $data['id_poli']; ?></td>
                                        <td><?php echo $data['nip']; ?></td>
                                        <td class="text-center">
                                            <a href="dokter.php?id=<?php echo $data['id']; ?>" class="btn btn-warning btn-action">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="dokter.php?id=<?php echo $data['id']; ?>&aksi=hapus" 
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" 
                                               class="btn btn-danger btn-action">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <h5>&copy; 2024 Sistem Layanan Kesehatan</h5>
            <p><a href="privacy.php">Kebijakan Privasi</a> | <a href="terms.php">Syarat dan Ketentuan</a></p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function validate() {
            var nama = document.getElementById("nama").value;
            var alamat = document.getElementById("alamat").value;
            var no_hp = document.getElementById("no_hp").value;
            var id_poli = document.getElementById("id_poli").value;
            var nip = document.getElementById("nip").value;
            var password = document.getElementById("password").value;

            if (nama == "" || alamat == "" || no_hp == "" || id_poli == "" || nip == "") {
                alert("Semua field harus diisi!");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
