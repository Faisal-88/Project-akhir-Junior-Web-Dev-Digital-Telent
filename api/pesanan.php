<?php
$host   = "gateway01.ap-southeast-1.prod.aws.tidbcloud.com";
$user   = "3QSp3qi6JdHmEaT.root";
$pass   = "89bsOyEj454DU0tq";
$db     = "test";
$port   = "4000";

$koneksi = mysqli_init();
$koneksi->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);
$success = $koneksi->real_connect($host, $user, $pass, $db, $port, NULL, MYSQLI_CLIENT_SSL);

if (!$success) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Inisialisasi variabel agar tidak error saat pertama buka
$Nama           = "";
$Pesanan        = "";
$NoMeja         = "";
$JumlahPesanan  = "";
$Tanggal        = "";
$Status         = "";
$sukses         = "";
$error          = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

// Operasi Delete
if($op == 'delete'){
    $Id         = $_GET['Id'];
    $sql1       = "DELETE FROM pesanan WHERE Id = '$Id'";
    $q1         = mysqli_query($koneksi, $sql1);
    if($q1){
        $sukses = "Berhasil hapus data";
    }else{
        $error  = "Gagal melakukan delete data";
    }
}

// Operasi Edit (Ambil data lama)
if ($op == 'edit') {
    $Id         = $_GET['Id'];
    $sql1       = "SELECT * FROM pesanan WHERE Id = '$Id'";
    $q1         = mysqli_query($koneksi, $sql1);
    $r1         = mysqli_fetch_array($q1);
    if ($r1) {
        $Nama           = $r1['Nama'];
        $Pesanan        = $r1['Pesanan'];
        $NoMeja         = $r1['No Meja']; // Pastikan di DB namanya 'No Meja'
        $JumlahPesanan  = $r1['Jumlah Pesanan']; // Pastikan di DB namanya 'Jumlah Pesanan'
        $Tanggal        = $r1['Tanggal'];
        $Status         = $r1['Status'];
    } else {
        $error = "Data tidak ditemukan";
    }
}

// Operasi Simpan (Create & Update)
if (isset($_POST['simpan'])) {
    $Nama           = $_POST['Nama'];
    $Pesanan        = $_POST['Pesanan'];
    $NoMeja         = $_POST['NoMeja']; // Mengambil dari name="NoMeja"
    $JumlahPesanan  = $_POST['JumlahPesanan']; // Mengambil dari name="JumlahPesanan"
    $Tanggal        = $_POST['Tanggal'];
    $Status         = $_POST['Status'];

    if ($Nama && $Pesanan && $NoMeja && $JumlahPesanan && $Tanggal && $Status) {
        if ($op == 'edit') { // Update data
            // Gunakan tanda backtick (`) untuk nama kolom yang ada spasinya
            $sql1 = "UPDATE pesanan SET Nama='$Nama', Pesanan='$Pesanan', `No Meja`='$NoMeja', `Jumlah Pesanan`='$JumlahPesanan', Tanggal='$Tanggal', Status='$Status' WHERE Id='$Id'";
            $q1   = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error  = "Data gagal diupdate: " . mysqli_error($koneksi);
            }
        } else { // Insert data baru
            $sql1 = "INSERT INTO pesanan (Nama, Pesanan, `No Meja`, `Jumlah Pesanan`, Tanggal, Status) VALUES ('$Nama', '$Pesanan', '$NoMeja', '$JumlahPesanan', '$Tanggal', '$Status')";
            $q1   = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Berhasil memasukkan data baru";
            } else {
                $error  = "Gagal memasukkan data: " . mysqli_error($koneksi);
            }
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
    <title>Warung 26 Ilir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .mx-auto { width: 1150px; }
        .card { margin-top: 20px; }
        .bg-dark { background-color: #212529 !important; }
    </style>
</head>
<body style="padding-top: 70px;">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="#">Muhammad Faisal</a>
        <div class="collapse navbar-collapse" id="navbarText">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="../index.html">HOME</a></li>
            <li class="nav-item"><a class="nav-link" href="../menu.html">DAFTAR MENU</a></li>
            <li class="nav-item"><a class="nav-link active" href="pesanan.php">PESANAN</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="mx-auto">
        <div class="card">
            <div class="card-header">Create / Edit Data</div>
            <div class="card-body">
                <?php if ($error) { ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $error ?>
    </div>
    <script>setTimeout(function(){ window.location.href='pesanan.php'; }, 3000);</script>
    <?php } ?>

    <?php if ($sukses) { ?>
        <div class="alert alert-success" role="alert">
        <?php echo $sukses ?>
        </div>
        <script>setTimeout(function(){ window.location.href='pesanan.php'; }, 3000);</script>
    <?php } ?>
                
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label class="col-sm-2">Nama</label>
                        <div class="col-sm-10"><input type="text" class="form-control" name="Nama" value="<?php echo $Nama ?>"></div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2">Pesanan</label>
                        <div class="col-sm-10"><input type="text" class="form-control" name="Pesanan" value="<?php echo $Pesanan ?>"></div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2">No. Meja</label>
                        <div class="col-sm-10"><input type="text" class="form-control" name="NoMeja" value="<?php echo $NoMeja ?>"></div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2">Jumlah Pesanan</label>
                        <div class="col-sm-10"><input type="number" class="form-control" name="JumlahPesanan" value="<?php echo $JumlahPesanan ?>"></div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2">Tanggal</label>
                        <div class="col-sm-10"><input type="date" class="form-control" name="Tanggal" value="<?php echo $Tanggal ?>"></div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2">Status</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="Status">
                                <option value="">- Pilih Status -</option>
                                <option value="Lunas" <?php if($Status=="Lunas") echo "selected"?>>Lunas</option>
                                <option value="Belum Bayar" <?php if($Status=="Belum Bayar") echo "selected"?>>Belum Bayar</option>
                            </select>
                        </div>
                    </div>
                    <input type="submit" name="simpan" value="Save" class="btn btn-primary">
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header text-white bg-secondary">Data Pesanan</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Pesanan</th>
                            <th>No.Meja</th>
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
                                <a href="pesanan.php?op=edit&Id=<?php echo $r2['Id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="pesanan.php?op=delete&Id=<?php echo $r2['Id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')">Hapus</a>
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