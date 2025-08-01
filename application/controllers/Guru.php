<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Guru extends MY_Controller {

	public function __construct(){

		parent::__construct();
		is_login();

		/*-- untuk mengatasi error confirm form resubmission  --*/
		header('Cache-Control: no-cache, must-revalidate, max-age=0');
		header('Cache-Control: post-check=0, pre-check=0',false);
		header('Pragma: no-cache');
		$this->load->model('guru_model');
	}

	public function index(){
		$this->db->select('pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		$data['topkrywn'] = $this->guru_model->TopKrywn();
		$data['ttlkaryawan'] = $this->guru_model->getCountKaryawan();
		$data['ttlIndikator'] = $this->guru_model->getCountIndikatorPenilaian();
		$data['ttlPenilaianMingguan'] = $this->guru_model->getCountPenilaianMingguan();
		$data['ttlPenilaianBulanan'] = $this->guru_model->getCountPenilaianBulanan();
		$data['penilaian'] = $this->guru_model->TopPenilaian();

		// Title sudah di-set otomatis di MY_Controller
		$data['page'] = "Dashboard";
		$this->load_template('guru/layout/guru_template','guru/guru_dashboard',$data);

	}

	public function dashboardDetail($id){

		if (count($this->uri->segment_array()) > 3) {
			redirect('guru');
		}
		if (!isset($id)) {
			redirect('guru');
		}
		if (is_numeric($id)) {
			redirect('guru');
		}
		
		$data['user'] = get_user_data();

		$data['onePelanggaranAll'] = $this->guru_model->getOnePelanggaranByID($this->encrypt->decode($id));
		$data['oneSiswa'] = $this->guru_model->getOneSiswa($this->encrypt->decode($id));
		$data['pelanggaranTotal'] = $this->guru_model->getCountPelanggaran($this->encrypt->decode($id));
		$data['pelanggaran'] = $this->guru_model->getPelanggaranByID($this->encrypt->decode($id));

		// Title sudah di-set otomatis di MY_Controller
		$data['page'] = "Detail Pelanggaran";
		$this->load_template('guru/layout/guru_template','guru/guru_dashboardDetail',$data);

	}

	public function listPenilaian(){

		$this->db->select('pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		$data['tipePenilaian'] = $this->guru_model->getKategoriPenilaian();

		// Get unique units for filter dropdown
		$this->db->distinct();
		$this->db->select('pegawai.jbtn as unit');
		$this->db->from('tb_penilaian');
		$this->db->join('pegawai', 'tb_penilaian.pegawai_id = pegawai.id', 'left');
		$this->db->where('pegawai.jbtn IS NOT NULL');
		$this->db->where('pegawai.jbtn !=', '');
		$this->db->order_by('pegawai.jbtn', 'ASC');
		$data['units'] = $this->db->get()->result();

		// Title sudah di-set otomatis di MY_Controller
		$data['page'] = "List Penilaian";
		$this->load_template('guru/layout/guru_template','guru/modul_listPelanggaran/guru_listPelanggaran',$data);

	}

	public function listPenilaianAdd(){

		// Get user data with employee name from pegawai table
		$this->db->select('tb_users.id as user_id, tb_users.username, pegawai.nama as nama_pegawai, pegawai.nik, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();
		
		$data['karyawanAll'] = $this->guru_model->getKaryawan();
		$data['indikatorPenilaianAll'] = $this->guru_model->getIndikatorPenilaian();

		$this->form_validation->set_rules('karyawan','Nama Karyawan','required');
		$this->form_validation->set_rules('indikatorPenilaian','Indikator Penilaian','required');
		$this->form_validation->set_rules('pelapor','Pelapor','required');
		$this->form_validation->set_rules('tanggalPelaporan','Tanggal Pelaporan','required');
		$this->form_validation->set_rules('catatanPenilaian','Catatan Penilaian','required');

		if($this->form_validation->run() == false){

			// Title sudah di-set otomatis di MY_Controller
			$data['parent'] = "List Penilaian";
			$data['page'] = "Add Penilaian Karyawan";
			$this->load_template('guru/layout/guru_template','guru/modul_listPelanggaran/guru_listPelanggaranAdd',$data);

		}else{
			$data_insert = [
				'user_id' => $data['user']->user_id,
				'pegawai_id' => $this->input->post('karyawan'),
				'indikator_id' => $this->input->post('indikatorPenilaian'),
				'catatan' => $this->input->post('catatanPenilaian'),
				'date' => $this->input->post('tanggalPelaporan')
			];

			$this->db->insert('tb_penilaian', $data_insert);

			$this->sweetalert->success('Penilaian untuk karyawan berhasil ditambahkan!');
			redirect('Guru/listPenilaian');

		}

	}

	public function listPenilaianEdit($id = null){

		if (count($this->uri->segment_array()) > 3) {
			redirect('Guru');
		}
		if (!isset($id)) {
			redirect('Guru/listPenilaian');
		}
		if (is_numeric($id)) {
			redirect('Guru/listPenilaian');
		}

		$this->db->select('tb_users.id as user_id, tb_users.username, pegawai.nama as nama_pegawai, pegawai.nik, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		$data['onepel'] = $this->guru_model->getOnePelanggaran($this->encrypt->decode($id));

		if (!$data['onepel']) {
			redirect('Guru/listPenilaian');
			return;
		}

		$data['pelanggaranAll'] = $this->guru_model->getKategoriPelanggaran();
		$data['guruAll'] = $this->guru_model->getGuru();

		$this->form_validation->set_rules('karyawan','Nama Karyawan','required');
		$this->form_validation->set_rules('indikatorPenilaian','Indikator Penilaian','required');
		$this->form_validation->set_rules('tanggalPenilaian','Tanggal Penilaian','required');

		if($this->form_validation->run() == false){

			// Title sudah di-set otomatis di MY_Controller
			$data['parent'] = "List Penilaian";
			$data['page'] = "List Penilaian Edit";
			$this->load_template('guru/layout/guru_template','guru/modul_listPelanggaran/guru_listPelanggaranEdit',$data);

		}else{

			$data_update = [
				'pegawai_id' => $this->input->post('karyawan'),
				'indikator_id' => $this->input->post('indikatorPenilaian'),
				'date' => $this->input->post('tanggalPenilaian')
			];

			$this->db->where('id', $this->input->post('z'));
			$this->db->update('tb_penilaian', $data_update);
			$this->sweetalert->success('Penilaian karyawan berhasil diupdate!');
			redirect('Guru/listPenilaian');

		}
	}

	public function listPelanggaranDelete(){

		$id = $this->input->post("id");

		$dataPelanggaran = $this->db->get_where('tb_pelanggaran',['id' => $id])->row();
		$dataKelas = $this->db->get_where('tb_kelas',['id' => $dataPelanggaran->class_id])->row();
		$pengurangan = $dataKelas->total_poin - $dataPelanggaran->point;
		$data = [

			'total_poin' => $pengurangan

		];

		$this->db->where('id', $dataPelanggaran->class_id);
		$this->db->update('tb_kelas',$data);

		$this->db->delete('tb_pelanggaran',['id' => $id]);

		$sukses = "Deleted successfully.";
		echo json_encode($sukses);

	}

	public function Profile(){

		$data['user'] = $this->db->get_where('tb_users',['username' => $this->session->userdata('username')])->row();

		// Title sudah di-set otomatis di MY_Controller
		$data['page'] = "Profile";
		$this->load_template('guru/layout/guru_template','guru/guru_profile',$data);
	}

	public function editProfile(){

		$data['user'] = $this->db->get_where('tb_users',['username' => $this->session->userdata('username')])->row();
		
		$this->form_validation->set_rules('fullname','Fullname','required');

		if($this->form_validation->run() == false){

			// Title sudah di-set otomatis di MY_Controller
			$data['page'] = "Profile";
			$this->load_template('guru/layout/guru_template','guru/guru_profile',$data);

		}else{

			//check jika ada gmabar yang akan diupload, "f" itu nama inputnya
			// $upload_image = $_FILES['photo']['name'];
			$filename = $this->session->userdata('username');

			$config['allowed_types'] = 'png';
				$config['max_size']     = '5120'; // dalam hitungan kilobyte(kb), aslinya 1mb itu 1024kb
				$config['upload_path'] = './assets/sips/img/guru/';
				$config['overwrite'] = "TRUE";
				$config['file_name'] = $filename;

				$this->load->library('upload', $config);
				$this->upload->overwrite = true;
				if(! $this->upload->do_upload('photo')){

					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $this->upload->display_errors() . '</div>');
					redirect('Guru/profile');

				}else{

					$data = [

						'full_name' => $this->input->post('fullname'),
						'email' => $this->input->post('email'),
					];

					$this->db->where('id', $this->input->post('z'));
					$this->db->update('tb_users',$data);
					$this->sweetalert->success('Profile Telah Di Update!');
					redirect('Guru/profile');
				}

		}
	}

	public function changePassword(){

		$data['user'] = $this->db->get_where('tb_users',['username' => $this->session->userdata('username')])->row();

		$this->form_validation->set_rules('bb', 'New Password','required|trim|min_length[4]|matches[cc]');
		$this->form_validation->set_rules('cc', 'Confirm New Password','required|trim|min_length[4]|matches[bb]');

		if($this->form_validation->run() == false){

			// Title sudah di-set otomatis di MY_Controller
			$data['page'] = "Profile";
			$this->load_template('guru/layout/guru_template','guru/guru_profile',$data);

		}else{

			$new_password = $this->input->post('bb');

			$password_hash = password_hash($new_password, PASSWORD_DEFAULT);

			$this->db->set('password', $password_hash);
			$this->db->where('id', $this->input->post('dd'));
			$this->db->update('tb_users');

			// Clear any existing flash data before setting new alert
			$this->clear_flash_data();
			
			$this->sweetalert->success('Password berhasil diubah!');
			
			// Use safe redirect to ensure clean session state
			$this->safe_redirect('Guru/profile');
		}

	}

	public function dataKategoriListKaryawan(){

		$this->db->select('pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();
		$data['karyawanAll'] = $this->guru_model->getKaryawan();

		// Title sudah di-set otomatis di MY_Controller
		$data['page'] = "List Karyawan";
		$this->load_template('guru/layout/guru_template','guru/modul_dataKategori/guru_listKaryawan',$data);

	}

	public function pengaturanUserEdit(){

		// Get user data by joining with pegawai table using session username
		$this->db->select('tb_users.id, tb_users.username, pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		// If user not found, show alert and redirect
		if (!$data['user']) {
			$this->sweetalert->error('User tidak ditemukan!');
			redirect('Guru');
			return;
		}

		// Title sudah di-set otomatis di MY_Controller
		$data['page'] = "Ubah Password";
		$this->load_template('guru/layout/guru_template','guru/guru_changePassword',$data);
	}

	public function updatePassword(){

		// Get user data by joining with pegawai table using session username - include id for form
		$this->db->select('tb_users.id, tb_users.username, pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		// Set validation rules with custom callback for password matching
		$this->form_validation->set_rules('new_password', 'Password Baru','required|trim|min_length[8]', [
			'required' => 'Password baru harus diisi',
			'min_length' => 'Password minimal 8 karakter'
		]);
		$this->form_validation->set_rules('repeat_password', 'Konfirmasi Password','required|trim|callback_password_match', [
			'required' => 'Konfirmasi password harus diisi'
		]);

		if($this->form_validation->run() == false){

			// Title sudah di-set otomatis di MY_Controller
			$data['page'] = "Ubah Password";
			$this->load_template('guru/layout/guru_template','guru/guru_changePassword',$data);

		}else{

			$new_password = $this->input->post('new_password');
			$password_hash = password_hash($new_password, PASSWORD_DEFAULT);

			$this->db->set('password', $password_hash);
			$this->db->where('id', $this->input->post('user_id'));
			$this->db->update('tb_users');

			// Clear any existing flash data before setting new alert
			$this->clear_flash_data();
			
			// Set success message
			$this->sweetalert->success('Password berhasil diubah!');
			
			// Use safe redirect to ensure clean session state
			$this->safe_redirect('Guru');
		}

	}

	// Custom validation callback for password matching
	public function password_match($repeat_password) {
		$new_password = $this->input->post('new_password');
		
		if ($new_password !== $repeat_password) {
			$this->form_validation->set_message('password_match', 'Password dan konfirmasinya tidak sama');
			return FALSE;
		}
		return TRUE;
	}

}
