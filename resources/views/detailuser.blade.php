<style>
    .container {
        max-width: 600px;
        margin: 0 auto;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .form-group input[type="text"] {
        width: 100%;
        padding: 8px;
        border-radius: 4px;
        border: 1px solid #ced4da;
    }
    .form-group input[type="file"] {
        border: none;
    }
    .form-group img {
        max-width: 100%;
        height: auto;
        border-radius: 4px;
    }
    .form-group button {
        padding: 10px 15px;
        background-color: #007bff;
        color: #ffffff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }
    .form-group button:hover {
        background-color: #0056b3;
    }
</style>
<?php
// Misalkan Anda telah mengambil nilai acak dari database dan menyimpannya dalam variabel $selected_package_id
$jabatan = $users->id_kelas;
?>
<div class="page-wrapper">
    <div class="container-fluid">
        <div class="container">
            <h2>Form Profil</h2>
            <form action="{{ url('/home/post_aksi_euser') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="image">Gambar Profil:</label>
                    <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                    <div id="imagePreview">
                        <img id="previewImg" src="{{ asset('images/' . $users->foto) }}" alt="Preview Image" style="{{ $users->foto ? '' : 'display: none;' }}" width="100px">
                    </div>
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="{{ $users->username }}" required>
                </div>
                <div class="form-group">
                    <label for="name">Nama:</label>
                    <input type="text" id="name" name="nama" value="{{ $users->nama }}" required>
                </div>
                <div class="form-group">
                    <label for="kelas">Kelas / Jabatan :</label>
                    <select class="form-control" name="kelas" id="kelas">
                
                        @foreach ($kelas as $key)
                            <option value="{{ $key->id_kelas }}" {{ $key->id_kelas == $jabatan ? 'selected' : '' }}>
                                {{ $key->kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit">Simpan</button>
                    <input type="hidden" value="{{ $users->id_user }}" name="id">
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const input = event.target;
        const file = input.files[0];
        const previewImg = document.getElementById('previewImg');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            previewImg.src = '{{ asset('assets/images/users/' . $users->foto) }}'; // Gambar default jika ada
            previewImg.style.display = 'none';
        }
    }
</script>
