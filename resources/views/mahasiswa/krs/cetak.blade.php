<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KRS - {{ $mahasiswa->nama_mahasiswa }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
        }
        .student-info {
            margin-bottom: 20px;
        }
        .student-info table {
            width: 100%;
        }
        .student-info td {
            padding: 3px 5px;
        }
        .student-info td:first-child {
            width: 150px;
            font-weight: bold;
        }
        .student-info td:nth-child(2) {
            width: 20px;
        }
        table.krs-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table.krs-table th,
        table.krs-table td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }
        table.krs-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        table.krs-table td {
            font-size: 11px;
        }
        .total {
            margin-top: 15px;
            text-align: right;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .signature {
            margin-top: 50px;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 200px;
            margin: 5px auto;
            padding-top: 3px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Kartu Rencana Studi (KRS)</h2>
        <p><strong>UNIVERSITAS CONTOH</strong></p>
        <p>Tahun Akademik 2026/2027</p>
    </div>

    <div class="student-info">
        <table>
            <tr>
                <td>Nama Mahasiswa</td>
                <td>:</td>
                <td>{{ $mahasiswa->nama_mahasiswa }}</td>
            </tr>
            <tr>
                <td>NPM</td>
                <td>:</td>
                <td>{{ $mahasiswa->id_user }}</td>
            </tr>
            <tr>
                <td>Program Studi</td>
                <td>:</td>
                <td>{{ $mahasiswa->prodi }}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{ $mahasiswa->kelas }}</td>
            </tr>
            <tr>
                <td>Semester</td>
                <td>:</td>
                <td>{{ $mahasiswa->semester }}</td>
            </tr>
            <tr>
                <td>Tanggal Cetak</td>
                <td>:</td>
                <td>{{ $tanggal }}</td>
            </tr>
        </table>
    </div>

 <table class="krs-table">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="12%">Kode MK</th>
            <th width="30%">Nama Mata Kuliah</th>
            <th width="8%">SKS</th>
            <th width="10%">Kelas</th>
            <th width="25%">Dosen Pengampu</th>
            <th width="10%">Semester</th>
        </tr>
    </thead>
    <tbody>
        @forelse($krsData as $index => $krs)
        <tr>
            <td style="text-align: center;">{{ $index + 1 }}</td>
            <td>{{ $krs->matakuliah->kode_matkul }}</td>
            <td>{{ $krs->matakuliah->nama_matkul }}</td>
            <td style="text-align: center;">{{ $krs->matakuliah->sks }}</td>
            <td style="text-align: center;">{{ $krs->matakuliah->kelas ?? $mahasiswa->kelas }}</td>
            <td>{{ $krs->matakuliah->dosen->nama_dosen }}</td>
            <td style="text-align: center;">{{ $krs->semester }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align: center;">Belum ada mata kuliah yang dipaketkan</td>
        </tr>
        @endforelse
    </tbody>
</table>

    @if($krsData->count() > 0)
    <div class="total">
        Total SKS Diambil: {{ $totalSks }} SKS
    </div>
    @endif

    <div class="footer">
        <p>Mengetahui,</p>
        <div style="display: flex; justify-content: space-between; margin-top: 50px;">
            <div style="text-align: center; width: 45%;">
                <p>Wakil Dekan I</p>
                <br><br><br>
                <p>___________________</p>
                <p>NIP. ........................</p>
            </div>
            <div style="text-align: center; width: 45%;">
                <p>Ketua Program Studi</p>
                <br><br><br>
                <p>___________________</p>
                <p>NIP. ........................</p>
            </div>
        </div>
    </div>
</body>
</html>