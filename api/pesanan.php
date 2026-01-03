<?php
$host       = "gateway01.ap-southeast-1.prod.aws.tidbcloud.com";
$user       = "3QSp3qi6JdHmEaT.root";
$pass       = "89bsOyEj454DU0tq";
$db         = "test";
$port       = "4000";

// Menggunakan mysqli dengan mode objek agar SSL lebih stabil
$koneksi = mysqli_init();

// Tambahkan opsi SSL secara spesifik
$koneksi->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);

// Lakukan koneksi
$success = $koneksi->real_connect($host, $user, $pass, $db, $port, NULL, MYSQLI_CLIENT_SSL);

if (!$success) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// COBA TEST QUERY (Hapus ini jika sudah berhasil tampil)
$result = $koneksi->query("SELECT * FROM pesanan");
if ($result) {
    echo "<h1>Koneksi Berhasil! Data ditemukan:</h1>";
    while($row = $result->fetch_assoc()) {
        echo "Pesanan: " . $row['Pesanan'] . "<br>"; // Sesuaikan nama kolom tabel kamu
    }
} else {
    echo "Koneksi sukses, tapi gagal ambil data: " . $koneksi->error;


}
$Nama            = "";
$Pesanan         = "";
$NoMeja          = "";
$JumlahPesanan   = "";
$Tanggal         = "";
$Status          = "";
$sukses         = "";
$error          = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if($op == 'delete'){
    $Id         = $_GET['Id'];
    $sql1       = "delete from pesanan where Id = '$Id'";
    $q1         = mysqli_query($koneksi,$sql1);
    if($q1){
        $sukses = "Berhasil hapus data";
    }else{
        $error  = "Gagal melakukan delete data";
    }
}
if ($op == 'edit') {
    $Id         = $_GET['Id'];
    $sql1       = "select*from pesanan WHERE Id = '$Id'";
    $q1         = mysqli_query($koneksi, $sql1);
    $r1         = mysqli_fetch_array($q1);
    $Nama        = $r1['Nama'];
    $Pesanan       = $r1['Pesanan'];
    $NoMeja     = $r1['No Meja'];
    $JumlahPesanan = $r1['Jumlah Pesanan'];
    $Tanggal    = $r1['Tanggal'];
    $Status   = $r1['Status'];

    if ($Nama == '') {
        $error = "Data tidak ditemukan";
    }
}
if (isset($_POST['simpan'])) { //untuk create
    $Nama           = $_POST['Nama'];
    $Pesanan        = $_POST['Pesanan'];
    $NoMeja         = $_POST['No Meja'];
    $JumlahPesanan  = $_POST['Jumlah Pesanan'];
    $Tanggal        = $_POST['Tanggal'];
    $Status         = $_POST['Status'];

    if ($Nama && $Pesanan && $NoMeja && $JumlahPesanan && $Tanggal && $Status) {
        if ($op == 'edit') { //untuk update
            $sql1       = "update pesanan set Nama ='$Nama',Pesanan='$Pesanan',No Meja='$NoMeja',Jumlah Pesanan='$JumlahPesanan',Tanggal='$Tanggal',Status='$Status' WHERE Id='$Id'";
            $q1         = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error  = "Data gagal diupdate";
            }
        } else { //untuk insert
            $sql1   = "insert into pesanan(Nama,Pesanan,No Meja,Jumlah Pesanan,Tanggal,Status) values ('$Nama','$Pesanan','$NoMeja','$JumlahPesanan','$Tanggal','$Status')";
            $q1     = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses     = "Berhasil memasukkan data baru";
            } else {
                $error      = "Gagal memasukkan data";
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung 26 Ilir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="menu.css" />
<style>
.mx-auto {
     width: 1150px;
}

.card {
    margin-top: 20px;
}
.navbar .navbar-nav .nav-link {
	color: white;
}

.navbar .navbar-nav .nav-link:hover {
	color: red;
}
.navbar .navbar-brand  {
	font-size: block;
    nav-left: auto;
}
.navbar #navbarText{
    font-size: block;
  }
    </style>
</head>

<body>
    <!-- navigasi -->
    <nav
      class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg fixed-top"
    >
      <div class="container">
        <a class="navbar-brand" href="#">Muhammad Faisal</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarText"
          aria-controls="navbarText"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse text-right" id="navbarText">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" href="index.html">HOME</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="menu.html">DAFTAR MENU</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="pesanan.php">PESANAN</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.html#tentang">TENTANG</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.html#kontak">KONTAK KAMI</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <br>
    <!-- akhir nav -->
    <br><br>
    <div class="mx-auto">
        <!-- untuk memasukkan data -->
        <div class="card">
            <div class="card-header">
                Create / Edit Data
            </div>
            <div class="card-body">
                <?php
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                    header("refresh:5;url=pesanan.php");//5 : detik
                }
                ?>
                <?php
                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:5;url=pesanan.php");
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="Nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="Nama" name="Nama" value="<?php echo $Nama ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="Pesanan" class="col-sm-2 col-form-label">Pesanan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="Pesanan" name="Pesanan" value="<?php echo $Pesanan ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="No Meja" class="col-sm-2 col-form-label">No.Meja</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="No Meja" name="NoMeja" value="<?php echo $NoMeja ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="Jumlah Pesanan" class="col-sm-2 col-form-label">Jumlah Pesanan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="Jumlah Pesanan" name="JumlahPesanan" value="<?php echo $JumlahPesanan ?>"><br>
                        </div>
                        <div class="mb-3 row">
                        <label for="Tanggal" class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="Tanggal" name="Tanggal" value="<?php echo $Tanggal ?>">
                        </div>
                    </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="Status" class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="Status" id="Status">
                                <option value="">- Pilih Status -</option>
                                <option value="Lunas" <?php if ($Status == "Lunas") echo "selected" ?>>Lunas</option>
                                <option value="Belum Bayar" <?php if ($Status == "Belum Bayar") echo "selected" ?>>Belum Bayar</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                    <input type="submit" name="simpan" value="Save" class="btn btn-primary"  />
                    </div>
                </form>
            </div>
        </div>

        <!-- untuk mengeluarkan data -->
        <div class="card">
            <div class="card-header text-white bg-secondary">
                Data Pesanan
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col" style="text-align: center;">Nama</th>
                            <th scope="col" style="text-align: center;">Pesanan</th>
                            <th scope="col" style="text-align: center;" >No.Meja</th>
                            <th scope="col" style="text-align: center;">Jumlah Pesanan</th>
                            <th scope="col" style="text-align: center;">Tanggal</th>
                            <th scope="col" style="text-align: center;">Status</th>
                            <th scope="col" style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2   = "select*from pesanan order by Id desc";
                        $q2     = mysqli_query($koneksi, $sql2);
                        $urut   = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $Id             = $r2['Id'];
                            $Nama           = $r2['Nama'];
                            $Pesanan        = $r2['Pesanan'];
                            $NoMeja         = $r2['No Meja'];
                            $JumlahPesanan  = $r2['Jumlah Pesanan'];
                            $Tanggal        = $r2['Tanggal'];
                            $Status         = $r2['Status'];

                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row" style="text-align: center;"><?php echo $Nama ?></td>
                                <td scope="row" style="text-align: center;"><?php echo $Pesanan ?></td>
                                <td scope="row" style="text-align: center;"><?php echo $NoMeja ?></td>
                                <td scope="row" style="text-align: center;"><?php echo $JumlahPesanan ?></td>
                                <td scope="row" style="text-align: center;"><?php echo $Tanggal ?></td>
                                <td scope="row" style="text-align: center;"><?php echo $Status ?></td>
                                <td scope="row" style="text-align: center;">
                                    <a href="pesanan.php?op=edit&Id=<?php echo $Id ?>"><button type="button" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 
                                    0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 
                                    0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/></svg></button></a>
                                    <a href="pesanan.php?op=delete&Id=<?php echo $Id?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                    </svg></button></a>            
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>

    <!-- footer -->
    <footer>
    <div class="container text-center pt-5 pb-5">
        Copyright &copy; 2021 - Muhammad Faisal, All Rights Reserved.
    </div>
  </footer>
  <!-- akhir footer -->
</body>
</html>
