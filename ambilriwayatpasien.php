<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);

require 'functions.php'; 

if (isset($_GET['patient_id'])) {
    $patientId = intval($_GET['patient_id']); 

   
    $query = "
   SELECT 
        periksa.id AS periksa_id,
        periksa.tgl_periksa,
        pasien.nama AS patient_name,
        dokter.nama AS doctor_name,
        daftar_poli.keluhan,
        periksa.catatan,        
        periksa.biaya_periksa,
        GROUP_CONCAT(obat.nama_obat SEPARATOR ', ') AS nama_obat
    FROM periksa
    JOIN daftar_poli ON periksa.id_daftar_poli = daftar_poli.id
    JOIN pasien ON daftar_poli.id_pasien = pasien.id
    JOIN jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id
    JOIN dokter ON jadwal_periksa.id_dokter = dokter.id
    JOIN detail_periksa ON periksa.id = detail_periksa.id_periksa
    JOIN obat ON detail_periksa.id_obat = obat.id
    WHERE pasien.id = $patientId
    GROUP BY periksa.id
    
";
    $no = 1;
    $data = query($query);
    if ($data) {
        foreach ($data as $row) {
            echo "
                <tr>
                    <td>{$no}</td>
                    <td>{$row['tgl_periksa']}</td>
                    <td>{$row['patient_name']}</td>
                    <td>{$row['doctor_name']}</td>
                    <td>{$row['keluhan']}</td>
                    <td>{$row['catatan']}</td>
                    <td>{$row['nama_obat']}</td>
                    <td>{$row['biaya_periksa']}</td>
                </tr>
            ";
            $no++;
        }
    } else {
        echo "<tr><td colspan='7'>No records found.</td></tr>";
    }
} else {
    echo "No patient ID provided.";
}
?>
