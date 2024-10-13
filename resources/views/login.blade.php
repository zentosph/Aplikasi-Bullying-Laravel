
    <style>
         .login {
            display: flex;
            justify-content: center;
            font-weight: bold;
            color: #4a934a;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
        }
        .captcha-container {
            display: none;
        }
    </style>
</head>

<body>
    <div class="main-wrapper">

    <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative">
    <div class="auth-box row">
        </div>
                <div class="col-lg-5 col-md-7 bg-white">
                    <div class="p-3">
                        <h2 class="mt-3 text-center">Sign In</h2>
                        <p class="text-center"></p>
                        <form class="mt-4" id="loginForm" action="{{ url('/home/post_aksi_login') }}" method="POST">

    @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="uname">Username</label>
                                        <input class="form-control" id="uname" type="text"
                                            placeholder="enter your username" name="username">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="pwd">Password</label>
                                        <input class="form-control" id="pwd" type="password"
                                            placeholder="enter your password" name="password">
                                    </div>
                                </div>
                            <div class="form-group captcha-container" id="captchaContainer">
                            <label for="captcha_code">Enter CAPTCHA</label>
                            <input type="text" class="form-control" id="captcha_code" name="captcha_code" placeholder="Enter CAPTCHA code" required>
                            <img id="captchaImage" src="" alt="CAPTCHA">
                            </div>
                            <div class="form-group" id="recaptchaContainer" style="display: none;">
                            <div class="g-recaptcha" data-sitekey="6LeKfiAqAAAAAIYfzHJCoT6fOpGTpktdJza3fixn"></div>
                            </div>
                                <div class="col-lg-12 text-center">
                                    <button type="submit" class="btn btn-block btn-dark">Sign In</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const captchaContainer = document.getElementById('captchaContainer');
        const recaptchaContainer = document.getElementById('recaptchaContainer');
        const captchaCodeInput = document.getElementById('captcha_code');
        const captchaImage = document.getElementById('captchaImage');

        if (navigator.onLine) {
            // Jika ada koneksi internet, tampilkan Google reCAPTCHA
            recaptchaContainer.style.display = 'block';
            captchaContainer.style.display = 'none';
            captchaCodeInput.removeAttribute('required'); // Hapus required jika CAPTCHA gambar tidak ditampilkan
        } else {
            // Jika tidak ada koneksi internet, tampilkan CAPTCHA gambar
            recaptchaContainer.style.display = 'none';
            captchaContainer.style.display = 'block';
            captchaCodeInput.setAttribute('required', 'required'); // Tambahkan required jika CAPTCHA gambar ditampilkan
            captchaImage.src = '{{ url('/home/generateCaptcha') }}';

        }
    });
</script>
   
</body>

</html>