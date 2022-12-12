<?php 

class Auth extends CI_Controller
{
    public function index()
    {
        //jika statusnya sudah login, maka tidak bisa mengakses halaman login alias dikembalikan ke tampilan user 
        if ($this->session->userdata('email_user')) {
            redirect('user');
        }
        $this->form_validation->set_rules(
            'email',
            'Alamat Email',
            'required|trim|valid_email',
            ['required' => 'Email Harus diisi!!', 'valid_email' => 'Email Tidak Benar!!']
        );
        $this->form_validation->set_rules(
            'password',
            'Password',
            'required|trim',
            ['required' => 'Password Harus diisi']
        );
        if ($this->form_validation->run() == false) {
            $data['judul'] = 'Login';
            $data['user'] = ''; //kata 'login' merupakan nilai dari variabel judul dalam array $data dikirimkan ke view aute_header 
            $this->load->view('template/aute_header', $data);
            $this->load->view('auth/login');
            $this->load->view('template/aute_footer');
        } else {
            $this->_login();
        }
    }

    private function _login()
    {
        $this->load->model('ModelUser');
        $email = htmlspecialchars($this->input->post('email', true));
        $password = md5($this->input->post('password', true));
        // var_dump($email, $password);
        // die;
        $user = $this->ModelUser->cekData(['email_user' => $email])->row_array(); //jika usernya ada 
            if ($user) { //jika user sudah aktif 
                if ($user['status_user'] == 1) {
                    //cek password 
                    if ($password==$user['password_user']) {
                        $data = ['email_user' => $user['email_user'], 'role_user' => $user['role_user']];
                        $this->session->set_userdata($data);
                        if ($user['role_user'] == 1) {
                            redirect('admin');
                        } else {
                            if ($user['img_user'] == 'default.jpg') {
                                $this->session->set_flashdata('pesan', '<div class="alert alert-info alert-message" role="alert">Silahkan Ubah Profile Anda untuk Ubah Photo Profil</div>');
                            }
                            redirect('user');
                        }
                    } else {
                        $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Password salah!!</div>');
                        redirect('auth');
                    }
                } else {
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">User belum diaktifasi!!</div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Email tidak terdaftar!!</div>');
                redirect('auth');
        }
    }

    public function cekData($where = null) {
        return $this->db->get_where('user', $where); 
    }
    public function blok()
    {
        $this->load->view('auth/blok');
    }
    public function gagal()
    {
        $this->load->view('auth/gagal');
    }
    public function registrasi()
    { 
        if ($this->session->userdata('email_user')) 
        { 
            redirect('user'); 
        } 
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required', [ 'required' => 'Nama Belum diisi!!' ]);
        $this->form_validation->set_rules('email', 'Alamat Email', 'required|trim|valid_email|is_unique[user.email_user]', [ 'valid_email' => 'Email Tidak Benar!!', 'required' => 'Email Belum diisi!!', 'is_unique' => 'Email Sudah Terdaftar!' ]); 
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [ 'matches' => 'Password Tidak Sama!!', 'min_length' => 'Password Terlalu Pendek' ]); 
        $this->form_validation->set_rules('password2', 'Repeat Password', 'required|trim|matches[password1]'); 
        if ($this->form_validation->run() == false) { 
            $data['judul'] = 'Registrasi Member'; 
            $this->load->view('template/aute_header', $data); 
            $this->load->view('auth/registrasi'); 
            $this->load->view('template/aute_footer'); 
        } 
        else {
            date_default_timezone_set('Asia/Jakarta');
            $email = $this->input->post('email', true);
            $password = md5($this->input->post('password1', true)); 
            $data = [ 
                'nama_user' => htmlspecialchars($this->input->post('nama', true)), 
                'email_user' => htmlspecialchars($email), 
                'password_user' => $password, 
                'img_user' => 'default.jpg', 
                'role_user' => 2, 
                'status_user' => 0, 
                'tgl' => date("Y-m-d h:i:s")
            ]; 
            $this->ModelUser->simpanData($data); 
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-message" role="alert">Selamat!! akun member anda sudah dibuat. Silahkan Aktivasi Akun anda</div>'); redirect('auth'); 
        } 
    }
    // public function registrasi()
    // {
    //     if ($this->session->userdata('email')) {
    //     redirect('user');
    //     }
    //     $this->form_validation->set_rules('nama', 'Nama Lengkap', 
    //     'required', [
    //     'required' => 'Nama Belum diis!!'
    //     ]);
    //     $this->form_validation->set_rules('email', 'Alamat Email', 
    //     'required|trim|valid_email|is_unique[user.email]', [
    //     'valid_email' => 'Email Tidak Benar!!',
    //     'required' => 'Email Belum diisi!!',
    //     'is_unique' => 'Email Sudah Terdaftar!'
    //     ]);
    //     $this->form_validation->set_rules('password1', 'Password', 
    //     'required|trim|min_length[3]|matches[password2]', [
    //     'matches' => 'Password Tidak Sama!!',
    //     'min_length' => 'Password Terlalu Pendek'
    //     ]);
    //     $this->form_validation->set_rules('password2', 'Repeat 
    //     Password', 'required|trim|matches[password1]');
    //     if ($this->form_validation->run() == false) {
    //         $data['judul'] = 'Registrasi Member';
    //         $this->load->view('template/aute_header', $data);
    //         $this->load->view('auth/registrasi');
    //         $this->load->view('template/aute_footer');
    //     } else {
    //         $email = $this->input->post('email', true);
    //         $data = [
    //             'nama' => htmlspecialchars($this->input->post('nama', true)),
    //             'email' => htmlspecialchars($email),
    //             'image' => 'default.jpg',
    //             'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
    //             'role_id' => 2,
    //             'is_active' => 0,
    //             'tanggal_input' => time()
    //         ];
    //         $this->ModelUser->simpanData($data);

    //         $this->session->set_flashdata('pesan', '<div 
    //         class="alert alert-success alert-message" role="alert">Selamat!! 
    //         akun member anda sudah dibuat. Silahkan Aktivasi Akun anda</div>');
    //         redirect('auth');
    //     }
    // }
    public function logout()
    {
        $this->session->unset_userdata('email_user');
        $this->session->unset_userdata('role_user');

        $this->session->set_flashdata('pesan', '<div 
            class="alert alert-success alert-message" role="alert">Logout Berhasil!</div>');
            redirect('auth');
    }
}