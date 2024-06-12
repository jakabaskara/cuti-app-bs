<!-- mengecek apakah sudah masuk tanggal jatuh tempo, jika iya maka reset cuti, jika terdapat mines,
 maka kurangi dari mines tsb -->
<?php

$servername = "";
$username = "";
$password = 'x$';
$dbname = "";

// Koneksi ke database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mendapatkan tanggal hari ini
$today = date('Y-m-d');

// Mendapatkan cuti panjang yang sudah jatuh tempo
$cutiPanjangResult = $conn->query("SELECT * FROM sisa_cuti WHERE periode_akhir <= '$today' AND id_jenis_cuti = 1");

if ($cutiPanjangResult->num_rows > 0) {
    while ($rowCutiPanjang = $cutiPanjangResult->fetch_assoc()) {
        // Mengupdate cuti panjang
        $id = $rowCutiPanjang['id'];
        $periodeMulaiPanjang = date('Y-m-d', strtotime($rowCutiPanjang['periode_mulai'] . " +6 Years"));
        $periodeAkhirPanjang = date('Y-m-d', strtotime($rowCutiPanjang['periode_akhir'] . " +6 Years"));

        $conn->query("UPDATE sisa_cuti SET jumlah = 30, periode_mulai = '$periodeMulaiPanjang', periode_akhir = '$periodeAkhirPanjang' WHERE id = $id");

        // Logging perubahan
        $conn->query("INSERT INTO log_pengurangan_cuti (id_karyawan, sisa_cuti_awal, sisa_cuti_setelah, keterangan) VALUES($rowCutiPanjang[id_karyawan], $rowCutiPanjang[jumlah], '30', 'Cuti Panjang Diupdate Oleh Sistem')");
    }
}

// Mendapatkan cuti tahunan yang sudah jatuh tempo
$cutiTahunanResult = $conn->query("SELECT * FROM sisa_cuti WHERE periode_akhir <= '$today' AND id_jenis_cuti = 2");

if ($cutiTahunanResult->num_rows > 0) {
    while ($rowTahunan = $cutiTahunanResult->fetch_assoc()) {
        // Mengupdate cuti tahunan
        $id = $rowTahunan['id'];
        $periodeMulaiTahunan = date('Y-m-d', strtotime($rowTahunan['periode_mulai'] . " +1 Years"));
        $periodeAkhirTahunan = date('Y-m-d', strtotime($rowTahunan['periode_akhir'] . " +1 Years"));

        if ($rowTahunan['jumlah'] > 0) {
            $conn->query("UPDATE sisa_cuti SET jumlah = 12, periode_mulai = '$periodeMulaiTahunan', periode_akhir = '$periodeAkhirTahunan' WHERE id = $id");

            // Logging perubahan
            $conn->query("INSERT INTO log_pengurangan_cuti (id_karyawan, sisa_cuti_awal, sisa_cuti_setelah, keterangan) VALUES($rowTahunan[id_karyawan], $rowTahunan[jumlah], '12', 'Cuti Tahunan Diupdate Oleh Sistem')");
        } else {
            // Mengubah jumlah cuti tahunan jika kurang dari 0
            $jumlahTahunanBaru = $rowTahunan['jumlah'] + 12;

            if ($jumlahTahunanBaru < 0) {
                // Mengambil sisa cuti panjang karyawan
                $cutiPanjangResult = $conn->query("SELECT * FROM sisa_cuti WHERE id_karyawan = $rowTahunan[id_karyawan] AND id_jenis_cuti = 1");

                if ($cutiPanjangResult->num_rows > 0) {
                    $rowCutiPanjang = $cutiPanjangResult->fetch_assoc();
                    $jumlahCutiPanjang = abs($rowCutiPanjang['jumlah']); // Mengambil nilai absolut

                    if ($jumlahCutiPanjang >= abs($jumlahTahunanBaru)) {
                        // Jika sisa cuti panjang lebih banyak atau sama dengan cuti tahunan yang negatif, maka ubah cuti tahunan menjadi 0
                        $cutiBaru = $jumlahCutiPanjang + $jumlahTahunanBaru;
                        // Kurangi cuti panjang berdasarkan nilai absolut cuti tahunan yang negatif
                        $conn->query("UPDATE sisa_cuti SET jumlah = $cutiBaru WHERE id_karyawan = $rowTahunan[id_karyawan] AND id_jenis_cuti = 1");
                        $conn->query("INSERT INTO log_pengurangan_cuti (id_karyawan, sisa_cuti_awal, sisa_cuti_setelah, keterangan) VALUES($rowTahunan[id_karyawan], '$jumlahCutiPanjang', '$cutiBaru', 'Cuti Panjang Diupdate Oleh Sistem')");
                        $conn->query("UPDATE sisa_cuti SET jumlah = 0, periode_mulai = '$periodeMulaiTahunan', periode_akhir = '$periodeAkhirTahunan' WHERE id = $id");
                        $conn->query("INSERT INTO log_pengurangan_cuti (id_karyawan, sisa_cuti_awal, sisa_cuti_setelah, keterangan) VALUES($rowTahunan[id_karyawan], $rowTahunan[jumlah], '0', 'Cuti Tahunan Diupdate Oleh Sistem')");
                    } else {
                        $conn->query("UPDATE sisa_cuti SET jumlah = $jumlahTahunanBaru, periode_mulai = '$periodeMulaiTahunan', periode_akhir = '$periodeAkhirTahunan' WHERE id = $id");
                        $conn->query("INSERT INTO log_pengurangan_cuti (id_karyawan, sisa_cuti_awal, sisa_cuti_setelah, keterangan) VALUES($rowTahunan[id_karyawan], $rowTahunan[jumlah], '$jumlahTahunanBaru', 'Cuti Tahunan Diupdate Oleh Sistem')");
                    }
                }
            } else {
             // Update jumlah cuti tahunan dan logging perubahan
            $conn->query("UPDATE sisa_cuti SET jumlah = $jumlahTahunanBaru, periode_mulai = '$periodeMulaiTahunan', periode_akhir = '$periodeAkhirTahunan' WHERE id = $id");
            $conn->query("INSERT INTO log_pengurangan_cuti (id_karyawan, sisa_cuti_awal, sisa_cuti_setelah, keterangan) VALUES($rowTahunan[id_karyawan], $rowTahunan[jumlah], '$jumlahTahunanBaru', 'Cuti Tahunan Diupdate Oleh Sistem')");
            }
        }
    }
}

// Menutup koneksi
$conn->close();
