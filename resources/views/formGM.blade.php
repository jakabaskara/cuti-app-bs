<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            transform: translateY(-25px);
        }
    </style>
</head>

<body style="margin: -5vh 5vh 0vh 10vh">
    <div>
        <p>PT PERKEBUNAN NUSANTARA IV</p>
        <p style="margin-top: -10px"><b>Regional V</b></p>
        <h2 style="text-align: center;"><u>M E M O R A N D U M</u></h2>
    </div>
    <div style="font-size:14px;">
        <table style="margin: auto;">
            <tr>
                <td>Kepada</td>
                <td>:</td>
                <td>{{ $jabatan }}</td>
            </tr>
            <tr>
                <td>Dari</td>
                <td>:</td>
                <td>{{ $karyawan->nama }}</td>
            </tr>
            <tr>
                <td>Nomor</td>
                <td>:</td>
                <td>{{ $permintaanCuti->id }}</td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>-</td>
            </tr>
        </table>
        <hr>
    </div>
    <div style="font-size:14px;">
        <table>
            <tr align="start" style="padding-bottom: -10px">
                <td>Hal</td>
                <td>:</td>
                <td><b>Permohonan Cuti</b></td>
            </tr>
        </table>
        <p>Mohon persetujuan Bapak/Ibu untuk melaksanakan cuti sebagai berikut:</p>
        <div style="padding-left: 5%">
            <p style="margin-top: -5px;"><b>1. Tanpa Pembayaran</b></p>
            <table style="margin-top: -15px;">
                <tr>
                    <td>1.1.</td>
                    <td>
                        Tgl.
                        {{ \Carbon\Carbon::parse($permintaanCuti->tanggal_mulai)->locale('id')->isoFormat('D MMMM YYYY') }}
                        s.d
                        {{ \Carbon\Carbon::parse($permintaanCuti->tanggal_selesai)->locale('id')->isoFormat('D MMMM YYYY') }}
                        <span>&nbsp;</span>
                    </td>
                    <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td>
                    <td>=</td>
                    <td>{{ $permintaanCuti->jumlah_cuti_tahunan + $permintaanCuti->jumlah_cuti_panjang }}</td>
                    <td>HK</td>
                </tr>
                <tr>
                    <td>1.2.</td>
                    <td>Sisa cuti yang dapat diambil</td>
                    <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>- Cuti Tahunan {{ $periode_cuti_tahunan }}</td>
                    <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td>
                    <td>=</td>
                    <td>{{ $cutiTahunanDijalani + $sisaCutiTahunan }}</td>
                    <td>HK</td>
                </tr>
                <tr>
                    <td></td>
                    <td>- Cuti Panjang {{ $periode_cuti_panjang }}</td>
                    <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td>
                    <td>=</td>
                    <td>{{ $cutiPanjangDijalani + $sisaCutiPanjang }}</td>
                    <td>HK</td>
                </tr>
                <tr>
                    <td>1.3.</td>
                    <td>Sisa cuti setelah/dijalani</td>
                    <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>- Cuti Tahunan {{ $periode_cuti_tahunan }}</td>
                    <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td>
                    <td>=</td>
                    <td>{{ $sisaCutiTahunan }}</td>
                    <td>HK</td>
                </tr>
                <tr>
                    <td></td>
                    <td>- Cuti Panjang {{ $periode_cuti_panjang }}</td>
                    <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td>
                    <td>=</td>
                    <td>{{ $sisaCutiPanjang }}</td>
                    <td>HK</td>
                </tr>
            </table>
            <p style="margin-top: 0px;"><b>2. Dengan Pembayaran</b></p>
            <table style="margin-top: -15px;">
                <tr>
                    <td>2.1.</td>
                    <td>Tanggal...............................................................................................................................................
                    </td>
                    {{-- <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td> --}}
                    <td>=</td>
                    <td>-</td>
                    <td>HK</td>
                </tr>
                <tr>
                    <td>2.2.</td>
                    <td>Sisa cuti yang dapat diambil</td>
                    {{-- <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td> --}}
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>- Cuti
                        Tahunan....................................................................................................................................
                    </td>
                    {{-- <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td> --}}
                    <td>=</td>
                    <td>-</td>
                    <td>HK</td>
                </tr>
                <tr>
                    <td></td>
                    <td>- Cuti
                        Panjang.....................................................................................................................................
                    </td>
                    {{-- <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td> --}}
                    <td>=</td>
                    <td>-</td>
                    <td>HK</td>
                </tr>
                <tr>
                    <td>2.3.</td>
                    <td>Sisa cuti setelah/dijalani</td>
                    {{-- <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td> --}}
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>- Cuti
                        Tahunan....................................................................................................................................
                    </td>
                    {{-- <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td> --}}
                    <td>=</td>
                    <td>-</td>
                    <td>HK</td>
                </tr>
                <tr>
                    <td></td>
                    <td>- Cuti
                        Panjang.....................................................................................................................................
                    </td>
                    {{-- <td style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td> --}}
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
        <br>
        <div>
            <table align="center" style="text-align: center;">
                <tr>
                    <td rowspan="2" style="transform: translateX(-13px); transform: translateY(-20px);">
                        Diketahui,
                    </td>
                    <td rowspan="2" style="color: transparent;">aaaaaaaaaaaaaaaaaaaaaaaa</td>
                    <td style="transform: translateY(-20px);">Pontianak,
                        {{ \Carbon\Carbon::parse($permintaanCuti->updated_at)->locale('id')->isoFormat('D MMMM YYYY') }}
                    </td>
                </tr>
                <tr>
                    <td style="transform: translateY(-20px);">Pemohon</td>
                </tr>
                {{-- <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td style="padding-top: 30px;"><b>{{ $nama_checker }}</b></td>
                    <td></td>
                    <td style="padding-top: 30px;"><b>{{ $karyawan->nama }}</b></td>
                </tr>
                <tr>
                    <td style="padding-top: 0%">{{ $jabatan_checker }}</td>
                    <td></td>
                    <td></td>
                </tr> --}}
                <tr>
                    <td rowspan="5">
                        <div style="">
                            <table
                                style="transform: translateY(-110px); border: 1px solid black; font-size: 10px; font-family: 'Courier New', Courier, monospace; width: 280px">
                                <tr>
                                    <td rowspan="5"><img src="{{ asset('assets/images/avatars/avatarlogo.png') }}"
                                            alt="" height="30"></td>
                                    <td colspan="4">Dokumen ini ditandatangani secara <br>elektronik oleh:</td>
                                </tr>
                                <tr>
                                    <td colspan="4"><b>{{ $nama_checker }}</b></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:top">NIK</td>
                                    <td style="text-align: center; vertical-align:top">:</td>
                                    <td colspan="2" style="text-align: left; vertical-align:top">{{ $nik_checker }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:top">Jabatan</td>
                                    <td style="text-align: center; vertical-align:top">:</td>
                                    <td colspan="2" style="text-align: left; vertical-align:top">
                                        {{ $jabatan_checker }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:top">Email</td>
                                    <td style="text-align: center; vertical-align:top">:</td>
                                    <td colspan="2" style="text-align: left; vertical-align:top; font-size: 10px;">
                                        info@reg5palmco.com {{-- email domain --}}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td><br><br></td>
                </tr>
                {{-- <tr>
                    <td><br></td>
                </tr> --}}
                <tr>
                    <td></td>
                    <td style="transform: translateY(-20px);">{{ $karyawan->nama }}</td>
                </tr>





                <tr align="center" style="transform: translateX(-150px); ">
                    <td colspan="3" style="margin-bottom: -5px">
                        <p style="margin-bottom: -1px">PTPN IV REGIONAL V</p>
                        <p style="margin-top: 0px">{{ $bagian }}</p>
                        <div style="margin-bottom: -5px">
                            <table align="center"
                                style="transform: translateY(-10px); border: 1px solid black; font-size: 10px; font-family: 'Courier New', Courier, monospace; width: 280px">
                                <tr>
                                    <td rowspan="5"><img
                                            src="{{ public_path() . '/assets/images/avatars/avatarlogo.png' }}"
                                            alt="" height="30"></td>
                                    <td colspan="4">Dokumen ini ditandatangani secara <br>elektronik oleh:</td>
                                </tr>
                                <tr>
                                    <td colspan="4"><b>{{ $nama_approver }}</b></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:top">NIK</td>
                                    <td style="text-align: center; vertical-align:top">:</td>
                                    <td colspan="2" style="text-align: left; vertical-align:top">{{ $nik_approver }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:top">Jabatan</td>
                                    <td style="text-align: center; vertical-align:top">:</td>
                                    <td colspan="2" style="text-align: left; vertical-align:top">
                                        {{ $jabatan_approver }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:top">Email</td>
                                    <td style="text-align: center; vertical-align:top">:</td>
                                    <td colspan="2" style="text-align: left; vertical-align:top; font-size:10px;">
                                        info@reg5palmco.com {{-- email domain --}}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>

            </table>
        </div>
    </div>
</body>
