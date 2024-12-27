<?php 
$conn = mysqli_connect("localhost","root","","poli_db");


function query($query){
  global $conn;
  $result = mysqli_query($conn,$query);
  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}
return $rows;
}
function hapusPoli($id){
  global $conn;
  mysqli_query($conn, "DELETE FROM poli WHERE id = $id");
  return mysqli_affected_rows($conn);
}

function hapusJadwalPeriksa($id){
  global $conn;
  mysqli_query($conn, "DELETE FROM jadwal_periksa WHERE id = $id");
  return mysqli_affected_rows($conn);
}

function hapusDokter($id){
  global $conn;
  $query_user = "SELECT user_id FROM dokter WHERE id = $id";
  $user_result = query($query_user);
  $user_id = $user_result[0]["user_id"];
  mysqli_query($conn, "DELETE FROM user WHERE id = $user_id");
  // mysqli_query($conn, "DELETE FROM dokter WHERE id = $id");
  return mysqli_affected_rows($conn);
}
function hapusPasien($id){
  global $conn;
  $query_user = "SELECT user_id FROM pasien WHERE id = $id";
  $user_result = query($query_user);
  $user_id = $user_result[0]["user_id"];
  mysqli_query($conn, "DELETE FROM user WHERE id = $user_id");
  // mysqli_query($conn, "DELETE FROM pasien WHERE id = $id");
  return mysqli_affected_rows($conn);
}
function hapusObat($id){
  global $conn;
  mysqli_query($conn, "DELETE FROM obat WHERE id = $id");
  return mysqli_affected_rows($conn);
}

function ubahObat($data){
  global $conn;
  $nama = htmlspecialchars($data["nama"]);
  $kemasan = htmlspecialchars($data["kemasan"]);
  $harga = htmlspecialchars($data["harga"]);
  
 

  $query = "UPDATE obat SET nama_obat = '$nama', kemasan = '$kemasan', harga = '$harga' WHERE id = $data[id]"; 
  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}
function ubahPoli($data){
  global $conn;
  $nama = htmlspecialchars($data["nama"]);
  $keterangan = htmlspecialchars($data["keterangan"]);
  

  $query = "UPDATE poli SET nama_poli = '$nama', keterangan = '$keterangan' WHERE id = $data[id]"; 
  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}

function ubahJadwalPeriksa($data){
  global $conn;
  $hari = htmlspecialchars($data["hari"]);
  $jam_mulai = htmlspecialchars($data["jam_mulai"]);
  $jam_selesai = htmlspecialchars($data["jam_selesai"]);
  $status = htmlspecialchars($data["status"]);
  $id_dokter = query("SELECT id FROM dokter WHERE user_id = $_SESSION[id]")[0]["id"];

  $result =mysqli_query($conn,"SELECT * FROM jadwal_periksa 
              WHERE id_dokter = $id_dokter 
              AND id != $data[id]
              AND status_jadwal = 'Aktif' " );
  if($status == "Aktif"){
    if(mysqli_fetch_assoc($result)){
      echo "<script>
      alert('Hanya boleh satu jadwal yang aktif');
      </script>";
      return false;
    }
  }
  
  $query = "UPDATE jadwal_periksa SET hari = '$hari', jam_mulai = '$jam_mulai', jam_selesai = '$jam_selesai', status_jadwal = '$status' WHERE id = $data[id]"; 
  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}

function ubahPasien($data){
  global $conn;
  $nama = htmlspecialchars($data["nama"]);
  $alamat = htmlspecialchars($data["alamat"]);
  $nomorktp = htmlspecialchars($data["no_ktp"]);
  $nohp = htmlspecialchars($data["no_hp"]);
  $norm = htmlspecialchars($data["no_rm"]);

  $query = "UPDATE pasien SET nama = '$nama', alamat = '$alamat', no_ktp = '$nomorktp', no_hp = '$nohp', no_rm = '$norm' WHERE id = $data[id]"; 
  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}

function ubahDokter($data){
  global $conn;
  $nama = htmlspecialchars($data["nama_dokter"]);
  $alamat = htmlspecialchars($data["alamat"]);
  $no_hp = htmlspecialchars($data["no_hp"]);
  $id_poli = htmlspecialchars($data["id_poli"]);
 

  $query = "UPDATE dokter SET nama = '$nama', alamat = '$alamat', no_hp = '$no_hp', id_poli = '$id_poli' WHERE id = $data[dokter_id]"; 
  mysqli_query($conn, $query);
  
  return mysqli_affected_rows($conn);
}


function ubahProfilDokter($data){
  global $conn;

  $nama = htmlspecialchars($data["nama_dokter"]);
  $alamat = htmlspecialchars($data["alamat"]);
  $id_poli = htmlspecialchars($data["id_poli"]);
 
  $query_user = "UPDATE user SET username = '$nama', password = '$alamat' WHERE id = $data[user_id]";
  mysqli_query($conn, $query_user);
  $query = "UPDATE dokter SET nama = '$nama', alamat = '$alamat', id_poli = '$id_poli' WHERE id = $data[dokter_id]"; 
  mysqli_query($conn, $query);
   
  return mysqli_affected_rows($conn);
}



function registrasi($data){
  global $conn;

  $nama = htmlspecialchars($data["nama"]);
  $alamat = htmlspecialchars($data["alamat"]); 
  $nomorktp = htmlspecialchars($data["ktp"]); 
  $nomortelepon = htmlspecialchars($data["telepon"]);

  //cek username sudah ada atau belum
  $result =mysqli_query($conn, "SELECT nama FROM pasien WHERE no_ktp = '$nomorktp'");
  if(mysqli_fetch_assoc($result)){
    echo "<script>
    alert('nomor ktp sudah terdaftar');
    </script>";
    return false;
  }
  $tahunbulan = date('Ym'); 
  // substring(string, start, length)
  $no_rm_query = "SELECT MAX(CAST(SUBSTRING_INDEX(no_rm, '-', -1) AS UNSIGNED)) AS max_queue FROM pasien WHERE no_rm LIKE '$tahunbulan-%'";
  $result = query($no_rm_query);
  $row = $result[0];

  $maxAntrian = isset($row['max_queue']) ? (int)$row['max_queue'] : 0;
  //str_pad(input, pad_length, pad_string, pad_type)
  $nextAntrian = str_pad($maxAntrian + 1, 3, "0", STR_PAD_LEFT);
  $no_rm = $tahunbulan . "-" . $nextAntrian;

//tambahkan user baru ke database
$query_user= "INSERT INTO user VALUES('', '$nama', '$alamat','pasien')";
mysqli_query($conn, $query_user);
$id_user = mysqli_insert_id($conn);
mysqli_query($conn, "INSERT INTO pasien VALUES('', '$nama', '$alamat', '$nomorktp', '$nomortelepon', '$no_rm','$id_user')");
return mysqli_affected_rows($conn);
}

function tambahDaftarPoli($data){
  global $conn;

  $id_pasien = htmlspecialchars($data["id_pasien"]);
  $id_jadwal = htmlspecialchars($data["id_jadwal"]); 
  $keluhan = htmlspecialchars($data["keluhan"]); 
  
  $no_antrian_query = "SELECT MAX(CAST(no_antrian AS UNSIGNED)) AS max_queue FROM daftar_poli WHERE id_jadwal = $id_jadwal";
  $result = query($no_antrian_query)[0];
  

  $maxAntrian = isset($row['max_queue']) ? (int)$row['max_queue'] : 0;
  //str_pad(input, pad_length, pad_string, pad_type)
  $nextAntrian = $maxAntrian + 1;
  

//tambahkan user baru ke database

mysqli_query($conn, "INSERT INTO daftar_poli VALUES('', '$id_pasien', '$id_jadwal', '$keluhan', '$nextAntrian')");
return mysqli_affected_rows($conn);
}

function tambahDokter($data){
  global $conn;

  $nama = htmlspecialchars($data["nama"]);
  $alamat = htmlspecialchars($data["alamat"]); 
  $nomorhp = htmlspecialchars($data["no_hp"]); 
  $id_poli = htmlspecialchars($data["id_poli"]);

  //cek username sudah ada atau belum
  $result =mysqli_query($conn, "SELECT nama FROM dokter WHERE nama = '$nama'");
  if(mysqli_fetch_assoc($result)){
    echo "<script>
    alert('username sudah terdaftar');
    </script>";
    return false;
  }  
 

//tambahkan user baru ke database
$query_user= "INSERT INTO user VALUES('', '$nama', '$alamat','dokter')";
mysqli_query($conn, $query_user);
$id_user = mysqli_insert_id($conn);
mysqli_query($conn, "INSERT INTO dokter VALUES('', '$nama', '$alamat', '$nomorhp', '$id_poli','$id_user')");
return mysqli_affected_rows($conn);
}
function tambahObat($data){
  global $conn;

  $nama = htmlspecialchars($data["nama"]);
  $kemasan = htmlspecialchars($data["kemasan"]); 
  $harga = htmlspecialchars($data["harga"]); 
  

  //cek username sudah ada atau belum
  $result =mysqli_query($conn, "SELECT nama_obat FROM obat WHERE nama_obat = '$nama'");
  if(mysqli_fetch_assoc($result)){
    echo "<script>
    alert('nama obat sudah terdaftar');
    </script>";
    return false;
  }
 

//tambahkan user baru ke database
mysqli_query($conn, "INSERT INTO obat VALUES('', '$nama', '$kemasan', '$harga')");
return mysqli_affected_rows($conn);
}
function tambahPoli($data){
  global $conn;

  $nama = htmlspecialchars($data["nama"]);
  $keterangan = htmlspecialchars($data["keterangan"]); 
  
  

  //cek username sudah ada atau belum
  $result =mysqli_query($conn, "SELECT nama_poli FROM poli WHERE nama_poli = '$nama'");
  if(mysqli_fetch_assoc($result)){
    echo "<script>
    alert('nama poli sudah terdaftar');
    </script>";
    return false;
  }
 

//tambahkan user baru ke database
mysqli_query($conn, "INSERT INTO poli VALUES('', '$nama', '$keterangan')");
return mysqli_affected_rows($conn);
}
function tambahJadwalPeriksa($data){
  global $conn;
  $id_dokter = query("SELECT id FROM dokter WHERE user_id = $data[id_dokter]")[0]["id"];
  $hari = htmlspecialchars($data["hari"]);
  $jam_mulai = htmlspecialchars($data["jam_mulai"]);
  $jam_selesai = htmlspecialchars($data["jam_selesai"]);
  
  //cek username sudah ada atau belum
  $result =mysqli_query($conn,"SELECT * FROM jadwal_periksa 
              WHERE id_dokter = $id_dokter 
              AND hari = '$hari' 
              AND (
                  ('$jam_mulai' BETWEEN jam_mulai AND jam_selesai) OR 
                  ('$jam_selesai' BETWEEN jam_mulai AND jam_selesai)                  
              )" );
  if(mysqli_fetch_assoc($result)){
    echo "<script>
    alert('Jadwal bertabrakan');
    </script>";
    return false;
  }
 

//tambahkan user baru ke database
mysqli_query($conn, "INSERT INTO jadwal_periksa VALUES('', '$id_dokter', '$hari', '$jam_mulai', '$jam_selesai', 'Tidak Aktif')");
return mysqli_affected_rows($conn);
}


?>

