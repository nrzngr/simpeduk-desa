<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public function __construct() {
		parent::__construct();
		if ($this->session->userdata('login') == TRUE) {
			redirect(base_url());
		}
		$this->load->library('form_validation');
		$this->load->model('m_login');
	}

	public function index() {
		$this->load->view('login');
	}

	public function auth() {
		$this->form_validation->set_rules('username', 'username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		if ($this->form_validation->run() != false) {
			$data_login = array(
				'username' => htmlspecialchars($this->input->post('username', TRUE), ENT_QUOTES),
				'password' => md5(htmlspecialchars($this->input->post('password', TRUE), ENT_QUOTES)),
			);
			$user = $this->m_login->login($this->security->xss_clean($data_login));
			if ($user->num_rows() > 0) {
				$data = $user->row_array();
				$this->session->set_userdata('login', TRUE);
				$this->session->set_userdata('username', $data['username']);
				$this->session->set_userdata('nama_petugas', $data['nama_petugas']);
				$this->session->set_userdata('level', $data['level']);
				redirect(base_url());
			} else {
				$this->session->set_flashdata('gagal', 'Username atau password salah!');
				redirect(base_url('login'));
			}
		}
	}

	public function register()
	{
		if($this->input->post())
		{
			$data = $this->input->post();
			$data['password'] = md5(htmlspecialchars($this->input->post('password', TRUE), ENT_QUOTES));
			unset($data['Submit']);
			$register = $this->m_login->register($data);
			if(!$register){
				redirect('login/auth');
				$this->session->set_flashdata('gagal','Gagal Registrasi! Silahkan Masukan Data Dengan benar!');
				redirect('login/register');
				return;
			}
			$this->session->set_flashdata('berhasil', 'Berhasil Registrasi! Silahkan Login!');
			redirect('login/index');
			return;
		}
		return $this->load->view('register');
	}

	/**
	 * Added Keperluan Lupa Password
	 */

	 public function lupapassword()
	 {
		if($this->input->post())
		{
			$data = $this->m_login->check_user($this->input->post('email'));
			return $this->sendemail($data);
		}
		return $this->load->view('lupapassword');
	 }

	 public function veriflupapass()
	 {
		if($this->input->post())
		{
			// cek jika password 1 & 2 sama
			$data = $this->input->post();
			// var_dump($data);die();
			if($data['password1'] != $data['password2'])
			{
				echo 'Terjadi Kesalahan, Password tidak sama!';
				return;
			}
			// update password di tabel
			$update_password = $this->m_login->update_password($data['username'],md5($data['password1']));
			$update_password = $this->m_login->update_status_user_lupa($data['username']);
			if(!$update_password)
			{
				echo 'Terjadi Kesalahan!';
				return;
			}
			return $this->load->view('successlupapassword');
			
			// konfirmasi success ganti password!

		}
		// disini di decrypt ambil dari inputan user q
		$encrypted = base64_decode($this->input->get('q'));
		// data bentuk json decode
		$data = json_decode($encrypted);
		if(!isset($data->username,$data->email,$data->token))
		{
			echo 'Terjadi Kesalahan, Silahkan klik link yang sesuai di email!';
			return;
		}

		// data kemudian username, email di cek ke tabel lupa_password
		// jika didalam tabel lupa_password data ada dan belum terverif(status='0')
		// check token , jika token yang diberikan user = token yang ada di db 
		$checkdata = $this->m_login->check_user_lupa_password($data);
		if(!$checkdata)
		{
			echo 'Terjadi Kesalahan, Silahkan klik link yang sesuai di email!';
			return;
		}
		// maka lanjutkan untuk tampilkan form set password dan konfirm password
		return $this->load->view('confirmlupapassword',['akun'=>$checkdata]);
	 }


	 private function generateToken($length = 5) {
		$token = '';
		// Range karakter yang diperbolehkan dalam token
		$characters = '0123456789';
		$charLength = strlen($characters);
		// Loop untuk menghasilkan token sepanjang $length
		for ($i = 0; $i < $length; $i++) {
			// Ambil karakter acak dari $characters
			$randomChar = $characters[rand(0, $charLength - 1)];
			// Tambahkan karakter acak ke token
			$token .= $randomChar;
		}
		return $token;
	}
	 public function sendemail($data)
    {
		// $encrypt = $this->input->get('q');
		// $data = json_decode(base64_decode($encrypt));
		// var_dump($data);die();
		if(!isset($data->username,$data->email))
		{
			echo 'Invalid Data';
			return;
		}
		// return $this->load->view('mail');
      // Konfigurasi email
        $config = [
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'protocol'  => 'smtp',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_user' => 'aplikasionlinee@gmail.com',  // Email gmail
            'smtp_pass'   => 'imzdzcdlbvarshhd',  // Password gmail
            'smtp_crypto' => 'ssl',
            'smtp_port'   => 465,
            'crlf'    => "\r\n",
            'newline' => "\r\n"
        ];

        // Load library email dan konfigurasinya
        $this->load->library('email', $config);

        // Email dan nama pengirim
        $this->email->from('no-reply@sistemdatapenduduk', 'Admin Sistem Pengelolaan Penduduk Warung Bambu');

        // Email penerima
        $this->email->to($data->email); // Ganti dengan email tujuan

        // Lampiran email, isi dengan url/path file
        // $this->email->attach('https://images.pexels.com/photos/169573/pexels-photo-169573.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940');

        // Subject email
        $this->email->subject('Reset Password');

        // Isi email
		$bodys = [
			'username'=>$data->username,
			'email'=>$data->email,
			'token'=>$this->generateToken()
		];

		$kirimtodb = $this->m_login->insert_user_lupa_password($bodys);
		if(!$kirimtodb)
		{
			echo 'Terjadi Kesalahan sistem!';
			return;
		}

		$datas = base64_encode(json_encode($bodys));
		$this->email->message('<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title>Verifikasi Reset Password</title>
		</head>
		<body>
			<p>Silahkan verifikasi dibawah ini untuk melanjutkan reset password:</p>
			<p><a href="'.base_url('login/veriflupapass?q='.$datas).'">Klik disini untuk verifikasi</a></p>
		</body>
		</html>
		');
		if ($this->email->send()) {
            echo 'Sukses! email berhasil dikirim.';
        } else {
            echo 'Error! email tidak dapat dikirim.';
        }
    }
}

