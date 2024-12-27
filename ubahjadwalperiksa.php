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
//ambil data di url
$id = $_GET["id"];

//query data 
$detail= query("SELECT * FROM jadwal_periksa WHERE id = $id")[0];
// $detailpoli= query("SELECT * FROM poli");
// $defaultpoli= query("SELECT poli.id, poli.nama_poli FROM dokter JOIN poli ON dokter.id_poli = poli.id WHERE dokter.id = $id")[0];


//cek apakah tombol submit sudah ditekan atau belum
  if (isset($_POST["submit"])){    
    //cek apakah data berhasil diubah
    if(ubahJadwalPeriksa($_POST) > 0 ){
      echo "
      <script>
      alert('data berhasil diubah');
      document.location.href = 'jadwalperiksa.php';
      </script>
      ";
    }else{
      echo "
      <script>
      alert('data gagal diubah');
      document.location.href = 'jadwalperiksa.php';
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
                <h3 class="card-title">Form Ubah Jadwal Periksa</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="" method="post">
                <div class="card-body">
                <input type="text" name="id" value="<?= $detail["id"]; ?>" hidden>
                <div class="form-group">
                  <label for="exampleSelectRounded0">Hari</label>
                  <input type="text" name="hari" class="form-control"  value="<?= $detail["hari"]; ?>" readonly>
                </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Jam Mulai</label>
                    <input type="time" class="form-control" id="exampleInputPassword1"  name="jam_mulai" value="<?= $detail["jam_mulai"]; ?>" readonly>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword2">Jam Selesai</label>
                    <input type= "time" name="jam_selesai" class="form-control" id="exampleInputPassword2" value="<?= $detail["jam_selesai"]; ?>" readonly>
                  </div>
                  <div class="form-group">
                  <label for="exampleSelectRounded0">Status</label>
                    <select name = "status" class="custom-select rounded" id="exampleSelectRounded0">
                    <?php 
                    $status = ["Aktif", "Tidak Aktif"];
                    foreach($status as $stat) : ?>                    
                    <?php if($detail["status_jadwal"] == $stat) :?>                   
                    <option value = "<?=$stat;?>" selected><?= $stat;?></option>
                    <?php else : ?>
                      <option value = "<?=$stat;?>"><?= $stat;?></option>
                    <?php endif; ?>
                    <?php endforeach; ?>
                  </select>
                </div>
                 
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                </div>
              </form>
            </div>
  
    
 
</body>
</html>