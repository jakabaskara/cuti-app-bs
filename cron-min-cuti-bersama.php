<!-- mengecek apakah hari ini cuti bersama, jika iya maka kurangi cuti tahunan sebanyak 1,
 jika cuti tahunan tersia 0 cek apakah ada cuti panjang,, jika ada maka kurangi dari cuti panjang,
 jika tidak terdapat 2 2 nya maka kurangi sehingga bisa menjadi mines -->

 <?php

$tanggalHariIni = date('Y-m-d');
$servername = "io";
$username = "";
$password = 'x$';
$dbname = "";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT * FROM karyawan WHERE id NOT IN (SELECT id_karyawan FROM permintaan_cuti WHERE tanggal_mulai <= '$tanggalHariIni' AND tanggal_selesai >= '$tanggalHariIni' AND is_approved = 0)";
$result = $conn->query($sql);

// Array untuk menyimpan nama karyawan yang cutinya dikurangi
$karyawanYangDikurangi = array();



// URL yang akan diambil datanya
$url = 'https://cuti.reg5palmco.com/cuti-bersama';

// Mengambil data dari URL
$response = file_get_contents($url);

// Mengonversi respons JSON menjadi array PHP
$data = json_decode($response, true);

// Menampilkan hasil
$dataCutiBersama = $data;


// Periksa apakah tanggal hari ini ada di dalam array data cuti bersama dan apakah hari ini merupakan hari cuti bersama
if (array_key_exists($tanggalHariIni, $dataCutiBersama) && $dataCutiBersama[$tanggalHariIni]['holiday'] === true) {
    // Seluruh Karyawan yang tidak terdapat di permintaan_cuti
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $idKaryawan = $row["id"];
            $namaKaryawan = $row["nama"];
            $karyawanYangDikurangi[] = $namaKaryawan;

            // Cari Cuti Tahunan
            $sqlSearchSisaCuti = "SELECT * FROM sisa_cuti WHERE id_karyawan = '$idKaryawan' AND id_jenis_cuti = 2 LIMIT 1";
            $searchSisaCuti = $conn->query($sqlSearchSisaCuti);
            // Jika terdapat Cuti Tahunan
            if ($searchSisaCuti->num_rows > 0) {
                while ($rowSearchSisaCuti = $searchSisaCuti->fetch_assoc()) {
                    $id = $rowSearchSisaCuti['id'];
                    // Jika Cuti Tahunan Tidak 0
                    if ($rowSearchSisaCuti['jumlah'] > 0) {
                        // Update, kurangi cuti sebanyak 1
                        $conn->query("UPDATE sisa_cuti SET jumlah = jumlah - 1 WHERE id = '$id'");
                    } else {
                        // Jika Cuti Tahunan kurang dari 0 atau mines
                        // Cari Cuti Panjang
                        $sisaCutiPanjang = $conn->query("SELECT * FROM sisa_cuti WHERE id_karyawan = '$idKaryawan' AND id_jenis_cuti = 1 LIMIT 1");
                        if ($sisaCutiPanjang->num_rows > 0) {
                            while ($rowSisaCutiPanjang = $sisaCutiPanjang->fetch_assoc()) {
                                // Jika Cuti Panjang tidak 0
                                if ($rowSisaCutiPanjang['jumlah'] > 0) {
                                    $id2 = $rowSisaCutiPanjang['id'];
                                    // Kurangi cuti panjang sebanyak 1
                                    $conn->query("UPDATE sisa_cuti SET jumlah = jumlah - 1 WHERE id = $id2");
                                } else {
                                    // Jika Cuti Panjang kurang dari atau sama dengan 0, maka cuti tahunan yang dikurangi (Mines)
                                    $conn->query("UPDATE sisa_cuti SET jumlah = jumlah - 1 WHERE id = $id");
                                }
                            }
                        }
                    }
                }
            }
            $sqlKurangiCuti = "UPDATE sisa_cuti SET jumlah = jumlah - 1 WHERE id_karyawan = '$idKaryawan' AND id_jenis_cuti = '2'";
            $sqlCatat = "INSERT INTO karyawan_cuti_bersama (id_karyawan, tanggal, created_at) VALUES ('$idKaryawan', '$tanggalHariIni', NOW())";
            // $conn->query($sqlKurangiCuti);
            $conn->query($sqlCatat);
        }
    } else {
        die("0 hasil");
    }

    // echo "Hari ini adalah cuti bersama.";
} else {
    die("Hari ini bukan cuti bersama.");
}
