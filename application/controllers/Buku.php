<?php defined('BASEPATH') or exit('No direct script access allowed');
class Buku extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();
    }

    public function ubahBuku() { 
        $data['judul'] = 'Ubah Data Buku'; 
        $data['user'] = $this->ModelUser->cekData(['email_user' => $this->session->userdata('email_user')])->row_array(); 
        $data['buku'] = $this->ModelBuku->bukuWhere(['id_buku' => $this->uri->segment(3)])->result_array();
        $kategori = $this->ModelBuku->joinKategoriBuku(['buku.id_kategori' => $this->uri->segment(3)])->result_array(); 
        foreach ($kategori as $k) { 
            $data['id_kategori'] = $k['id_kategori']; 
        } 
        $data['id_kategori'] = $this->ModelBuku->getKategori()->result_array(); 
        $this->form_validation->set_rules('judul_buku', 'Judul Buku', 'required|min_length[3]', [ 'required' => 'Judul Buku harus diisi', 'min_length' => 'Judul buku terlalu pendek' ]); 
        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required', [ 'required' => 'Nama pengarang harus diisi', ]); 
        $this->form_validation->set_rules('pengarang', 'Nama Pengarang', 'required|min_length[3]', [ 'required' => 'Nama pengarang harus diisi', 'min_length' => 'Nama pengarang terlalu pendek' ]); 
        $this->form_validation->set_rules('penerbit', 'Nama Penerbit', 'required|min_length[3]', [ 'required' => 'Nama penerbit harus diisi', 'min_length' => 'Nama penerbit terlalu pendek' ]); 
        $this->form_validation->set_rules('tahun', 'Tahun Terbit', 'required|min_length[3]|max_length[4]|numeric', [ 'required' => 'Tahun terbit harus diisi', 'min_length' => 'Tahun terbit terlalu pendek', 'max_length' => 'Tahun terbit terlalu panjang', 'numeric' => 'Hanya boleh diisi angka' ]); 
        $this->form_validation->set_rules('isbn', 'Nomor ISBN', 'required|min_length[3]|numeric', [ 'required' => 'Nama ISBN harus diisi', 'min_length' => 'Nama ISBN terlalu pendek', 'numeric' => 'Yang anda masukan bukan angka' ]);
        $this->form_validation->set_rules('stok', 'Stok', 'required|numeric', [ 'required' => 'Stok harus diisi', 'numeric' => 'Yang anda masukan bukan angka' ]); 
        //konfigurasi sebelum gambar diupload 
        $config['upload_path'] = './assets/img/upload/'; 
        $config['allowed_types'] = 'jpg|png|jpeg'; 
        $config['max_size'] = '3000'; 
        $config['max_width'] = '5000'; 
        $config['max_height'] = '3000'; 
        $config['file_name'] = 'img' . time(); 
        //memuat atau memanggil library upload 
        $this->load->library('upload', $config); 
        if ($this->form_validation->run() == false) { 
            $this->load->view('admin/header', $data); 
            $this->load->view('admin/sidebar', $data); 
            $this->load->view('admin/topbar', $data); 
            $this->load->view('buku/ubahbuku', $data); 
            $this->load->view('admin/footer'); 
        } 
        else { 
            if ($this->upload->do_upload('image')) { 
                $image = $this->upload->data(); 
                unlink('assets/img/upload/' . $this->input->post('old_pict', TRUE)); 
                $gambar = $image['file_name']; 
            } else { 
                $gambar = $this->input->post('old_pict', TRUE); 
            } 
            $data = [ 
                'judul_buku' => $this->input->post('judul_buku', true), 
                'id_kategori' => $this->input->post('id_kategori', true), 
                'pengarang_buku' => $this->input->post('pengarang', true), 
                'penerbit_buku' => $this->input->post('penerbit', true), 
                'tahun_buku' => $this->input->post('tahun', true),
                'isbn_buku' => $this->input->post('isbn', true), 
                'stok_buku' => $this->input->post('stok', true), 
                'cover_buku' => $gambar 
            ]; 
            $this->ModelBuku->updateBuku($data, ['id_buku' => $this->input->post('id')]); 
            redirect('buku'); 
        } 
    }
    public function hapusBuku() { 
        $where = ['id_buku' => $this->uri->segment(3)]; 
        $this->ModelBuku->hapusBuku($where);
        redirect('buku'); 
    }

    public function kategori()
    {
        $data['judul'] = 'Kategori Buku';
        $data['user'] = $this->ModelUser->cekData(['email_user' => $this->session->userdata('email_user')])->row_array();
        $data['kategori'] = $this->ModelBuku->getKategori()->result_array();
        $this->form_validation->set_rules('kategori', 'Kategori', 'required', ['required' => 'Judul Buku harus diisi']);
        if ($this->form_validation->run() == false) {
            $this->load->view('admin/header', $data);
            $this->load->view('admin/sidebar', $data);
            $this->load->view('admin/topbar', $data);
            $this->load->view('buku/kategori', $data);
            $this->load->view('admin/footer');
        } else {
            $data = ['nama_kategori' => $this->input->post('kategori')];
            $this->ModelBuku->simpanKategori($data);
            redirect('buku/kategori');
        }
    }

    public function hapusKategori()
    {
        $where = ['id_kategori' => $this->uri->segment(3)];
        $this->ModelBuku->hapusKategori($where);
        redirect('buku/kategori');
    }
    //manajemen Buku 
    public function index()
    {
        $data['judul'] = 'Data Buku';
        $data['user'] = $this->ModelUser->cekData(['email_user' => $this->session->userdata('email_user')])->row_array();
        $data['buku'] = $this->ModelBuku->getBuku()->result_array();
        $data['kategori'] = $this->ModelBuku->getKategori()->result_array();
        $this->form_validation->set_rules('judul_buku', 'Judul Buku', 'required|min_length[3]', ['required' => 'Judul Buku harus diisi', 'min_length' => 'Judul buku terlalu pendek']);
        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required', ['required' => 'Nama pengarang harus diisi',]);
        $this->form_validation->set_rules('pengarang', 'Nama Pengarang', 'required|min_length[3]', ['required' => 'Nama pengarang harus diisi', 'min_length' => 'Nama pengarang terlalu pendek']);
        $this->form_validation->set_rules('penerbit', 'Nama Penerbit', 'required|min_length[3]', ['required' => 'Nama penerbit harus diisi', 'min_length' => 'Nama penerbit terlalu pendek']);
        $this->form_validation->set_rules('tahun', 'Tahun Terbit', 'required|min_length[3]|max_length[4]|numeric', ['required' => 'Tahun terbit harus diisi', 'min_length' => 'Tahun terbit terlalu pendek', 'max_length' => 'Tahun terbit terlalu panjang', 'numeric' => 'Hanya boleh diisi angka']);
        $this->form_validation->set_rules('isbn', 'Nomor ISBN', 'required|min_length[3]|numeric', ['required' => 'Nama ISBN harus diisi', 'min_length' => 'Nama ISBN terlalu pendek', 'numeric' => 'Yang anda masukan bukan angka']);
        $this->form_validation->set_rules('stok', 'Stok', 'required|numeric', ['required' => 'Stok harus diisi', 'numeric' => 'Yang anda masukan bukan angka']); //konfigurasi sebelum gambar diupload 
        $config['upload_path'] = './assets/img/upload/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size'] = '3000';
        $config['max_width'] = '5000';
        $config['max_height'] = '3000';
        $config['file_name'] = 'img' . time();
        $this->load->library('upload', $config);
        if ($this->form_validation->run() == false) {
            $this->load->view('admin/header', $data);
            $this->load->view('admin/sidebar', $data);
            $this->load->view('admin/topbar', $data);
            $this->load->view('buku/index', $data);
            $this->load->view('admin/footer');
        } else {
            if ($this->upload->do_upload('image')) {
                $image = $this->upload->data();
                $gambar = $image['file_name'];
            } else {
                $gambar = '';
            }
            $data = [
                'judul_buku' => $this->input->post('judul_buku', true),
                'id_kategori' => $this->input->post('id_kategori', true),
                'pengarang_buku' => $this->input->post('pengarang', true),
                'penerbit_buku' => $this->input->post('penerbit', true),
                'tahun_buku' => $this->input->post('tahun', true),
                'isbn_buku' => $this->input->post('isbn', true),
                'stok_buku' => $this->input->post('stok', true),
                'jumlah_pinjam' => 0,
                'status_buku' => 0,
                'cover_buku' => $gambar
            ];
            $this->ModelBuku->simpanBuku($data);
            redirect('buku');
        }
    }
} 