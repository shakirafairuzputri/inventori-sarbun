<!DOCTYPE html>
<html>
<head>
    <title>Laporan Persediaan Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        h2 {
            text-align: center;
        }
        .tengah {
            text-align: center;
            line-height: 5px;
        }

        .table {
            border-bottom: 3px solid #000;
            padding: 2px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <table class="table" width="100%">
        <tr>
            <td class="tengah">
                <img src="{{ public_path('sb/assets/img/saribundologo.png') }}" alt="Logo" style="width: 60px; height: auto;">
                <h1>Rumah Makan Sari Bundo</h1>
                <b>Jl. Merak, Sadang Serang, Kecamatan Coblong, Kota Bandung, Jawa Barat 40132</b><br>

            </td>
        </tr>
    </table>
    <h2>Laporan Persediaan Barang</h2>
    <p style="text-align: center;">Tanggal: {{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Bahan/Barang</th>
                <th>Kelompok</th>
                <th>Kategori</th>
                <th>Satuan</th>
                <th>Stok Awal</th>
                <th>Tambah</th>
                <th>Kurang</th>
                <th>Sisa</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reportData as $data)
                <tr>
                    <td>{{ $data['tanggal'] }}</td>
                    <td>{{ $data['nama_barang'] }}</td>
                    <td>{{ $data['kelompok'] }}</td>
                    <td>{{ $data['kategori_brg'] }}</td>
                    <td>{{ $data['satuan_brg'] }}</td>
                    <td>{{ $data['stok_awal'] }}</td>
                    <td>{{ $data['tambah'] }}</td>
                    <td>{{ $data['kurang'] }}</td>
                    <td>{{ $data['sisa'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
