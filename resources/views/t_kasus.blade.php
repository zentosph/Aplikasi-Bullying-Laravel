

<style>
    .containerbutton{
        display: flex;
        justify-content: center;
        align-items: center;
        /* background-color: black; */
        height: 60px;
    }
</style>
<div class="page-wrapper">
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <!-- <h4 class="card-title">Kasus</h4> -->
                        <form class="mt-4" method="POST" action="{{ url('/home/post_aksi_tkasus') }}">
                        @csrf
                            <div class="form-group">
                            <h5 class="card-title text text-danger">Kronologi Lengkap*</h5>
                                <div class="input-group">
                                
                                    <textarea class="form-control" id="voiceInput" placeholder="Klik mikrofon dan bicara..." name="kronologi"></textarea>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" id="startVoice">
                                            <i class="fa fa-microphone"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        
                    </div>
                    <div class="containerbutton">
                        <div>
                        <button type="submit" class="btn btn-info">Simpan</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://use.fontawesome.com/releases/v5.8.1/js/all.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
    const startVoiceButton = document.getElementById('startVoice');
    const voiceInput = document.getElementById('voiceInput');
    
    let recognition;

    function startRecognition() {
        recognition = new webkitSpeechRecognition();
        recognition.lang = 'id-ID'; // Bahasa Indonesia
        recognition.interimResults = true; // Tampilkan hasil sementara
        recognition.maxAlternatives = 1; // Ambil hasil terbaik
        recognition.continuous = true; // Pengenalan suara akan terus berjalan

        recognition.onresult = (event) => {
            let interimTranscript = '';
            let finalTranscript = '';

            // Iterasi melalui hasil untuk menampilkan hasil final dan interim
            for (let i = 0; i < event.results.length; i++) {
                const transcript = event.results[i][0].transcript;
                if (event.results[i].isFinal) {
                    finalTranscript += transcript;
                } else {
                    interimTranscript += transcript;
                }
            }

            // Gabungkan hasil final dengan hasil sementara
            voiceInput.value = finalTranscript + interimTranscript;
        };

        recognition.onerror = (event) => {
            console.error('Error occurred in recognition: ' + event.error);
            if (event.error === 'no-speech' || event.error === 'network') {
                recognition.stop();
                startRecognition();
            }
        };

        recognition.onend = () => {
            // Restart recognition if it ends automatically
            startRecognition();
        };

        recognition.start();
    }

    startVoiceButton.addEventListener('click', () => {
        if ('webkitSpeechRecognition' in window) {
            startRecognition();
        } else {
            alert("Browser Anda tidak mendukung pengenalan suara.");
        }
    });
});

</script>

</body>
</html>
