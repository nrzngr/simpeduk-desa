<?php

class Autentifikasi extends CI_Controller
    {
    public function index()
        {
        //Jika statusnya sudah Login, maka tidak bisa mengakses halaman Login alias dikembalikan ke tampilan User
        if ($this->session->userdata('email')) {
            redirect('user');
            }
        $this->form_validation->set_rules('email', 'Alamat Email', 'required|trim|valid_email', ['required' => 'Email harus diisi!', 'valid_email' => 'Email tidak benar!']);
        $this->form_validation->set_rules('password', 'Password', 'required|trim', ['required' => 'Password harus diisi!']);
        if ($this->form_validation->run() == false) {
            $data['judul'] = 'Login';
            $data['user'] = '';

            //Kata 'Login' merupakan nilai dari variabel judul dalam array $data yang dikirimkan ke View "Aute_Header"
            $this->load->view('templates/Aute_Header', $data);
            $this->load->view('autentifikasi/Login');
            $this->load->view('templates/Aute_Footer');
            }
        else {
            $this->_login();
            }
        }



    private function _login()
        {
        $email = ($this->input->post('email', true));
        $password = $this->input->post('password', true);

        $user = $this->ModelUser->cekData(['email' => $email])->row_array();

        //Jika User Ada
        if ($user) {
            //Jika User Sudah Aktif
            if ($user['is_active'] == 1) {
                //Cek Password
                if (password_verify($password, $user['password'])) {
                    $data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id'],
                    ];

                    $this->session->set_userdata($data);

                    if ($user['role_id'] == 1) {
                        redirect('admin');
                        }
                    else {
                        if ($user['image'] == 'default.jpg') {
                            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-message" role="alert">Silakan Ubah Profil Anda untuk Ubah Foto Profil</div>');
                            }
                        redirect('user');
                        }
                    }
                else {
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Password salah!</div>');
                    redirect('autentifikasi');
                    }
                }
            else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">User belum diaktifasi!</div>');
                redirect('autentifikasi');
                }
            }
        else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Email tidak terdaftar!</div>');
            redirect('autentifikasi');
            }
        }
    public function blok()
        {
        $this->load->view('autentifikasi/Blok');
        }

    public function gagal()
        {
        $this->load->view('autentifikasi/Gagal');
        }

    public function registrasi()
        {
        if ($this->session->userdata('email')) {
            redirect('user');
            exit;
            }
        //Membuat rule untuk input nama aga tidak boleh kosong dengan membuat pesan error dengan
        //bahasa sendiri yaitu 'Nama belum diisi!'
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required', ['required' => 'Nama belum diisi!']);

        $this->form_validation->set_rules('email', 'Alamat Email', 'required|trim|valid_email|is_unique[user.email]', ['valid_email' => 'Email tidak benar!', 'required' => 'Email belum diisi!', 'is_unique' => 'Email sudah terdaftar!']);

        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', ['matches' => 'Password tidak sama!', 'min_length' => 'Password terlalu pendek!']);

        $this->form_validation->set_rules('password2', 'Ulangi Password!', 'required|trim|matches[password1]');

        // Proses validasi dan penyimpanan data
        if ($this->form_validation->run() == false) {
            $data['judul'] = 'Registrasi Member';
            $this->load->view('templates/Aute_Header', $data);
            $this->load->view('autentifikasi/formRegistrasi');
            $this->load->view('templates/Aute_Footer');
            }
        else {
            $email = $this->input->post('email', true);
            $data = [
                'nama' => htmlspecialchars($this->input->post('nama', true)),
                'email' => htmlspecialchars($email),
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 0,
                'tanggal_input' => time(),
            ];

            // siapkan token
            $token = base64_encode(random_bytes(32));
            $user_token = [
                'email' => $email,
                'token' => $token,
                'data_created' => time(),
            ];

            // Menyimpan data menggunakan model
            $this->ModelUser->simpandata($data);
            $this->ModelUser->simpanToken($user_token);
            $this->_sendEmail($token, 'verify');

            // Set flash message
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-message" role="alert">Selamat! Akun member Anda berhasil dibuat. Silakan aktivasi akun Anda.</div>');
            redirect('autentifikasi');
            }
        }

    private function _sendEmail($token, $type)
        {
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_user' => '12220906@bsi.ac.id',
            'smtp_pass' => '2002-01-20',
            'smtp_port' => 465,
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n",
        ];

        $this->load->library('email', $config);
        $this->email->from('nim@bsi.ac.id', 'Pustaka_Booking');
        $this->email->to($this->input->post('email'));

        if ($type == 'verify') {
            $this->email->subject('Verifikasi Akun');
            $this->email->message('Klik link berikut untuk verifikasi akun Anda : <a href="' . base_url() . 'autentifikasi/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Aktifasi Disini</a>');
            }
        else if ($type == 'forgot') {
            $this->email->subject('Reset Password');
            $this->email->message('Klik link berikut untuk reset password Anda : <a href="' . base_url() . 'autentifikasi/resetpassword?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Reset Password</a>');
            }

        if ($this->email->send()) {
            return true;
            }
        else {
            echo $this->email->print_debugger();
            die;
            }
        }

    public function verify()
        {
        $email = $this->input->get('email');
        $token = $this->input->get('token');
        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        if ($user) {
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
            if ($user_token) {
                if (time() - $user_token['date_created'] < (60 * 60 * 24)) {
                    $this->db->set('is_active', 1);
                    $this->db->where('email', $email);
                    $this->db->update('user');
                    $this->db->delete('user_token', ['email' => $email]);
                    $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-message" role="alert">Email ' . $email . 'Sudah Diaktifasi. Silahkan Login</div>');
                    redirect('autentifikasi');
                    }
                else {
                    $this->db->delete('user', ['email' => $email]);
                    $this->db->delete('user_token', ['email' => $email]);
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Aktifasi Akun Gagal!! Token Expired!!</div>');
                    redirect('autentifikasi');
                    }
                }
            else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Aktifasi Akun Gagal!! Token Salah!!</div>');
                redirect('autentifikasi');
                }
            }
        else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Aktifasi Akun Gagal!! Email Salah!!</div>');
            redirect('autentifikasi');
            }
        }

    public function logout()
        {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');
        $this->session->unset_userdata('pesan', '<div class="alert alert-success alert-message" role="alert">Anda telah berhasil logout!</div>');
        redirect('autentifikasi');
        }

    public function lupaPassword()
        {
        $this->form_validation->set_rules('email', 'Alamat email', 'required|trim|valid_email', [
            'required' => 'Email harus diisi',
            'valid_email' => 'email tidak benar',
        ]);
        if ($this->form_validation->run() == false) {
            $data['judul'] = 'Lupa Password';

            $this->load->view('autentifikasi/lupaPassword');

            }
        else {
            $email = $this->input->post('email');
            $user = $this->db->get_where('user', ['email' => $email, 'is_active' => 1])->row_array();
            if ($user) {
                $token = base64_encode(random_bytes(32));
                $user_token = [
                    'email' => $email,
                    'token' => $token,
                    'date_created' => time(),
                ];
                $this->ModelUser->simpanToken($user_token);
                $this->_sendEmail($token, 'forgot');
                $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-message" role="alert">Silahkan cek email anda untuk reset password</div>');
                redirect('autentifikasi/lupaPassword');
                }
            else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Email tidak terdaftar atau belum diaktivasi</div>');
                redirect('autentifikasi/lupaPassword');
                }
            }
        }

    public function resetPassword()
        {
        $email = $this->input->get('email');
        $token = $this->input->get('token');
        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        if ($user) {
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

            if ($user_token) {
                $this->session->set_userdata('reset_email', $email);
                $this->ubahPassword();
                }
            else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Reset Password Gagal!! Token Salah!!</div>');
                redirect('autentifikasi/lupaPassword');
                }
            }
        else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Reset Password Gagal!! Email Salah!!</div>');
            redirect('autentifikasi/lupaPassword');
            }
        }

    public function ubahPassword()
        {
        if ($this->session->userdata('reset_email')) {
            redirect('autentifikasi');
            }
        $this->form_validation->set_rules('passwword_baru', 'Password Baru', 'required|trim|min_length[3]|matches[password_baru2]', [
            'matches' => 'Password Tidak Sama!!',
            'min_length' => 'Password Terlalu Pendek!!',
            'required' => 'Password Belum Diisi!!',
        ]);
        $this->form_validation->set_rules('password_baru2', 'Ulangi Password Baru', 'required|trim|matches[password_baru1]', [
            'matches' => 'Ulangi Password Tidak Sama!!',
            'required' => 'Ulangi Password Belum Diisi!!',
        ]);

        if ($this->form_validation->run() == false) {
            $data['judul'] = 'Ubah Password';
            $this->load->view('templates/Aute_header', $data);
            $this->load->view('autentifikasi/new-password');
            $this->load->view('templates/Aute_footer');

            }
        else {
            $password = password_hash($this->input->post('password_baru1'), PASSWORD_DEFAULT);
            $email = $this->session->userdata('reset_email');

            $this->db->set('password', $password);
            $this->db->where('email', $email);
            $this->db->update('user');

            $this->session->unset_userdata('reset_email');

            $this->db->delete('user_token', ['email' => $email]);

            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-message" role="alert">Password Berhasil Diubah Silahkan Login</div>');
            redirect('autentifikasi');
            }
        }
    }
