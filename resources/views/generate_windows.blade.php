<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>
    <style>
        /* Ensure the document size and styling match an A4 page */
        @page {
            size: A4;
            margin: 0; /* Remove margins for printing */
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .content {
            position: relative;
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px; 
            box-shadow: 0 0 5px rgba(0,0,0,0.1); 
        }
        .header {
            margin-bottom: 20px;
        }
        .title, .subtitle {
            text-align: left;
            margin: 0;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
        }
        .subtitle {
            font-size: 18px;
            margin-top: 5px;
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
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #FFFF00;
            font-weight: bold;
        }
        .total-row {
            font-weight: bold;
            text-align: right;
            border-top: 2px solid #000;
        }
        .total-value {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="header">
            <div class="title">Laporan Kasus</div>
            <div class="subtitle">{{ $setting->judul_website ?? 'Default Website' }}</div>
            <div class="subtitle">Tanggal Kasus: {{ $startDate }} - {{ $endDate }}</div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kelas as $key)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ htmlspecialchars($key->username, ENT_QUOTES, 'UTF-8') }}</td>
                    <td>{{ htmlspecialchars($key->nama, ENT_QUOTES, 'UTF-8') }}</td>
                    <td>{{ htmlspecialchars($key->kelas, ENT_QUOTES, 'UTF-8') }}</td>
                    <td>{{ htmlspecialchars($key->status, ENT_QUOTES, 'UTF-8') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
    window.addEventListener('load', function() {
        window.print();
    });
    </script>
</body>
</html>
