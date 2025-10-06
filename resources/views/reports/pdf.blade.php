<!DOCTYPE html>
<html>
<head>
    <title>Laporan Stok Opname</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dddddd; text-align: left; padding: 6px; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
        .footer { /* ... */ }
        .positive { color: green; }
        .negative { color: red; }
    </style>
</head>
<body>
    <h1>Laporan Hasil Stok Opname</h1>
    <p>Tanggal Dibuat: {{ now()->format('d F Y, H:i') }}</p>
    <hr>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th style="text-align: center;">Stok Sistem</th>
                <th style="text-align: center;">Stok Fisik</th>
                <th style="text-align: center;">Selisih</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData as $data)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data['name'] }}</td>
                    <td>{{ $data['category'] }}</td>
                    <td style="text-align: center;">{{ $data['system_stock'] }}</td>
                    <td style="text-align: center;">{{ $data['physical_stock'] ?? '-' }}</td>
                    <td style="text-align: center;" class="{{ ($data['variance'] ?? 0) > 0 ? 'positive' : (($data['variance'] ?? 0) < 0 ? 'negative' : '') }}">
                        @if($data['variance'] !== null)
                            {{ ($data['variance'] > 0 ? '+' : '') . $data['variance'] }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 40px; width: 100%;">
        <div style="width: 30%; float: right; text-align: center;">
            <p>Diperiksa oleh,</p>
            <br><br><br>
            <p>(_________________________)</p>
            <p>Admin</p>
        </div>
        <div style="width: 30%; float: left; text-align: center;">
            <p>Dibuat oleh,</p>
            <br><br><br>
            <p>( {{ auth()->user()->name }} )</p>
            <p>Staf Inventaris</p>
        </div>
    </div>
</body>
</html>
