<?php 
session_start();

if(!isset($_SESSION["login"])){
  header("Location: index.php");
  exit;
}else if($_SESSION["role"] != "pasien"){
  header("Location: index.php");
  exit;
}
  require 'functions.php';

//query data 
$cariidpasien= query("SELECT id FROM pasien WHERE user_id = $_SESSION[id]")[0]["id"];

$detailpoli= query("SELECT * FROM poli");

$selected_poli = isset($_POST['id_poli']) ? $_POST['id_poli'] : '';
$selected_dokter = isset($_POST['id_dokter']) ? $_POST['id_dokter'] : '';
$detaildokter = [];
$detailjadwal = [];

if(!empty($selected_poli)){
  
  $detaildokter = query("SELECT * FROM dokter WHERE id_poli = $selected_poli");
}
if(!empty($selected_dokter)){
  $detailjadwal = query("SELECT * FROM jadwal_periksa WHERE id_dokter = $selected_dokter AND status = 'Aktif'");
}


//cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submitForm"])){    
  //cek apakah data berhasil diubah
  if(tambahDaftarPoli($_POST) > 0 ){
    echo "
    <script>
    alert('data berhasil ditambah');
    document.location.href = 'dashboardpasien.php';
    </script>
    ";
  }else{
    echo "
    <script>
    alert('data gagal ditambah');
    document.location.href = 'dashboardpasien.php';
    </script>
    ";
  }   

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ubah Data Dokter</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</head>
<body>
  
  <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Form Ubah Data Dokter</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="" method="post">
                <input type="hidden" name="id_pasien" value="<?= $cariidpasien; ?>">
                <div class="card-body">
                                
                 
                  <div class="form-group">
                  <label for="exampleSelectRounded0">Pilih Poli</label>
                    <select name = "id_poli" class="custom-select rounded" id="exampleSelectRounded0" onchange="this.form.submit()">
                    <option value="" >Pilih Poli</option>
                    <?php foreach($detailpoli as $poli) : ?>          
                      
                    <option  value="<?= $poli['id']; ?>" <?= $poli['id'] == $selected_poli ? 'selected' : ''; ?>><?= $poli["nama_poli"]; ?></option>
                    
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="exampleSelectRounded0">Pilih Dokter</label>
                    <select name = "id_dokter" class="custom-select rounded" id="exampleSelectRounded0" onchange="this.form.submit()">
                    <option value="" >Pilih Dokter</option>
                    <?php foreach($detaildokter as $dokter) : ?>          
                      
                    <option  value="<?= $dokter['id']; ?>" <?= $dokter['id'] == $selected_dokter ? 'selected' : ''; ?>><?= $dokter["nama"]; ?></option>
                    
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="exampleSelectRounded0" >Pilih Jadwal</label>
                    <select name = "id_jadwal" class="custom-select rounded" id="exampleSelectRounded0" >
                    <option value="" >Pilih Jadwal</option>
                    <?php foreach($detailjadwal as $jadwal) : ?>          
                     
                    <option  value="<?= $jadwal['id']; ?>"><?= $jadwal["hari"]; ?>  -  <?=$jadwal["jam_mulai"]; ?>  -  <?=$jadwal["jam_selesai"];?> </option>
                    
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword2">Keluhan</label>
                    <input type= "text" name="keluhan" class="form-control" id="exampleInputPassword2" placeholder="Masukkan Keluhan" >
                  </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary" name="submitForm">Submit</button>
                </div>
              </form>
            </div>
  
           
 
</body>
</html>