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
//query data 

$nama_pasien = query("SELECT nama FROM pasien WHERE id = $id_pasien")[0]["nama"];
$listobat = query("SELECT * FROM obat");

$biayadokter = 150000;
$totalHarga = $biayadokter;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (isset($_POST['obat']) && is_array($_POST['obat'])) {
      foreach ($_POST['obat'] as $idObat) {
          foreach ($listobat as $obat) {
              if ($obat['id'] == $idObat) {
                  $totalHarga += $obat['harga'];
              }
          }
      }
  }
}



//cek apakah tombol submit sudah ditekan atau belum
  if (isset($_POST["submitForm"])){    
    //cek apakah data berhasil diubah
    if(tambahHasilPeriksa($_POST) > 0 ){
      echo "
      <script>
      alert('data berhasil diubah');
      document.location.href = 'dashboarddokter.php';
      </script>
      ";
    }else{
      echo "
      <script>
      alert('data gagal diubah');
      document.location.href = 'dashboarddokter.php';
      </script>
      ";
    }   

  }
  date_default_timezone_set('Asia/Jakarta');
  $tanggal_periksa = $_POST['tanggal_periksa'] ?? date('Y-m-d\TH:i');
$catatan = $_POST['catatan'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Dashboard</title>
 <!-- Select2 -->
 <link rel="stylesheet" href="app/plugins/select2/css/select2.min.css">
 

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
    <!-- Content Header (Page header) -->
    
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Periksa Pasien</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="" method="post">
                <div class="card-body">
                  <div class="form-group">
                  <input type="hidden" name="id_daftar_poli" value="<?=$id_daftar_poli; ?>">
                    <label for="exampleInputEmail1">Nama Pasien</label>
                    <input value="<?= $id_pasien;?>" name="id_pasien" hidden>
                    <input type="text" class="form-control" id="exampleInputEmail1" value = "<?= $nama_pasien;?>" name="nama_pasien" readonly>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Tanggal Periksa</label>
                    <input type="datetime-local" class="form-control" id="exampleInputPassword1" name="tanggal_periksa" value="<?= $tanggal_periksa; ?>">
                  </div>
                  <div class="form-group">
                  
                    <label for="exampleInputEmail2">Catatan</label>
                    <input type="text" class="form-control" id="exampleInputEmail2"  name="catatan" value="<?= $catatan;?>">
                  </div>
                  <div class="form-group">
                  <label  >Obat</label>
                  <select class="select2" multiple="multiple" data-placeholder="Pilih Obat" style="width: 100%;" name="obat[]" onchange="this.form.submit()" >
                  <?php foreach ($listobat as $obat) : ?>
                  <option 
                    value="<?= $obat["id"]; ?>" 
                    <?= isset($_POST['obat']) && in_array($obat["id"], $_POST['obat']) ? 'selected' : ''; ?>
                  >
                    <?= $obat["nama_obat"]; ?> - <?= $obat["kemasan"]; ?> - Rp. <?= $obat["harga"]; ?>
                  </option>
                  <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group">
                  
                    <label for="exampleInputEmail2">Total Harga</label>
                    <input type="text" class="form-control" id="exampleInputEmail2" value = "<?= $totalHarga;?>" name="biaya_periksa" readonly>
                  </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary" name="submitForm">Submit</button>
                </div>
              </form>
            </div>
  
    <!-- /.content -->
  </div>
  

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>

<!-- ./wrapper -->
 <!-- jQuery -->
<script src="app/plugins/jquery/jquery.min.js"></script>
<!-- Select2 -->
<script src="app/plugins/select2/js/select2.full.min.js"></script>

<!-- jQuery UI 1.11.4 -->
<script src="app/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="app/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="app/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="app/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="app/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="app/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="app/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="app/plugins/moment/moment.min.js"></script>
<script src="app/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="app/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="app/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="app/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="app/dist/js/adminlte.js"></script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()
  })
</script>

</body>
</html>
