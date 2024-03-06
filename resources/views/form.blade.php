<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body style="margin: 10vh">
    <div>
        <p>PT PERKEBUNAN NUSANTARA IV</p>
        <p><b>Regional V</b></p>
        <h2 style="text-align: center;"><u>M E M O R A N D U M</u></h2>
    </div>
    <div>
        <table style="margin: auto;">
            <tr>
                <td>Kepada</td>
                <td>:</td>
                <td>Kepala Bagian SDM & Sistem Management</td>
            </tr>
            <tr>
                <td>Dari</td>
                <td>:</td>
                <td>{{ $karyawan->nama }}</td>
            </tr>
            <tr>
                <td>Nomor</td>
                <td>:</td>
                <td>lst</td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>-</td>
            </tr>
        </table>
        <hr>
    </div>
    <div>
        <table>
            <tr align="start">
                <td>Hal</td>
                <td>:</td>
                <td><b>Permohonan Cuti</b></td>
            </tr>
        </table>
        <p>Mohon persetujuan Bapak untuk melaksanakan cuti sebagai berikut:</p>
        <div style="padding-left: 5%">
            <p><b>1. Tanpa Pembayaran</b></p>
            <table>
                <tr>
                    <td>1.1.</td>
                    <td>Tgl. {{ date('d F Y', strtotime($permintaanCuti->tanggal_mulai)) }} s.d
                        {{ date('d F Y', strtotime($permintaanCuti->tanggal_selesai)) }}
                        <span>&nbsp;</span>
                    </td>
                    <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td>
                    <td>=</td>
                    <td>{{ $permintaanCuti->jumlah_hari_cuti }}</td>
                    <td>HK</td>
                </tr>
                <tr>
                    <td>1.2.</td>
                    <td>Sisa cuti yang dapat diambil</td>
                    <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>- Cuti Tahunan 2022/2023</td>
                    <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td>
                    <td>=</td>
                    <td>{{ $cutiTahunanDijalani }}</td>
                    <td>HK</td>
                </tr>
                <tr>
                    <td></td>
                    <td>- Cuti Panjang 2016/2022</td>
                    <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td>
                    <td>=</td>
                    <td>{{ $cutiPanjangDijalani }}</td>
                    <td>HK</td>
                </tr>
                <tr>
                    <td>1.3.</td>
                    <td>Sisa cuti setelah/dijalani</td>
                    <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>- Cuti Tahunan 2022/2023</td>
                    <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td>
                    <td>=</td>
                    <td>{{ $sisaCutiTahunan }}</td>
                    <td>HK</td>
                </tr>
                <tr>
                    <td></td>
                    <td>- Cuti Panjang 2016/2022</td>
                    <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td>
                    <td>=</td>
                    <td>{{ $sisaCutiPanjang }}</td>
                    <td>HK</td>
                </tr>
            </table>
            <p><b>2. Dengan Pembayaran</b></p>
            <table>
                <tr>
                    <td>2.1.</td>
                    <td>Tanggal.................................</td>
                    <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td>
                    <td>=</td>
                    <td>-</td>
                    <td>HK</td>
                </tr>
                <tr>
                    <td>2.2.</td>
                    <td>Sisa cuti yang dapat diambil</td>
                    <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>- Cuti Tahunan......................</td>
                    <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td>
                    <td>=</td>
                    <td>-</td>
                    <td>HK</td>
                </tr>
                <tr>
                    <td></td>
                    <td>- Cuti Panjang.......................</td>
                    <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td>
                    <td>=</td>
                    <td>-</td>
                    <td>HK</td>
                </tr>
                <tr>
                    <td>2.3.</td>
                    <td>Sisa cuti setelah/dijalani</td>
                    <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>- Cuti Tahunan......................</td>
                    <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td>
                    <td>=</td>
                    <td>-</td>
                    <td>HK</td>
                </tr>
                <tr>
                    <td></td>
                    <td>- Cuti Panjang.......................</td>
                    <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td>
                    <td>=</td>
                    <td>-</td>
                    <td>HK</td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>Alasan cuti</td>
                    <td>:</td>
                    <td>{{ $permintaanCuti->alasan }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $permintaanCuti->alamat }}</td>
                </tr>
            </table>
        </div>
        <p>Demikian permohonan kami, atas persetujuan Bapak, di ucapkan terima kasih.</p>
        <div>
            <table align="center" style="text-align: center;">
                <tr>
                    <td>Disetujui oleh</td>
                    <td><br></td>
                    <td>Pontianak, {{ date('d F Y', strtotime($permintaanCuti->updated_at)) }}</td>
                </tr>
                <tr>
                    <td>Bagian SDM Sistem Magajemen</td>
                    <td><br></td>
                    <td>Pemohon</td>
                </tr>
                <tr>
                    <td>
                        {{-- <img src="{{ asset('assets/images/avatars/approved.png') }}"> --}}
                        <img src="{{ public_path() . '/assets/images/avatars/approved.png' }}" alt=""
                            width="50%">
                    </td>
                    <td><br></td>
                </tr>
                <tr>
                    <td><b><u>{{ $namaAtasan }}</u></b></td>
                    <td></td>
                </tr>
                <tr>
                    <td>{{ $jabatan }}</td>
                    <td style="color: transparent;">{{ $namaAtasan }}</td>
                    <td><b><u>{{ $karyawan->nama }}</u></b></td>
                </tr>
            </table>
        </div>
    </div>
</body>
