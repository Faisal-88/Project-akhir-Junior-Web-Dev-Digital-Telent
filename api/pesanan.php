<?php
// 1. Pengaturan Error Reporting agar jika ada salah, Vercel memberitahu kita
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Konfigurasi Database TiDB
$host   = "gateway01.ap-southeast-1.prod.aws.tidbcloud.com";
$user   = "3QSp3qi6JdHmEaT.root";
$pass   = "89bsOyEj454DU0tq";
$db     = "test";
$port   = "4000";

// 3. Koneksi Database dengan Penanganan Error yang Kuat
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $koneksi = mysqli_init();
    // SSL Verify diset ke false karena Vercel tidak punya sertifikat CA TiDB secara lokal
    $koneksi->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false); 
    $success = @$koneksi->real_connect($host, $user, $pass, $db, $port, NULL, MYSQLI_CLIENT_SSL);
    
    if (!$success) {
        throw new Exception("Koneksi gagal: " . $koneksi->connect_error);
    }
} catch (Exception $e) {
    die("<div style='color:red;text-align:center;margin-top:50px;'>
            <h2>Koneksi Database Gagal</h2>
            <p>Pesan: " . $e->getMessage() . "</p>
         </div>");
}

// 4. Inisialisasi Variabel
$Id             = "";
$Nama           = "";
$Pesanan        = "";
$NoMeja         = "";
$JumlahPesanan  = "";
$Tanggal        = "";
$Status         = "";
$sukses         = "";
$error          = "";

$op = isset($_GET['op']) ? $_GET['op'] : "";
$Id = isset($_GET['Id']) ? $_GET['Id'] : "";

// --- OPERASI DELETE ---
if($op == 'delete' && $Id){
    $sql1   = "DELETE FROM pesanan WHERE Id = '$Id'";
    $q1     = mysqli_query($koneksi, $sql1);
    if($q1) $sukses = "Berhasil hapus data";
    else $error = "Gagal melakukan delete data";
}

// --- OPERASI EDIT (Ambil Data Lama) ---
if ($op == 'edit' && $Id) {
    $sql1   = "SELECT * FROM pesanan WHERE Id = '$Id'";
    $q1     = mysqli_query($koneksi, $sql1);
    $r1     = mysqli_fetch_array($q1);
    if ($r1) {
        $Nama           = $r1['Nama'];
        $Pesanan        = $r1['Pesanan'];
        $NoMeja         = $r1['No Meja'];
        $JumlahPesanan  = $r1['Jumlah Pesanan'];
        $Tanggal        = $r1['Tanggal'];
        $Status         = $r1['Status'];
    } else {
        $error = "Data tidak ditemukan";
    }
}

// --- OPERASI SIMPAN (Create & Update) ---
if (isset($_POST['simpan'])) {
    $Nama           = $_POST['Nama'];
    $Pesanan        = $_POST['Pesanan'];
    $NoMeja         = $_POST['NoMeja'];
    $JumlahPesanan  = $_POST['JumlahPesanan'];
    $Tanggal        = $_POST['Tanggal'];
    $Status         = $_POST['Status'];

    if ($Nama && $Pesanan && $NoMeja && $JumlahPesanan && $Tanggal && $Status) {
        if ($op == 'edit') { 
            // Update
            $sql1 = "UPDATE pesanan SET Nama='$Nama', Pesanan='$Pesanan', `No Meja`='$NoMeja', `Jumlah Pesanan`='$JumlahPesanan', Tanggal='$Tanggal', Status='$Status' WHERE Id='$Id'";
            $q1   = mysqli_query($koneksi, $sql1);
            if ($q1) $sukses = "Data berhasil diupdate";
            else $error = "Data gagal diupdate";
        } else { 
            // Insert
            $sql1 = "INSERT INTO pesanan (Nama, Pesanan, `No Meja`, `Jumlah Pesanan`, Tanggal, Status) VALUES ('$Nama', '$Pesanan', '$NoMeja', '$JumlahPesanan', '$Tanggal', '$Status')";
            $q1   = mysqli_query($koneksi, $sql1);
            if ($q1) $sukses = "Berhasil memasukkan data baru";
            else $error = "Gagal memasukkan data";
        }
    } else {
        $error = "Silakan masukkan semua data";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung 26 Ilir - Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container-custom { max-width: 1150px; margin: auto; padding-top: 20px; }
        .bg-dark-custom { background-color: #212529 !important; }
    </style>
</head>
<body style="padding-top: 70px;">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark-custom fixed-top">
      <div class="container">
        <a class="navbar-brand" href="#">Muhammad Faisal</a>
        <div class="collapse navbar-collapse">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="../index.html">HOME</a></li>
            <li class="nav-item"><a class="nav-link" href="../menu.html">DAFTAR MENU</a></li>
            <li class="nav-item"><a class="nav-link active" href="pesanan.php">PESANAN</a></li>
            <li class="nav-item"><a class="nav-link" href="../index.html#tentang">TENTANG KAMI</a></li>
            <li class="nav-item"><a class="nav-link" href="../index.html#kontak">KONTAK KAMI</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-custom">
        <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <?php if ($sukses) echo "<div class='alert alert-success'>$sukses</div>"; ?>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Input / Edit Pesanan</div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label class="col-sm-2">Nama</label>
                        <div class="col-sm-10"><input type="text" class="form-control" name="Nama" value="<?php echo $Nama ?>" required></div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2">Pesanan</label>
                        <div class="col-sm-10"><input type="text" class="form-control" name="Pesanan" value="<?php echo $Pesanan ?>" required></div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2">No. Meja</label>
                        <div class="col-sm-10"><input type="text" class="form-control" name="NoMeja" value="<?php echo $NoMeja ?>" required></div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2">Jumlah</label>
                        <div class="col-sm-10"><input type="number" class="form-control" name="JumlahPesanan" value="<?php echo $JumlahPesanan ?>" required></div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2">Tanggal</label>
                        <div class="col-sm-10"><input type="date" class="form-control" name="Tanggal" value="<?php echo $Tanggal ?>" required></div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2">Status</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="Status" required>
                                <option value="">- Pilih Status -</option>
                                <option value="Lunas" <?php if($Status=="Lunas") echo "selected"?>>Lunas</option>
                                <option value="Belum Bayar" <?php if($Status=="Belum Bayar") echo "selected"?>>Belum Bayar</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="simpan" class="btn btn-primary">Simpan Data</button>
                    <a href="pesanan.php" class="btn btn-secondary">Reset</a>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header text-white bg-secondary">Data Pesanan</div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Pesanan</th>
                            <th>No. Meja</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2   = "SELECT * FROM pesanan ORDER BY Id DESC";
                        $q2     = mysqli_query($koneksi, $sql2);
                        $urut   = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                        ?>
                        <tr>
                            <td><?php echo $urut++ ?></td>
                            <td><?php echo $r2['Nama'] ?></td>
                            <td><?php echo $r2['Pesanan'] ?></td>
                            <td><?php echo $r2['No Meja'] ?></td>
                            <td><?php echo $r2['Jumlah Pesanan'] ?></td>
                            <td><?php echo $r2['Tanggal'] ?></td>
                            <td><?php echo $r2['Status'] ?></td>
                            <td>
                                <a href="pesanan.php?op=edit&Id=<?php echo $r2['Id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="pesanan.php?op=delete&Id=<?php echo $r2['Id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>