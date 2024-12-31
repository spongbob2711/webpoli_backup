<?php
session_start();

if(!isset($_SESSION["login"])){
  header("Location: index.php");
  exit;
}else if($_SESSION["role"] != "dokter"){
  header("Location: index.php");
  exit;
}

require 'functions.php';
$id_pasien = $_GET["id_pasien"];
$id_daftar_poli = $_GET["id_daftar_poli"];


$detailhasilperiksa = query("SELECT * FROM periksa WHERE id_daftar_poli = $id_daftar_poli")[0];
$id_periksa = $detailhasilperiksa["id"];
$listobatPasien = query("SELECT * FROM detail_periksa WHERE id_periksa = $id_periksa");
$selectedObatIds = array_column($listobatPasien, 'id_obat');
$nama_pasien = query("SELECT nama FROM pasien WHERE id = $id_pasien")[0]["nama"];
$listobat = query("SELECT * FROM obat");
$biayadokter = 150000;

$totalHarga = $biayadokter;
foreach ($listobatPasien as $detail) {
    $obat = query("SELECT harga FROM obat WHERE id = {$detail['id_obat']}")[0];
    $totalHarga += $obat['harga'];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (isset($_POST['submitForm'])) {
    if (ubahHasilPeriksa($_POST) > 0) {
      echo "<script>alert('Data berhasil diubah'); document.location.href = 'listperiksa.php';</script>";
    } else {
      echo "<script>alert('Data gagal diubah'); document.location.href = 'listperiksa.php';</script>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Hasil Periksa</title>
  <!-- Select2 -->
  <link rel="stylesheet" href="app/plugins/select2/css/select2.min.css">
  <!-- Bootstrap -->
  <link rel="stylesheet" href="app/plugins/bootstrap/css/bootstrap.min.css">
  <!-- AdminLTE style -->
  <link rel="stylesheet" href="app/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="app/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="app/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="app/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="app/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="app/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="app/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="app/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="app/plugins/summernote/summernote-bs4.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a href="logout.php" class="btn btn-danger" name="logout">Logout</a>
      </li>

      <!-- Messages Dropdown Menu -->
      
      <!-- Notifications Dropdown Menu -->
      
      
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="dashboard.php" class="brand-link">      
      <span class="brand-text font-weight-light">Web Poli</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
     

      

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               <li class="nav-item">
            <a href="dashboarddokter.php" class="nav-link">
              <i class="nav-icon far fa-user" style="color: #dbdbdb;"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
               <li class="nav-item">
            <a href="jadwalperiksa.php" class="nav-link">
              <i class="nav-icon far fa-user" style="color: #dbdbdb;"></i>
              <p>
                Jadwal Periksa
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="listperiksa.php" class="nav-link">
              <i class="nav-icon far fa-user" style="color: #dbdbdb;"></i>
              <p>
                Memeriksa Pasien
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="kelolaobat.php" class="nav-link">
              <i class="nav-icon far fa-user" style="color: #dbdbdb;"></i>
              <p>
                Riwayat Pasien
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="profildokter.php" class="nav-link">
              <i class="nav-icon far fa-user" style="color: #dbdbdb;"></i>
              <p>
                Profil
              </p>
            </a>
          </li>
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Edit Hasil Periksa</h3>
      </div>
      <form action="" method="post">
        <div class="card-body">
          <div class="form-group">
            <input type="hidden" name="id_daftar_poli" value="<?= $id_daftar_poli; ?>">
            <label>Nama Pasien</label>
            <input type="hidden" name="id_pasien" value="<?= $id_pasien; ?>">
            <input type="text" class="form-control" value="<?= $nama_pasien; ?>" readonly>
          </div>
          <div class="form-group">
            <label>Tanggal Periksa</label>
            <input type="datetime-local" class="form-control" name="tanggal_periksa" value="<?= $detailhasilperiksa['tgl_periksa']; ?>">
          </div>
          <div class="form-group">
            <label>Catatan</label>
            <input type="text" class="form-control" name="catatan">
          </div>
          <div class="form-group">
            <label>Obat</label>
            <select class="select2" multiple="multiple" data-placeholder="Pilih Obat" style="width: 100%;" name="obat[]" id="obatSelect">
              <?php foreach ($listobat as $obat) : ?>
                <option value="<?= $obat['id']; ?>" data-price="<?= $obat['harga']; ?>"
                  <?= in_array($obat['id'], $selectedObatIds) ? 'selected' : ''; ?>>
                  <?= $obat['nama_obat']; ?> - <?= $obat['kemasan']; ?> - Rp. <?= $obat['harga']; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Total Harga</label>
            <input type="text" class="form-control" id="totalHarga" value="<?= $totalHarga; ?>" name="biaya_periksa" readonly>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary" name="submitForm">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="app/plugins/jquery/jquery.min.js"></script>
<script src="app/plugins/select2/js/select2.full.min.js"></script>
<script>
  $(function () {
    $('.select2').select2();
  });

  document.addEventListener('DOMContentLoaded', function () {
    const obatSelect = document.getElementById('obatSelect');
    const totalHargaInput = document.getElementById('totalHarga');
    const basePrice = <?= $biayadokter; ?>;

    function updateTotalPrice() {
      let totalPrice = basePrice;
      const selectedOptions = Array.from(obatSelect.selectedOptions);

      selectedOptions.forEach(option => {
        const price = parseInt(option.getAttribute('data-price'), 10);
        totalPrice += price;
      });

      totalHargaInput.value = totalPrice;
    }

    $('#obatSelect').on('change', updateTotalPrice);

    updateTotalPrice(); 
  });
</script>
</body>
</html>
