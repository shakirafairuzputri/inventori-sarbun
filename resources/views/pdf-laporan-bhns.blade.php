<html>
<head>
    <style>
        /* CSS untuk mengecilkan ukuran tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px; /* Ukuran teks lebih kecil */
        }
        th, td {
            border: 1px solid black;
            padding: 4px;
            text-align: left;
        }
        h2 {
            font-size: 14px;
        }
        .tengah {
            text-align: center;
            line-height: 5px;
        }

        .table {
            border-bottom: 3px solid #000;
            padding: 2px;
            margin: 0 auto;
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
    <h2>Laporan Persediaan Bahan</h2>
    <p style="text-align: center;">
        Tanggal: 
        @if($tanggalMulai === $tanggalSelesai)
            {{ \Carbon\Carbon::parse($tanggalMulai)->format('d-m-Y') }}
        @else
            {{ \Carbon\Carbon::parse($tanggalMulai)->format('d-m-Y') }} s.d. {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d-m-Y') }}
        @endif
    </p>  
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Bahan</th>
                <th>Kategori</th>
                <th>Satuan</th>
                <th>Stok Awal</th>
                <th>Retur Baik</th>
                <th>Retur Rusak</th>
                <th>Pembelian</th>
                <th>Produksi Baik</th>
                <th>Produksi Paket</th>
                <th>Produksi Rusak</th>
                <th>Stok Siang</th>
                <th>Cek Fisik</th>
                <th>Selisih</th>
                <th>Tambahan Sore</th>
                <th>Stok Akhir</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reportDatas as $key => $data)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $data['tanggal'] }}</td>
                    <td>{{ $data['nama_bahan'] }}</td>
                    <td>{{ $data['kategori'] }}</td>
                    <td>{{ $data['satuan'] }}</td>
                    <td>{{ $data['stok_awal'] }}</td>
                    <td>{{ $data['retur_baik'] }}</td>
                    <td>{{ $data['retur_rusak'] }}</td>
                    <td>{{ $data['pembelian'] }}</td>
                    <td>{{ $data['produksi_baik'] }}</td>
                    <td>{{ $data['produksi_paket'] }}</td>
                    <td>{{ $data['produksi_rusak'] }}</td>
                    <td>{{ $data['stok_siang'] }}</td>
                    <td>{{ $data['cek_fisik'] }}</td>
                    <td>
                        {{ $data['selisih'] == 0 ? '0' : ($data['selisih'] < 0 ? '-' . abs($data['selisih']) : $data['selisih']) }}
                    </td>
                    <td>{{ $data['tambahan_sore'] }}</td>
                    <td>{{ $data['stok_akhir'] }}</td>
                    <td>{{ $data['keterangan'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
