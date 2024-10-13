<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Button Layout with Date Inputs</title>
    <style>
        .btn-status {
            border: none;
            color: white;
            padding: 8px 12px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: background-color 0.3s ease;
        }
        .btn-status-pending { background-color: #f5a623; }
        .btn-status-proses { background-color: #007bff; }
        .btn-status-proses-ke-kepala-sekolah { background-color: #6f42c1; }
        .btn-status-selesai { background-color: #28a745; }
        .btn-status:hover { opacity: 0.8; }

        .button-group {
            display: flex;
            gap: 10px; 
            margin-bottom: 20px; 
        }

        .form-group { margin-bottom: 20px; }

        .form-group label { margin-right: 10px; }

        .form-group input[type="date"] {
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="page-wrapper">
    <div class="container-fluid">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">User</h4>
                    
                    <!-- Date Inputs -->
                    <form method="GET" action="{{ route('filter.tanggal') }}">
                        <div class="form-group">
                            <label for="tanggal_awal">Tanggal Awal:</label>
                            <input type="date" id="tanggal_awal" name="start_date" value="{{ request('start_date') }}">
                        </div>
                        <div class="form-group">
                            <label for="tanggal_akhir">Tanggal Akhir:</label>
                            <input type="date" id="tanggal_akhir" name="end_date" value="{{ request('end_date') }}">
                        </div>
                        <div class="btn btn-info btn-status">
                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                        </div>
                    </form>
                    
                    <!-- Buttons -->
                    <div class="button-group">
                        <button class="btn btn-info btn-status btn-status-proses" onclick="generatePDF()">PDF</button>
                        <button class="btn btn-info btn-status btn-status-proses-ke-kepala-sekolah" onclick="generateEXCEL()">Excel</button>
                        <button class="btn btn-info btn-status btn-status-selesai" onclick="generateWINDOWS()">Windows</button>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-dark mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Kelas</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php
                            $no = 1;
                            @endphp
                            @foreach ($kelas as $key)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $key->username }}</td>
                                    <td>{{ $key->nama }}</td>
                                    <td>{{ $key->kelas }}</td>
                                    <td>{{ $key->status }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script>
function generatePDF() {
    var startDate = document.querySelector('input[name="start_date"]').value;
    var endDate = document.querySelector('input[name="end_date"]').value;

    // Build the URL for the PDF
    var url = '{{ route('kasus.pdf') }}' + '?start_date=' + encodeURIComponent(startDate) + '&end_date=' + encodeURIComponent(endDate);

    // Open the URL in a new tab
    window.open(url, '_blank');
}

function generateEXCEL() {
    var startDate = document.querySelector('input[name="start_date"]').value;
    var endDate = document.querySelector('input[name="end_date"]').value;

    // Build the URL for the Excel
    var url = '{{ route('kasus.excel') }}' + '?start_date=' + encodeURIComponent(startDate) + '&end_date=' + encodeURIComponent(endDate);

    // Open the URL in a new tab
    window.open(url, '_blank');
}

function generateWINDOWS() {
    var startDate = document.querySelector('input[name="start_date"]').value;
    var endDate = document.querySelector('input[name="end_date"]').value;

    // Build the URL for the Windows
    var url = '{{ route('kasus.windows') }}' + '?start_date=' + encodeURIComponent(startDate) + '&end_date=' + encodeURIComponent(endDate);

    // Open the URL in a new tab
    window.open(url, '_blank');
}
</script>
</html>
