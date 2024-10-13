<style>

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
<div class="page-wrapper">

<div class="container-fluid">
    <div class="container">
        <h2>Form User</h2>
        <form action="{{ url('/home/post_t_user') }}" method="post" enctype="multipart/form-data">
        @csrf
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="name">Nama:</label>
                <input type="text" id="name" name="nama" required>
            </div>
            <div class="form-group">
    <label for="kelas">Kelas:</label>
    <select class="form-control" name="kelas" id="kelas">
        <?php foreach ($kelas as $key): ?>
            <option value="<?= $key->id_kelas ?>">
                <?= $key->kelas ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
            <div class="form-group">
                <button type="submit">Simpan</button>
            </div>
        </form>
    </div>
</div>
</div>
