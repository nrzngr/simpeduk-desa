<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
    {
    public function __construct()
        {
        parent::__construct();
        $this->load->library('form_validation');
        }
    public function index()
        {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == false) {

            $data['title'] = 'Login page';
            $this->load->view('auth_templates/header', $data);
            $this->load->view('autentifikasi/login', $data);
            $this->load->view('auth_templates/footer');
            }
        else {
            $this->_login();
            }
        }

    public function register()
        {
        $this->form_validation->set_rules(
            'nik',
            'NIK',
            'required|trim|is_unique[data_pegawai.nik]',
            [

                'required' => 'NIK Wajib diisi',
                'is_unique' => 'NIK Sudah Terdaftar!',
            ],
        );

        $this->form_validation->set_rules(
            'email',
            'Alamat Email',
            'required|trim|valid_email|is_unique[data_pegawai.email]',
            [
                'valid_email' => 'Email Tidak Benar!!',
                'required' => 'Email Wajib diisi',
                'is_unique' => 'Email Sudah Terdaftar!',
            ],
        );

        $this->form_validation->set_rules(
            'nama_pegawai',
            'Nama Lengkap',
            'required',
            [
                'required' => 'Nama Wajib diisi',
            ],
        );

        $this->form_validation->set_rules(
            'jenis_kelamin',
            'Jenis Kelamin',
            'required',
            [
                'required' => 'Jenis Kelamin Wajib diisi',
            ],
        );

        $this->form_validation->set_rules(
            'jabatan',
            'Jabatan',
            'required',
            [
                'required' => 'Jabatan Wajib diisi',
            ],
        );

        $this->form_validation->set_rules(
            'nama_pegawai',
            'Nama Lengkap',
            'required',
            [
                'required' => 'Nama Wajib diisi',
            ],
        );

        $this->form_validation->set_rules(
            'username',
            'username',
            'required|trim|is_unique[data_pegawai.username]',
            [
                'valid_email' => 'Username Tidak Benar!!',
                'required' => 'Username Wajib diisi',
                'is_unique' => 'Username Sudah Terdaftar!',
            ],
        );

        $this->form_validation->set_rules(
            'password',
            'Password',
            'required|trim|min_length[3]|matches[password2]',
            [
                'matches' => 'Password Tidak Sama!!',
                'required' => 'Password Wajib diisi',
                'min_length' => 'Password Terlalu Pendek',
            ],
        );

        $this->form_validation->set_rules(
            'password2',
            'RepeatPassword',
            'required|trim|matches[password]',
            [
                'matches' => 'Password Tidak Sama!!',
                'required' => 'Password Wajib diisi',
                'min_length' => 'Password Terlalu Pendek',
            ],
        );

        if ($this->form_validation->run() == false) {

            $data['title'] = "Registrasi";
            $this->load->view('auth_templates/header', $data);
            $this->load->view('autentifikasi/register', $data);
            $this->load->view('auth_templates/footer');
            }
        else {
            $email = $this->input->post('email', true);
            $data = [
                'nik' => htmlspecialchars($this->input->post('nik', true)),
                'nama_pegawai' => htmlspecialchars($this->input->post('nama_pegawai', true)),
                'username' => htmlspecialchars($this->input->post('username', true)),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'jenis_kelamin' => $this->input->post('jenis_kelamin', true),
                'jabatan' => $this->input->post('jabatan', true),
                'tanggal_masuk' => date("Y-m-d"),
                'status' => 'Pegawai Tetap',
                'photo' => 'default.jpg',
                'hak_akses' => 2,
                'is_active' => 0,
                'email' => htmlspecialchars($email),


            ];

            $token = base64_encode(random_bytes(32));
            $user_token = [
                'email' => $email,
                'token' => $token,
                'date_created' => time(),
            ];

            // $this->ModelUser->simpanData($data); //menggunakan model
            $this->ModelUser->simpanData($data);
            $this->ModelUser->simpanToken($user_token);
            $this->_sendEmail($token, 'verify');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Selamat!! akun member anda sudah dibuat. Silahkan Aktivasi Akun anda
            </div>');
            redirect('auth');
            }
        }

    private function _sendEmail($token, $type)
        {
        ini_set('SMTP', 'smtp.googlemail.com');
        ini_set('smtp_port', 465); // Change this to the appropriate port

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
        $this->email->initialize($config);  //tambahkan baris ini
        $this->email->from('12220906@bsi.ac.id', 'Penggajian');
        $this->email->to($this->input->post('email'));

        if ($type == 'verify') {
            $this->email->subject('Verifikasi Akun');
            $this->email->message('klik link berikut untuk verifikasi akun anda : <a href="' . base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '"> Aktifkan Disini</a>');

            }
        else if ($type == 'forgot') {
            $this->email->subject('Reset Password');
            $this->email->message('Klik link berikut untuk reset password anda: <a href="' . base_url() . 'auth/resetPassword?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Reset Password</a>');

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

        $user = $this->db->get_where('data_pegawai', ['email' => $email])->row_array();

        if ($user) {
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

            if ($user_token) {
                if (time() - $user_token['date_created'] < (60 * 60 * 24)) {
                    $this->db->set('is_active', 1);
                    $this->db->where('email', $email);
                    $this->db->update('data_pegawai');

                    $this->db->delete('user_token', ['email' => $email]);
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">' . $email . ' berhasil diaktivasi! silahkan login
                    </div>');
                    redirect('auth');
                    }
                else {
                    $this->db->delete('data_pegawai', ['email' => $email]);
                    $this->db->delete('user_token', ['token' => $user_token]);

                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Verifikasi akun gagal! token expired
                    </div>');
                    redirect('auth');
                    }
                }
            else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Verifikasi akun gagal! token tidak valid
                </div>');
                redirect('auth');
                }

            }
        else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Verifikasi akun gagal! email tidak valid
            </div>');
            redirect('auth');
            }
        }

    private function _login()
        {
        $email = htmlspecialchars($this->input->post('email', true));
        $password = $this->input->post('password', true);

        $user = $this->ModelUser->cekData(['email' => $email])->row_array();

        if ($user) {
            if ($user['is_active'] == 1) {
                if (password_verify($password, $user['password'])) {
                    $data = [
                        'email' => $user['email'],
                        'hak_akses' => $user['hak_akses'],
                    ];
                    $this->session->set_userdata($data);

                    if ($user['hak_akses'] == 1) {
                        redirect('admin/Dashboard');
                        }
                    else {
                        if ($user['image'] == 'default.jpg') {
                            $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-message" role="alert">Silahkan
                                Ubah Profile Anda untuk Ubah Photo Profil</div>');
                            }
                        redirect('pegawai/Dashboard');
                        }
                    }
                else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert"> Password anda salah</div>');
                    redirect('auth');
                    }
                }
            else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email belum teraktivasi
            </div>');
                redirect('auth');
                }
            }
        else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email tidak teregistrasi
            </div>');
            redirect('auth');
            }

        }

    public function logout()
        {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('hak_akses');

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Anda berhasil logout
            </div>');
        redirect('auth');
        }

    public function forgotpassword()
        {
        $this->form_validation->set_rules(
            'email',
            'Email',
            'required|trim|valid_email',
        );

        if ($this->form_validation->run() == false) {

            $data['title'] = 'Forgot Password';
            $this->load->view('auth_templates/header', $data);
            $this->load->view('autentifikasi/Lupa-Password', $data);
            $this->load->view('auth_templates/footer');
            }
        else {
            $email = $this->input->post('email');
            $user = $this->db->get_where('data_pegawai', ['email' => $email, 'is_active' => 1])->row_array();

            if ($user) {
                $token = base64_encode(random_bytes(32));
                $user_token = [
                    'email' => $email,
                    'token' => $token,
                    'date_created' => time(),
                ];

                $this->db->insert('user_token', $user_token);
                $this->_sendEmail($token, 'forgot');

                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert"> Cek email anda untuk reset password
            </div>');
                redirect('auth/forgotPassword');

                }
            else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email belum terdaftar atau belum teraktivasi
            </div>');
                redirect('auth/forgotPassword');
                }
            }
        }

    public function resetPassword()
        {
        $email = $this->input->get('email');
        $token = $this->input->get('token');
        $user = $this->db->get_where('data_pegawai', ['email' => $email])->row_array();

        if ($user) {
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

            if ($user_token) {
                $this->session->set_userdata('reset_email', $email);
                $this->changePassword();
                }
            else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Reset Password gagal! token salah
            </div>');
                redirect('auth');
                }
            }
        else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Reset Password gagal! email salah
            </div>');
            redirect('auth');
            }
        }

    public function changePassword()
        {
        if (!$this->session->userdata('reset_email')) {
            redirect('auth');
            }

        $this->form_validation->set_rules(
            'password',
            'Password baru',
            'required|trim|min_length[3]',
        );
        $this->form_validation->set_rules(
            'password2',
            'Ulangi password baru',
            'required|trim|min_length[3]|matches[password]',
        );

        if ($this->form_validation->run() == false) {

            $data['title'] = 'Change Password';
            $this->load->view('auth_templates/header', $data);
            $this->load->view('formGantiPassword', $data);
            $this->load->view('auth_templates/footer');
            }
        else {
            $password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            $email = $this->session->userdata('reset_email');
            $this->db->set('password', $password);
            $this->db->where('email', $email);
            $this->db->update('data_pegawai');

            $this->session->unset_userdata('reset_email');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password berhasil diubah, silahkan login
            </div>');
            redirect('auth');
            }
        }
    }

?>
