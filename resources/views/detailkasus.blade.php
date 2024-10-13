<style>
    #autoResizeTextarea {
        max-height: auto; /* Example max height */
        overflow-y: auto; /* Allow scrolling if content exceeds max height */
    }

    @media (max-width: 768px) {
        #autoResizeTextarea {
            max-height: 400px;
        }
    }

    @media (min-width: 769px) {
        #autoResizeTextarea {
            max-height: 600px;
        }
    }

    .containerbtn {
        display: flex;
    }

    .selesai {
        margin-left: auto;
    }
</style>

<?php
$activePage = basename($_SERVER['REQUEST_URI']);
?>

<div class="page-wrapper">
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text text-danger">Detail Kasus</h3>
                        <div class="form-group">
                            <h6 class="card-title">Nama</h6>
                            <input type="text" class="form-control" value="{{$satu->nama}} | {{$satu->kelas}}" disabled>
                            <h6 class="card-title">Kronologi</h6>
                            <textarea class="form-control" name="kronologi" id="autoResizeTextarea" disabled>{{$satu->kasus}}</textarea>
                        </div>
                        <div class="containerbtn">
                            <div class="back">
                                <a href="{{ url('home/dkasus/'.session()->get('id')) }}">
                                    <button class="btn btn-primary">Back</button>
                                </a>
                            </div>
                            @if (session()->get('level') == 1 && $satu->status == "Pending")
                                <div class="selesai">
                                    <a href="{{ url('home/statusproses/'.$satu->id_kasus) }}">
                                        <button class="btn btn-primary">Proses</button>
                                    </a>
                                </div>
                            @endif

                            @if (session()->get('level') == 1 && $satu->status == "Proses")
                                <div class="selesai">
                                    <a href="{{ url('home/statuspks/'.$satu->id_kasus) }}">
                                        <button class="btn btn-primary">Tindaklanjut</button>
                                    </a>
                                    <a href="{{ url('home/statusselesai/'.$satu->id_kasus) }}">
                                        <button class="btn btn-success">Selesai</button>
                                    </a>
                                </div>
                            @endif

                            @if (session()->get('level') == 1 && $satu->status == "Proses ke Kepala Sekolah")
                                <div class="selesai">
                                    <a href="{{ url('home/statusselesai/'.$satu->id_kasus) }}">
                                        <button class="btn btn-primary">Selesai</button>
                                    </a>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <script>
            window.addEventListener('load', function () {
                const textarea = document.getElementById('autoResizeTextarea');

                if (textarea) {
                    console.log('Textarea found:', textarea);

                    function autoResize() {
                        this.style.height = 'auto'; // Reset height
                        let newHeight = this.scrollHeight;

                        const maxHeight = 600; // Set a reasonable max height
                        if (newHeight > maxHeight) {
                            newHeight = maxHeight;
                        }

                        this.style.height = newHeight + 'px';
                        console.log('New height set:', this.style.height);
                    }

                    setTimeout(function() {
                        autoResize.call(textarea);
                    }, 100); // Adjust this delay as needed

                    textarea.addEventListener('input', autoResize, { passive: true });
                } else {
                    console.error('Textarea not found!');
                }
            });
        </script>
