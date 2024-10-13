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
</style>
<?php
$activePage = basename($_SERVER['REQUEST_URI']);

?>
<div class="page-wrapper">
    <div class="container-fluid">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">user</h4>
                  
                        <a href="{{ url('home/t_user') }}">
                            <button class="btn btn-info">Tambah</button>
                        </a>
   
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-dark mb-0">
                        <thead>
                            <tr>
                            <th scope="col">No</th>
                            <th scope="col">Username</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user as $key)
                            <tr>
                            <td> {{ $loop->iteration }}</td>
                            <td> {{ $key->username }}</td>
                            <td> {{ $key->nama }}</td>
                            <td>
                            @if ($activePage === "duser")
                            <a href="{{ url('home/resetpassword/'.$key->id_user) }}">
                                                    <button class="btn btn-primary"><i class="fas fa-redo"></i></button>
                                                </a>
                                <a href="{{ url('home/hapus_user/'.$key->id_user) }}">
                                                    <button class="btn btn-primary"><i class="fa fa-trash"></i></button>
                                                </a>
                                <a href="{{ url('home/detailuser/'.$key->id_user) }}">
                                                    <button class="btn btn-primary"><i class="fa fa-info-circle"></i></button>
                                                </a>
                                @endif

                                @if ($activePage === "suser")
                                <a href="{{ url('home/resetpasswords/'.$key->id_user) }}">
                                                    <button class="btn btn-primary"><i class="fas fa-redo"></i></button>
                                                </a>
                                <a href="{{ url('home/restoredeleteuser/'.$key->id_user) }}">
                                                    <button class="btn btn-primary"><i class="fas fa-sync-alt"></i></button>
                                                </a>
                                <a href="{{ url('home/detailuser/'.$key->id_user) }}">
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

