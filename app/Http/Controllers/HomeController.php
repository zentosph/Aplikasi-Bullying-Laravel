<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
// use App\Models\User;
use App\Models\M_b;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Dompdf\Dompdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KelasExport;
class HomeController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    
    public function index()
    {
        $model = new M_b();
        $where = ['setting.id_setting' => 1];
        $data = [
            'setting' => $model->getwhere('setting', $where),
        ];
    
        $where1 = array('level' =>session()->get('level'));
		$data['menu'] = $model->getwhere('menu', $where1);
		if ($data['menu'] === null || !isset($data['menu']->dashboard)) {
			return redirect()->to('home/login');
		}
		if ($data['menu']->dashboard == 1) {
            echo view('header');
            echo view('menu', $data);
            echo view('dashboard');
            echo view('footer');
        } else {
            return redirect()->to('home/login');
        }
    }
    
    

    public function login()
    {
        echo view('header');
        echo view('login');
    }

    public function generateCaptcha()
    {
        // Create a string of possible characters
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $captcha_code = '';

        // Generate a random CAPTCHA code with letters and numbers
        for ($i = 0; $i < 6; $i++) {
            $captcha_code .= $characters[rand(0, strlen($characters) - 1)];
        }

        // Store CAPTCHA code in session
        session()->set('captcha_code', $captcha_code);

        // Create an image for CAPTCHA
        $image = imagecreate(120, 40); // Increased size for better readability
        $background = imagecolorallocate($image, 200, 200, 200);
        $text_color = imagecolorallocate($image, 0, 0, 0);
        $line_color = imagecolorallocate($image, 64, 64, 64);

        imagefilledrectangle($image, 0, 0, 120, 40, $background);

        // Add some random lines to the CAPTCHA image for added complexity
        for ($i = 0; $i < 5; $i++) {
            imageline($image, rand(0, 120), rand(0, 40), rand(0, 120), rand(0, 40), $line_color);
        }

        // Add the CAPTCHA code to the image
        imagestring($image, 5, 20, 10, $captcha_code, $text_color);

        // Output the CAPTCHA image
        header('Content-type: image/png');
        imagepng($image);
        imagedestroy($image);
    }

    public function checkInternetConnection()
    {
        $connected = @fsockopen("www.google.com", 80);
        if ($connected) {
            fclose($connected);
            return true;
        } else {
            return false;
        }
    }

    public function post_aksi_login(Request $request)
{
    // Check internet connection
    if (!$this->checkInternetConnection()) {
        // If no internet connection, check CAPTCHA image
        if (session('captcha_code') !== $request->captcha_code) {
            return redirect()->route('login')
                ->with('toast_message', 'Invalid CAPTCHA')
                ->with('toast_type', 'danger');
        }
    } else {
        // Verify Google reCAPTCHA
        $recaptchaResponse = $request->input('g-recaptcha-response');
        $secret = '6LeKfiAqAAAAAFkFzd_B9MmWjX76dhdJmJFb6_Vi'; // Replace with your Secret Key

        $response = Http::withOptions(['verify' => false])->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secret,
            'response' => $recaptchaResponse,
        ]);

        $status = json_decode($response, true);

        if (!$status['success']) {
            return redirect()->to('home/login')
                ->with('toast_message', 'Captcha validation failed')
                ->with('toast_type', 'danger');
        }
    }

    // Normal login process
    $u = $request->username;
    $p = $request->password;

    $where = [
        'username' => $u,
        'password' => md5($p),
    ];

    // Get user using the User model
    $user = M_b::getWhere('user',$where); // Call the static method

    if ($user) {
        session()->put([
            'nama' => $user->nama, 
            'id' => $user->id_user, 
            'level' => $user->id_level, 
            'kelas' => $user->id_kelas,
            'foto' => $user->foto
        ]);
        // dd(session()->all()); // Debug session
        return redirect()->to('home/index');
        
    } else {
        return redirect()->to('home/login');
        // print_r($where);

    }
}

public function logout()
{
    session()->forget(['nama', 'id', 'level', 'kelas']); // Remove specific session variables
    return redirect()->to('home/login');
}

public function dkasus($id)
{
    $model = new M_b();
    $where = ['kasus.delete' => null , 'kasus.id_user' => $id];

    // Adjusting the join conditions to pass column names separately
    $data = [
        'kasus' => $model->joinResult2(
            'kasus',
            'user', ['kasus.id_user', 'user.id_user'],
            'kelas', ['user.id_kelas', 'kelas.id_kelas'],
            $where
        ),
    ];

    $where = ['setting.id_setting' => 1];
    $data1 = [
        'setting' => $model->getwhere('setting', $where),
    ];
    $where1 = array('level' =>session()->get('level'));
		$data2['menu'] = $model->getwhere('menu', $where1);
	if ($data2['menu'] === null || !isset($data2['menu']->dashboard)) {
			return redirect()->to('home/login');
	}
	if (session()->get('level') == 1 || $data2['menu']->dashboard == 1) {
    echo view('header');
    echo view('menu',$data1, $data2);
    echo view('dkasus', $data); // Pass the data array to the view
    echo view('footer');
    }else{
        return redirect()->to('home/login');
    }
}


public function t_kasus(){
    $model = new M_b();


    $where = ['setting.id_setting' => 1];
    $data1 = [
        'setting' => $model->getwhere('setting', $where),
    ];
    $where1 = array('level' =>session()->get('level'));
		$data2['menu'] = $model->getwhere('menu', $where1);
		if ($data2['menu'] === null || !isset($data2['menu']->dashboard)) {
			return redirect()->to('home/login');
		}
		if (session()->get('level') == 1 || $data2['menu']->dashboard == 1) {
    echo view('header');
    echo view('menu', $data1,$data2);
    echo view('t_kasus'); // Pass the data array to the view
    echo view('footer');
    }else{
        return redirect()->to('home/login');
    }
}

public function post_aksi_tkasus(Request $request){
    $model = new M_b();
	
	$kronologi = $request->input('kronologi');
	$data = array(
        'id_user' => session('id'),  
        'kasus' => $kronologi,
        'status' => 'Pending',
		'create_at' => date('Y-m-d H:i:s'),
		'create_by' => session('id'),
    );
	$model->tambah('kasus', $data);
    return redirect()->to('home/dkasus/'.session()->get('id'));
}

public function softdeletekasus($id){
    $model = new M_b;
			$where = array('id_kasus' => $id);
			// Ubah status transaksi menjadi "habis" di kedua tabel
			$model->statuschange('kasus', 'delete', date('Y-m-d H:i:s'), $where);
            // $this->log_activity('User Soft Delete Kasus');
			// Kirim respons (jika diperlukan)
			return redirect()->to('home/dkasus/'.session()->get('id'));
}

public function restoredeletekasus($id){
    $model = new M_b;
			$where = array('id_kasus' => $id);
			// Ubah status transaksi menjadi "habis" di kedua tabel
			$model->statuschange('kasus', 'delete', NULL, $where);
            // $this->log_activity('User Soft Delete Kasus');
			// Kirim respons (jika diperlukan)
			return redirect()->to('home/dkasus/'.session()->get('id'));
}


public function detailkasus($id){

    $model = new M_b();
		$where1 = array('level' =>session()->get('level'));
		$data2['menu'] = $model->getwhere('menu', $where1);
        // $this->log_activity('User Membuka Detail Kasus');
		if ($data2['menu'] === null || !isset($data2['menu']->dashboard)) {
			return redirect()->to('Home/login');
		}
        $where = array('user.id_kelas' => session()->get('kelas'));
        $data['user'] = $model->joinRow('kelas', 'user', ['kelas.id_kelas', 'user.id_kelas'], $where);        
        $where = array('kasus.delete' => Null);
$where1 = array('id_kasus' => $id);
$data['satu'] = $model->joinRow2(
    'kasus',
    'user',
    ['kasus.id_user', 'user.id_user'],  // Menggunakan array untuk join pertama
    'kelas',
    ['user.id_kelas', 'kelas.id_kelas'], // Menggunakan array untuk join kedua
    $where,
    $where1
);

        $where = array('setting.id_setting' => 1);
        $data1['setting'] = $model->getwhere('setting', $where);
	if (session()->get('level') == 1 || $data2['menu']->kasus == 1) {
        echo view('header', $data1);
        echo view('menu', $data2,$data1);
        echo view('detailkasus',$data);
        echo view('footer');
        // print_r($data['satu']);
    } else {
		return redirect()->to('home/login');
	}
}

public function statusproses($id){
    $model = new M_b;
			$where = array('id_kasus' => $id);
			// Ubah status transaksi menjadi "habis" di kedua tabel
			$model->statuschange('kasus', 'status', 'Proses', $where);
            // $this->log_activity('User Soft Delete Kasus');
			// Kirim respons (jika diperlukan)
			return redirect()->to('home/dkasus/'.session()->get('id'));
}

public function statuspks($id){
    $model = new M_b;
			$where = array('id_kasus' => $id);
			// Ubah status transaksi menjadi "habis" di kedua tabel
			$model->statuschange('kasus', 'status', 'Proses ke Kepala Sekolah', $where);
            // $this->log_activity('User Soft Delete Kasus');
			// Kirim respons (jika diperlukan)
			return redirect()->to('home/dkasus/'.session()->get('id'));
}

public function statusselesai($id){
    $model = new M_b;
			$where = array('id_kasus' => $id);
			// Ubah status transaksi menjadi "habis" di kedua tabel
			$model->statuschange('kasus', 'status', 'Selesai', $where);
            // $this->log_activity('User Soft Delete Kasus');
			// Kirim respons (jika diperlukan)
			return redirect()->to('home/dkasus/'.session()->get('id'));
}

public function duser()
{
    $model = new M_b();

    // Adjusting the join conditions to pass column names separately
    $data = [
        'user' => $model->tampil(
            'user'
        ),
    ];

    $where = ['setting.id_setting' => 1];
    $data1 = [
        'setting' => $model->getwhere('setting', $where),
    ];
    $where1 = array('level' =>session()->get('level'));
		$data2['menu'] = $model->getwhere('menu', $where1);
		if ($data2['menu'] === null || !isset($data2['menu']->dashboard)) {
			return redirect()->to('home/login');
		}
		if (session()->get('level') == 1 || $data2['menu']->data == 1) {
    echo view('header');
    echo view('menu', $data1,$data2);
    echo view('duser', $data); // Pass the data array to the view
    echo view('footer');
    }else{
        return redirect()->to('home/login');
    }
}

public function resetpasswords($id){
    $model = new M_b;
			$where = array('id_user' => $id);
			// Ubah status transaksi menjadi "habis" di kedua tabel
			$model->statuschange('user', 'password', md5('sph2024'), $where);
            // $this->log_activity('User Soft Delete Kasus');
			// Kirim respons (jika diperlukan)
			return redirect()->to('home/suser');
}

public function resetpassword($id){
    $model = new M_b;
			$where = array('id_user' => $id);
			// Ubah status transaksi menjadi "habis" di kedua tabel
			$model->statuschange('user', 'password', md5('sph2024'), $where);
            // $this->log_activity('User Soft Delete Kasus');
			// Kirim respons (jika diperlukan)
			return redirect()->to('home/duser');
}

public function restoredeleteuser($id){
    $model = new M_b;
			$where = array('id_user' => $id);
			// Ubah status transaksi menjadi "habis" di kedua tabel
			$model->statuschange('user', 'delete', Null, $where);
            // $this->log_activity('User Soft Delete Kasus');
			// Kirim respons (jika diperlukan)
			return redirect()->to('home/suser');
}
public function t_user(){
    $model = new M_b();
    $where = ['kelas.view' => 2];
    $data = [
        'kelas' => $model->tampilwhere(
            'kelas', $where
        ),
    ];

    $where = ['setting.id_setting' => 1];
    $data1 = [
        'setting' => $model->getwhere('setting', $where),
    ];
    $where1 = array('level' =>session()->get('level'));
		$data2['menu'] = $model->getwhere('menu', $where1);
		if ($data2['menu'] === null || !isset($data2['menu']->dashboard)) {
			return redirect()->to('Home/login');
		}
	if (session()->get('level') == 1 || $data2['menu']->dashboard == 1) {
    echo view('header');
    echo view('menu', $data1,$data2);
    echo view('t_user',$data); // Pass the data array to the view
    echo view('footer');
    }else{
        return redirect()->to('home/login');
    }
}
public function post_t_user(Request $request) {
    $model = new M_b(); // Assuming you have a User model

    // Fetch the form data using $request

    $username = $request->input('username');
    $nama = $request->input('nama');
    $kelas = $request->input('kelas');

    // Prepare data for the database
    $data = [
        'username' => $username,
        'nama' => $nama,
        'id_kelas' => $kelas,
        'password' => md5('sph123'),
        'id_level' => 1,
        'delete' => null,  // Use integer for 'delete'
    ];


    // Insert the user data into the 'users' table
    $model->tambah('user',$data); // Assuming you're using Eloquent to insert the data

    // Redirect to the user management page
    return redirect()->to('/home/duser'); // Use named routes in Laravel
}

public function datakasus()
{
    $model = new M_b();
    $where = ['kasus.delete' => null];

    // Adjusting the join conditions to pass column names separately
    $data = [
        'kasus' => $model->joinResult2(
            'kasus',
            'user', ['kasus.id_user', 'user.id_user'],
            'kelas', ['user.id_kelas', 'kelas.id_kelas'],
            $where
        ),
    ];

    $where = ['setting.id_setting' => 1];
    $data1 = [
        'setting' => $model->getwhere('setting', $where),
    ];
    $where1 = array('level' =>session()->get('level'));
		$data2['menu'] = $model->getwhere('menu', $where1);
	if ($data2['menu'] === null || !isset($data2['menu']->dashboard)) {
			return redirect()->to('home/login');
	}
	if (session()->get('level') == 1 || $data2['menu']->dashboard == 1) {
    echo view('header');
    echo view('menu',$data1, $data2);
    echo view('dkasus', $data); // Pass the data array to the view
    echo view('footer');
    }else{
        return redirect()->to('home/login');
    }
}

public function skasus()
{
    $model = new M_b();
    $where = function($query) {
        $query->whereNotNull('kasus.delete'); // Using whereNotNull for condition
    };
    $data = [
        'kasus' => $model->joinResult2(
            'kasus',
            'user', ['kasus.id_user', 'user.id_user'],
            'kelas', ['user.id_kelas', 'kelas.id_kelas'],
            $where
        ),
    ];

    $where = ['setting.id_setting' => 1];
    $data1 = [
        'setting' => $model->getwhere('setting', $where),
    ];
    $where1 = array('level' =>session()->get('level'));
		$data2['menu'] = $model->getwhere('menu', $where1);
	if ($data2['menu'] === null || !isset($data2['menu']->dashboard)) {
			return redirect()->to('home/login');
	}
	if (session()->get('level') == 1 || $data2['menu']->data == 1) {
    echo view('header');
    echo view('menu',$data1, $data2);
    echo view('dkasus', $data); // Pass the data array to the view
    echo view('footer');
    }else{
        return redirect()->to('home/login');
    }
}

public function suser()
{
    $model = new M_b();

    $where = function($query) {
        $query->whereNotNull('user.delete'); // Using whereNotNull for condition
    };
    // Adjusting the join conditions to pass column names separately
    $data = [
        'user' => $model->tampilwhere(
            'user',$where
        ),
    ];

    $where = ['setting.id_setting' => 1];
    $data1 = [
        'setting' => $model->getwhere('setting', $where),
    ];
    $where1 = array('level' =>session()->get('level'));
		$data2['menu'] = $model->getwhere('menu', $where1);
		if ($data2['menu'] === null || !isset($data2['menu']->dashboard)) {
			return redirect()->to('home/login');
		}
		if (session()->get('level') == 1 || $data2['menu']->data == 1) {
    echo view('header');
    echo view('menu', $data1,$data2);
    echo view('duser', $data); // Pass the data array to the view
    echo view('footer');
    }else{
        return redirect()->to('home/login');
    }
}

public function detailuser($id){
    $model = new M_b();
		$where1 = array('level' =>session()->get('level'));
		$data2['menu'] = $model->getwhere('menu', $where1);
		if ($data2['menu'] === null || !isset($data2['menu']->dashboard)) {
			return redirect()->to('Home/login');
		}
	if (session()->get('level') == 1 || $data2['menu']->data == 1) {
            
            // $this->log_activity('User Membuka Detail User');
            $where = ['user.id_kelas' => session()->get('kelas')];
            $data3['user'] = $model->joinRows('kelas', 'user', ['kelas.id_kelas', 'user.id_kelas'], $where);
            $where = ['user.id_user' => $id];
            $data4['users'] = $model->joinRows('kelas', 'user', ['kelas.id_kelas', 'user.id_kelas'], $where);
            $where1 = array('view' => '2');
            $data['kelas'] = $model->tampilwhere('kelas',$where1);
            $where = array('setting.id_setting' => 1);
            $data1['setting'] = $model->getwhere('setting', $where);
            echo view('header', $data1);
            echo view('menu', $data1,$data2);
            echo view('detailuser',$data4,$data,$data3);
            echo view('footer');
    } else {
		return redirect()->to('home/login');
	}
}

public function profile(){
    $model = new M_b();
		$where1 = array('level' =>session()->get('level'));
		$data2['menu'] = $model->getwhere('menu', $where1);
		if ($data2['menu'] === null || !isset($data2['menu']->dashboard)) {
			return redirect()->to('Home/login');
		}
	if (session()->get('level') == 1 || $data2['menu']->data == 1) {
            
            // $this->log_activity('User Membuka Detail User');
            $where = ['user.id_kelas' => session()->get('kelas')];
            $data3['user'] = $model->joinRows('kelas', 'user', ['kelas.id_kelas', 'user.id_kelas'], $where);
            $where = ['user.id_user' => session()->get('id')];
            $data4['users'] = $model->joinRows('kelas', 'user', ['kelas.id_kelas', 'user.id_kelas'], $where);
            $where1 = array('view' => '2');
            $data['kelas'] = $model->tampilwhere('kelas',$where1);
            $where = array('setting.id_setting' => 1);
            $data1['setting'] = $model->getwhere('setting', $where);
            echo view('header', $data1);
            echo view('menu', $data1,$data2);
            echo view('detailuser',$data4,$data,$data3);
            echo view('footer');
    } else {
		return redirect()->to('home/login');
	}
}

public function post_aksi_euser(Request $request) {
    $model = new M_b();
    
    $uploadedFile = $request->file('image'); 
    $username = $request->input('username');
    $nama = $request->input('nama');
    $kelas = $request->input('kelas');
    $id = $request->input('id');
    $where = ['id_user' => $id];

    if ($uploadedFile && $uploadedFile->isValid()) {
        $foto = $uploadedFile->getClientOriginalName(); 
        $model->upload1($uploadedFile); 

        $data = [
            'username' => $username,
            'nama' => $nama,
            'foto' => $foto,
            'id_kelas' => $kelas,
            'update_by' => session()->get('id'),
            'update_at' => now()
        ];
    } else {
        $data = [
            'username' => $username,
            'nama' => $nama,
            'id_kelas' => $kelas,
            'update_by' => session()->get('id'),
            'update_at' => now()
        ];
    }


    $model->edit('user', $data, $where);
    return redirect()->to('home/detailuser/' . $id);
}

public function hapus_user($id){
    $model = new M_b();
    $where = array('id_user' => $id);
    $model->hapus('user', $where);
    return redirect()->to('home/duser');
}

public function setting(){
    $model = new M_b();
    $where1 = array('level' =>session()->get('level'));
		$data2['menu'] = $model->getwhere('menu', $where1);
		if ($data2['menu'] === null || !isset($data2['menu']->dashboard)) {
			return redirect()->to('Home/login');
		}
	if (session()->get('level') == 1 || $data2['menu']->data == 1) {
    $model = new M_b();
    $where = ['setting.id_setting' => 1];
    $data1 = [
        'setting' => $model->getwhere('setting', $where),
    ];

    echo view('header', $data1);
    echo view('menu', $data1,$data2);
    echo view('setting',$data1);
    echo view('footer');
} else {
    return redirect()->to('home/login');
}
}

public function post_aksi_esetting(Request $request) {
    // Ambil model untuk melakukan operasi database
    $model = new M_b();

    // Ambil data POST
    $jwebsite = $request->input('judul_website');
    $t_icon = $request->file('t_icon');
    $m_icon = $request->file('m_icon');
    $l_icon = $request->file('l_icon');
    $l_image = $request->file('l_image');
    $id = $request->input('id');

    $where = array('id_setting' => $id);
    // Buat array data yang akan diupdate
    $data = ['nama_website' => $jwebsite];

    // Handle file upload jika valid
    if ($t_icon && $t_icon->isValid()) {
        $foto_t_icon = $t_icon->getClientOriginalName();
        $t_icon->move(public_path('images/'), $foto_t_icon); // Simpan ke folder public/images/website
        $data['icon_website'] = $foto_t_icon;
    }

    if ($m_icon && $m_icon->isValid()) {
        $foto_m_icon = $m_icon->getClientOriginalName();
        $m_icon->move(public_path('images/'), $foto_m_icon); // Simpan ke folder public/images/website
        $data['icon_menu'] = $foto_m_icon;
    }

    if ($l_icon && $l_icon->isValid()) {
        $foto_l_icon = $l_icon->getClientOriginalName();
        $l_icon->move(public_path('images/'), $foto_l_icon); // Simpan ke folder public/images/website
        $data['login_icon'] = $foto_l_icon;
    }

    if ($l_image && $l_image->isValid()) {
        $foto_l_image = $l_image->getClientOriginalName();
        $l_image->move(public_path('images/website'), $foto_l_image); // Simpan ke folder public/images/website
        $data['login_image'] = $foto_l_image;
    }



    // Update data di database menggunakan model Laravel
    $model->edit('setting', $data, $where);

    // Redirect kembali ke halaman setting setelah proses selesai
    return redirect()->to('home/setting');
}


public function post_updateMenuVisibility(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'id_menu' => 'required|integer',
        // Add more validation rules as needed
    ]);
    
    $data = $request->except('_token', 'id_menu');
    $id_menu = $request->input('id_menu');

    // Prepare the data for update (setting unchecked fields to 0)
    $updateData = [];
    foreach ($data as $key => $value) {
        // Check if the checkbox was checked (it will only be present if checked)
        $updateData[$key] = $value ? 1 : 0;
    }

    // Update the menu visibility in the database
    $updated = DB::table('menu')
        ->where('id_menu', $id_menu)
        ->update($updateData);
    // print_r($data);
    if ($updated) {
        // Set flash message for successful update
        return redirect()->back()->with('message', 'Menu visibility updated successfully.');
    } else {
        // Handle case where no rows were updated
        return redirect()->back()->with('error', 'Failed to update menu visibility. Please try again.');
    }
}


public function managemenu()
{
    $model = new M_b();
    
    // Fetch the menu based on the user's level
    $where1 = array('level' => session()->get('level'));
    $data2['menu'] = $model->getwhere('menu', $where1);// Use first() to get a single record
    
    // Redirect to login if no menu or dashboard found
    if ($data2['menu'] === null || !isset($data2['menu']->dashboard)) {
        return redirect()->to('Home/login');
    }
    
    // Check if level is 1 or 'data' permission is enabled
    if (session()->get('level') == 1 || $data2['menu']->data == 1) {
        // Fetch user details based on session id
        $userId = session()->get('id');
        $level = 3;
        
        // Get user details
        $user = DB::table('user')->where('id_user', $userId)->first();
        
        // Fetch the menu data based on the user's level (single result)
        $where1 = array('level' => session()->get('level'));
        $data3['menu'] = $model->getwhere('menu', $where1);
        
        // Fetch all menu items for level 3 (collection)
        $menuLevel3 = DB::table('menu')->where('level', '3')->get();
        $menu = DB::table('menu')->where('level', $level)->first();

        // Fetch settings
        $where = ['id_setting' => 1];
        $data['setting'] = $model->getwhere('setting', $where);
        
        // Get the column names from the 'menu' table, excluding 'id_menu' and 'level'
        $columns = Schema::getColumnListing('menu');
        $columns = array_diff($columns, ['id_menu', 'level']); // Exclude unwanted columns
        
        // Fetch specific menu details (where id_menu is 2, for example)
        $menuDetails = DB::table('menu')->where('id_menu', 2)->first();
        
        // Load the views and echo them
        echo view('header');
        echo view('menu', $data2,$data); // Pass menu and user data
        echo view('managemenu', [
            'user' => $user,
            'menue' => $menuLevel3,
            'columns' => $columns,
            'menu' => $menu,
            'menuDetails' => $menuDetails
        ], $data);
        echo view('footer');
    } else {
        return redirect()->to('home/login');
    }
}

public function laporan(){
    $model = new M_b();
    $where1 = array('level' =>session()->get('level'));
    $data['menu'] = $model->getwhere('menu', $where1);
    if ($data['menu'] === null || !isset($data['menu']->dashboard)) {
        return redirect()->to('Home/login');
    }
    if (session()->get('level') == 1 || $data['menu']->data == 1) {
    $model = new M_b();
    $where = array('user.id_kelas' => session()->get('kelas'));
    $data['user'] = $model->joinRowss('kelas', 'user', 'kelas.id_kelas', 'user.id_kelas', $where);
    $where = array('setting.id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where);
    $where1 = "view IS NOT NULL";
    $tables = ['user', 'kasus', 'kelas'];
        $joinConditions = [
            ['user.id_user', 'kasus.id_user'], // Join user with kasus
            ['user.id_kelas', 'kelas.id_kelas'] // Join user with kelas
        ];

    $data['kelas'] = $model->joinMultiple($tables, $joinConditions);
    echo view('header', $data);
    echo view('menu', $data);
    echo view('laporan',$data);
    echo view('footer');
} else {
    return redirect()->to('home/login');
}
}

public function kasus_pdf(Request $request)
{
    $model = new M_b();
    $where1 = array('level' => session()->get('level'));
    $data['menu'] = $model->getwhere('menu', $where1);
    
    if ($data['menu'] === null || !isset($data['menu']->dashboard)) {
        return redirect()->to('Home/login');
    }

    if (session()->get('level') == 1 || $data['menu']->dashboard == 1) {
        $where = array('user.id_kelas' => session()->get('kelas'));
        $data['user'] = $model->joinrowss('kelas', 'user', 'kelas.id_kelas', 'user.id_kelas', $where);
        $where = array('setting.id_setting' => 1);
        $data['setting'] = $model->getwhere('setting', $where);
        
        // Ambil parameter tanggal dari query string
        $startDate = $request->get('start_date'); // Ubah di sini
        $endDate = $request->get('end_date'); // Ubah di sini

        // Set tanggal default jika tidak ada filter
        if (!$startDate) {
            $startDate = '0000-00-00'; // Atau tanggal default yang sesuai
        }
        if (!$endDate) {
            $endDate = '9999-12-31'; // Atau tanggal default yang sesuai
        }

        // Panggil model untuk mendapatkan data yang difilter
        $where = array('id_user' => session()->get('id'));
        $data['dua'] = $model->getwhere('user', $where);
        $data['kelas'] = $model->joinResultByDate(
            'user', 
            'kasus', 
            'user.id_user = kasus.id_user', 
            'kelas', 
            'user.id_kelas = kelas.id_kelas', 
            $startDate, 
            $endDate
        );
        
        
        // Passing data to the PDF generation file
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
        $data['kelas'] = $data['kelas']; // make sure this is available for the PDF
        
        // Load the PDF generation script with data
        return view('generate_pdf', $data);
    } else {
        return redirect()->to('home/login');
    }
}

public function kasus_excel(Request $request)
{
    $model = new M_b();
    $where1 = array('level' => session()->get('level'));
    $data['menu'] = $model->getwhere('menu', $where1);
    
    if ($data['menu'] === null || !isset($data['menu']->dashboard)) {
        return redirect()->to('Home/login');
    }

    if (session()->get('level') == 1 || $data['menu']->dashboard == 1) {
        $where = array('user.id_kelas' => session()->get('kelas'));
        $data['user'] = $model->joinrowss('kelas', 'user', 'kelas.id_kelas', 'user.id_kelas', $where);
        $where = array('setting.id_setting' => 1);
        $data['setting'] = $model->getwhere('setting', $where);
        
        // Ambil parameter tanggal dari query string
        $startDate = $request->get('start_date'); // Ubah di sini
        $endDate = $request->get('end_date'); // Ubah di sini

        // Set tanggal default jika tidak ada filter
        if (!$startDate) {
            $startDate = '0000-00-00'; // Atau tanggal default yang sesuai
        }
        if (!$endDate) {
            $endDate = '9999-12-31'; // Atau tanggal default yang sesuai
        }

        // Panggil model untuk mendapatkan data yang difilter
        $where = array('id_user' => session()->get('id'));
        $data['dua'] = $model->getwhere('user', $where);
        $data['kelas'] = $model->joinResultByDate(
            'user', 
            'kasus', 
            'user.id_user = kasus.id_user', 
            'kelas', 
            'user.id_kelas = kelas.id_kelas', 
            $startDate, 
            $endDate
        );
        
        
        // Passing data to the PDF generation file
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
        $data['kelas'] = $data['kelas']; // make sure this is available for the PDF
        
        // Load the PDF generation script with data
        return view('generate_excel', $data);
    } else {
        return redirect()->to('home/login');
    }
}

public function kasus_windows(Request $request)
{
    $model = new M_b();
    $where1 = array('level' => session()->get('level'));
    $data['menu'] = $model->getwhere('menu', $where1);
    
    if ($data['menu'] === null || !isset($data['menu']->dashboard)) {
        return redirect()->to('Home/login');
    }

    if (session()->get('level') == 1 || $data['menu']->dashboard == 1) {
        $where = array('user.id_kelas' => session()->get('kelas'));
        $data['user'] = $model->joinrowss('kelas', 'user', 'kelas.id_kelas', 'user.id_kelas', $where);
        $where = array('setting.id_setting' => 1);
        $data['setting'] = $model->getwhere('setting', $where);
        
        // Ambil parameter tanggal dari query string
        $startDate = $request->get('start_date'); // Ubah di sini
        $endDate = $request->get('end_date'); // Ubah di sini

        // Set tanggal default jika tidak ada filter
        if (!$startDate) {
            $startDate = '0000-00-00'; // Atau tanggal default yang sesuai
        }
        if (!$endDate) {
            $endDate = '9999-12-31'; // Atau tanggal default yang sesuai
        }

        // Panggil model untuk mendapatkan data yang difilter
        $where = array('id_user' => session()->get('id'));
        $data['dua'] = $model->getwhere('user', $where);
        $data['kelas'] = $model->joinResultByDate(
            'user', 
            'kasus', 
            'user.id_user = kasus.id_user', 
            'kelas', 
            'user.id_kelas = kelas.id_kelas', 
            $startDate, 
            $endDate
        );
        
        
        // Passing data to the PDF generation file
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
        $data['kelas'] = $data['kelas']; // make sure this is available for the PDF
        
        // Load the PDF generation script with data
        return view('generate_windows', $data);
    } else {
        return redirect()->to('home/login');
    }
}

}


