<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KHS - {{ $mahasiswa->nama_mahasiswa }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header h3 {
            margin: 5px 0;
            font-size: 14px;
        }
        .header p {
            margin: 2px 0;
        }
        .student-info {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
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
        table.khs-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table.khs-table th,
        table.khs-table td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }
        table.khs-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        table.khs-table td {
            font-size: 10px;
        }
        .summary {
            margin-top: 15px;
            text-align: right;
        }
        .summary-box {
            display: inline-block;
            border: 2px solid #333;
            padding: 10px 20px;
            margin-left: 20px;
        }
        .summary-box strong {
            font-size: 12px;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .signature {
            margin-top: 50px;
            text-align: center;
            display: inline-block;
            width: 250px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
        }
        .grade-legend {
            margin-top: 20px;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>KARTU HASIL STUDI (KHS)</h2>
        <h3>UNIVERSITAS CONTOH</h3>
        <p>Tahun Akademik 2026/2027 - Semester Genap</p>
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

    <table class="khs-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode MK</th>
                <th width="35%">Nama Mata Kuliah</th>
                <th width="8%">SKS</th>
                <th width="15%">Nilai Akhir</th>
                <th width="12%">Grade</th>
                <th width="10%">Bobot</th>
            </tr>
        </thead>
        <tbody>
            @foreach($khsData as $index => $khs)
            @php
                $grade = strtoupper($khs->grade);
                $bobot = 0;
                switch($grade) {
                    case 'A': $bobot = 4.0; break;
                    case 'B+': $bobot = 3.5; break;
                    case 'B': $bobot = 3.0; break;
                    case 'C+': $bobot = 2.5; break;
                    case 'C': $bobot = 2.0; break;
                    case 'D': $bobot = 1.0; break;
                    case 'E': $bobot = 0.0; break;
                }
            @endphp
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $khs->matakuliah->kode_matkul }}</td>
                <td>{{ $khs->matakuliah->nama_matkul }}</td>
                <td style="text-align: center;">{{ $khs->matakuliah->sks }}</td>
                <td style="text-align: center;">{{ number_format($khs->nilai_akhir, 2) }}</td>
                <td style="text-align: center;"><strong>{{ $grade }}</strong></td>
                <td style="text-align: center;">{{ number_format($bobot, 1) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-box">
            <strong>Total SKS: {{ $totalSks }}</strong>
        </div>
        <div class="summary-box">
            <strong>IPK: {{ number_format($ipkKumulatif, 2) }}</strong>
        </div>
    </div>

    <div class="grade-legend">
        <strong>Keterangan:</strong> A = 4.0 | B+ = 3.5 | B = 3.0 | C+ = 2.5 | C = 2.0 | D = 1.0 | E = 0.0
    </div>

    <div class="footer">
        <div class="signature">
            <p>Mengetahui,</p>
            <p><strong>Ketua Program Studi</strong></p>
            <div class="signature-line">
                <p>(___________________)</p>
                <p>NIP. ........................</p>
            </div>
        </div>
    </div>
</body>
</html>