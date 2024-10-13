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
    .btn-status-pending {
        background-color: #f5a623; /* Soft orange */
    }
    .btn-status-proses {
        background-color: #007bff; /* Soft blue */
    }
    .btn-status-proses-ke-kepala-sekolah {
        background-color: #6f42c1; /* Soft purple */
    }
    .btn-status-selesai {
        background-color: #28a745; /* Soft green */
    }
    .btn-status:hover {
        opacity: 0.8;
    }

    .peringatan{
        color: red;
        margin-bottom: 0px;
    }
</style>
<?php
// Get the current URL path
$urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Split the path into segments
$segments = explode('/', trim($urlPath, '/'));

// Assuming 'home' is the first segment and 'dkasus' is the second segment
// Get the second segment (index 1) which corresponds to 'dkasus'
$activePage = isset($segments[1]) ? $segments[1] : null;

?>
<div class="page-wrapper">
    <div class="container-fluid">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Kasus</h4>
           
                        <a href="{{ url('home/t_kasus') }}">
                            <button class="btn btn-info">Laporkan</button>
                        </a>
                        <h6 class="peringatan">*Laporan Bullying ini Akan ditindaklanjuti</h6>

                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-dark mb-0">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Kelas</th>
                                <th scope="col">Status</th>
                                @if (session()->get('level') == 1 || session()->get('level') == 4)
                                    <th scope="col">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($kasus as $key)
                            @php
                                $btnClass = '';
                                switch ($key->status) {
                                    case 'Pending':
                                        $btnClass = 'btn-status btn-status-pending';
                                        break;
                                    case 'Proses':
                                        $btnClass = 'btn-status btn-status-proses';
                                        break;
                                    case 'Proses ke Kepala Sekolah':
                                        $btnClass = 'btn-status btn-status-proses-ke-kepala-sekolah';
                                        break;
                                    case 'Selesai':
                                        $btnClass = 'btn-status btn-status-selesai';
                                        break;
                                }
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $key->nama }}</td>
                                <td>{{ $key->kelas }}</td>
                                <td><button class="{{ $btnClass }}">{{ $key->status }}</button></td>
                                <td>
                                @if ($activePage === "dkasus")
                                <a href="{{ url('home/softdeletekasus/'.$key->id_kasus) }}">
                                                    <button class="btn btn-primary"><i class="fa fa-trash"></i></button>
                                                </a>
                                <a href="{{ url('home/detailkasus/'.$key->id_kasus) }}">
                                                    <button class="btn btn-primary"><i class="fa fa-info-circle"></i></button>
                                                </a>
                                @endif
                                
                                @if ($activePage === "datakasus")
                                <a href="{{ url('home/softdeletekasus/'.$key->id_kasus) }}">
                                                    <button class="btn btn-primary"><i class="fa fa-trash"></i></button>
                                                </a>
                                <a href="{{ url('home/detailkasus/'.$key->id_kasus) }}">
                                                    <button class="btn btn-primary"><i class="fa fa-info-circle"></i></button>
                                                </a>
                                @endif

                                @if ($activePage === "skasus")
                                <a href="{{ url('home/restoredeletekasus/'.$key->id_kasus) }}">
                                                    <button class="btn btn-primary"><i class="fas fa-sync-alt"></i></button>
                                                </a>
                                <a href="{{ url('home/detailkasus/'.$key->id_kasus) }}">
                                                    <button class="btn btn-primary"><i class="fa fa-info-circle"></i></button>
                                                </a>
                                @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

