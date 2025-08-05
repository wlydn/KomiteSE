<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/**
 * @property User_model $user_model
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_DB_query_builder $db
 * @property CI_Encrypt $encrypt
 * @property Sweetalert $sweetalert
 * @property CI_URI $uri
 * @property CI_Upload $upload
 */
class User extends MY_Controller
{

	public function __construct()
	{

		parent::__construct();
		is_login('User');

		/*-- untuk mengatasi error confirm form resubmission  --*/
		header('Cache-Control: no-cache, must-revalidate, max-age=0');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
		$this->load->model('user_model');
	}

	public function index()
	{
		$this->db->select('pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		$data['topkrywn'] = $this->user_model->TopKrywn();
		$data['ttlkaryawan'] = $this->user_model->getCountKaryawan();
		$data['ttlIndikator'] = $this->user_model->getCountIndikatorPenilaian();
		$data['ttlPenilaianMingguan'] = $this->user_model->getCountPenilaianMingguan();
		$data['ttlPenilaianBulanan'] = $this->user_model->getCountPenilaianBulanan();
		$data['penilaian'] = $this->user_model->TopPenilaian();

		// Title sudah di-set otomatis di MY_Controller
		$data['page'] = "Dashboard";
		$this->load_template('user/layout/user_template', 'user/user_dashboard', $data);
	}
	
	public function listPenilaian()
	{

		$this->db->select('pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		$data['tipePenilaian'] = $this->user_model->getIndikatorPenilaian();

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
		$this->load_template('user/layout/user_template', 'user/modul_listPelanggaran/user_listPelanggaran', $data);
	}

	public function listPenilaianAdd()
	{

		// Get user data with employee name from pegawai table
		$this->db->select('tb_users.id as user_id, tb_users.username, pegawai.nama as nama_pegawai, pegawai.nik, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		$data['karyawanAll'] = $this->user_model->getKaryawan();
		$data['indikatorPenilaianAll'] = $this->user_model->getIndikatorPenilaian();

		$this->form_validation->set_rules('karyawan', 'Nama Karyawan', 'required|integer');
		$this->form_validation->set_rules('indikatorPenilaian', 'Indikator Penilaian', 'required|integer');
		$this->form_validation->set_rules('pelapor', 'Pelapor', 'required|trim|htmlspecialchars');
		$this->form_validation->set_rules('tanggalPelaporan', 'Tanggal Pelaporan', 'required');
		$this->form_validation->set_rules('catatanPenilaian', 'Catatan Penilaian', 'required|trim|htmlspecialchars');

		if ($this->form_validation->run() == false) {

			// Title sudah di-set otomatis di MY_Controller
			$data['parent'] = "List Penilaian";
			$data['page'] = "Add Penilaian Karyawan";
			$this->load_template('user/layout/user_template', 'user/modul_listPelanggaran/user_listPelanggaranAdd', $data);
		} else {
			$data_insert = [
				'user_id' => $data['user']->user_id,
				'pegawai_id' => $this->input->post('karyawan'),
				'indikator_id' => $this->input->post('indikatorPenilaian'),
				'catatan' => $this->input->post('catatanPenilaian'),
				'date' => $this->input->post('tanggalPelaporan')
			];

			$this->db->insert('tb_penilaian', $data_insert);

			$this->sweetalert->success('Penilaian untuk karyawan berhasil ditambahkan!');
			redirect('User/listPenilaian');
		}
	}

	public function listPenilaianEdit($id = null)
	{

		if (count($this->uri->segment_array()) > 3) {
			redirect('User');
		}
		$decoded_id = $this->encrypt->decode($id);
		if (!isset($id) || !validate_id($decoded_id)) {
			redirect('User/listPenilaian');
		}

		$this->db->select('tb_users.id as user_id, tb_users.username, pegawai.nama as nama_pegawai, pegawai.nik, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		$data['onepel'] = $this->user_model->getOnePelanggaran($decoded_id);

		if (!$data['onepel']) {
			redirect('User/listPenilaian');
			return;
		}

		$data['pelanggaranAll'] = $this->user_model->getIndikatorPenilaian();
		$data['userAll'] = $this->user_model->getKaryawan();

		$this->form_validation->set_rules('karyawan', 'Nama Karyawan', 'required|integer');
		$this->form_validation->set_rules('indikatorPenilaian', 'Indikator Penilaian', 'required|integer');
		$this->form_validation->set_rules('tanggalPenilaian', 'Tanggal Penilaian', 'required');

		if ($this->form_validation->run() == false) {

			// Title sudah di-set otomatis di MY_Controller
			$data['parent'] = "List Penilaian";
			$data['page'] = "List Penilaian Edit";
			$this->load_template('user/layout/user_template', 'user/modul_listPelanggaran/user_listPelanggaranEdit', $data);
		} else {

			$data_update = [
				'pegawai_id' => $this->input->post('karyawan'),
				'indikator_id' => $this->input->post('indikatorPenilaian'),
				'date' => $this->input->post('tanggalPenilaian')
			];

			$this->db->where('id', $this->input->post('z'));
			$this->db->update('tb_penilaian', $data_update);
			$this->sweetalert->success('Penilaian karyawan berhasil diupdate!');
			redirect('User/listPenilaian');
		}
	}

	public function listPelanggaranDelete()
	{

		$id = $this->input->post("id");
		if (!validate_id($id)) {
			$sukses = "Invalid ID.";
			echo json_encode($sukses);
			return;
		}


		$dataPelanggaran = $this->db->get_where('tb_pelanggaran', ['id' => $id])->row();
		$dataKelas = $this->db->get_where('tb_kelas', ['id' => $dataPelanggaran->class_id])->row();
		$pengurangan = $dataKelas->total_poin - $dataPelanggaran->point;
		$data = [

			'total_poin' => $pengurangan

		];

		$this->db->where('id', $dataPelanggaran->class_id);
		$this->db->update('tb_kelas', $data);

		$this->db->delete('tb_pelanggaran', ['id' => $id]);

		$sukses = "Deleted successfully.";
		echo json_encode($sukses);
	}

	public function listPelanggaranDetail($id)
	{
		if (count($this->uri->segment_array()) > 3) {
			redirect('user');
		}
		$decoded_id = $this->encrypt->decode($id);
		if (!isset($id) || !validate_id($decoded_id)) {
			redirect('user');
		}

		$data['user'] = get_user_data();

		$data['onepel'] = $this->user_model->getOnePelanggaran($decoded_id);

		if (!$data['onepel']) {
			redirect('User/listPenilaian');
			return;
		}

		// Title sudah di-set otomatis di MY_Controller
		$data['parent'] = "List Penilaian";
		$data['page'] = "Detail Pelanggaran";
		$this->load_template('user/layout/user_template', 'user/modul_listPelanggaran/user_listPelanggaranDetail', $data);
	}

	public function Profile()
	{

		$data['user'] = $this->db->get_where('tb_users', ['username' => $this->session->userdata('username')])->row();

		// Title sudah di-set otomatis di MY_Controller
		$data['page'] = "Profile";
		$this->load_template('user/layout/user_template', 'user/user_profile', $data);
	}

	public function editProfile()
	{

		$data['user'] = $this->db->get_where('tb_users', ['username' => $this->session->userdata('username')])->row();

		$this->form_validation->set_rules('fullname', 'Fullname', 'required|trim|htmlspecialchars');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');


		if ($this->form_validation->run() == false) {

			// Title sudah di-set otomatis di MY_Controller
			$data['page'] = "Profile";
			$this->load_template('user/layout/user_template', 'user/user_profile', $data);
		} else {

			//check jika ada gmabar yang akan diupload, "f" itu nama inputnya
			// $upload_image = $_FILES['photo']['name'];
			$filename = $this->session->userdata('username');

			$config['allowed_types'] = 'png';
			$config['max_size']     = '5120'; // dalam hitungan kilobyte(kb), aslinya 1mb itu 1024kb
			$config['upload_path'] = './assets/sips/img/user/';
			$config['overwrite'] = "TRUE";
			$config['file_name'] = $filename;

			$this->load->library('upload', $config);
			$this->upload->overwrite = true;
			if (!$this->upload->do_upload('photo')) {

				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $this->upload->display_errors() . '</div>');
				redirect('User/profile');
			} else {

				$data = [

					'full_name' => $this->input->post('fullname'),
					'email' => $this->input->post('email'),
				];

				$this->db->where('id', $this->input->post('z'));
				$this->db->update('tb_users', $data);
				$this->sweetalert->success('Profile Telah Di Update!');
				redirect('User/profile');
			}
		}
	}

	public function changePassword()
	{

		$data['user'] = $this->db->get_where('tb_users', ['username' => $this->session->userdata('username')])->row();

		$this->form_validation->set_rules('bb', 'New Password', 'required|trim|min_length[4]|matches[cc]');
		$this->form_validation->set_rules('cc', 'Confirm New Password', 'required|trim|min_length[4]|matches[bb]');

		if ($this->form_validation->run() == false) {

			// Title sudah di-set otomatis di MY_Controller
			$data['page'] = "Profile";
			$this->load_template('user/layout/user_template', 'user/user_profile', $data);
		} else {

			$new_password = $this->input->post('bb');

			$password_hash = password_hash($new_password, PASSWORD_DEFAULT);

			$this->db->set('password', $password_hash);
			$this->db->where('id', $this->input->post('dd'));
			$this->db->update('tb_users');

			// Clear any existing flash data before setting new alert
			$this->clear_flash_data();

			$this->sweetalert->success('Password berhasil diubah!');

			// Use safe redirect to ensure clean session state
			$this->safe_redirect('User/profile');
		}
	}

	public function dataKategoriListKaryawan()
	{

		$this->db->select('pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();
		$data['karyawanAll'] = $this->user_model->getKaryawan();

		// Title sudah di-set otomatis di MY_Controller
		$data['page'] = "List Karyawan";
		$this->load_template('user/layout/user_template', 'user/modul_dataKategori/user_listKaryawan', $data);
	}

	public function pengaturanUserEdit()
	{

		// Get user data by joining with pegawai table using session username
		$this->db->select('tb_users.id, tb_users.username, pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		// If user not found, show alert and redirect
		if (!$data['user']) {
			$this->sweetalert->error('User tidak ditemukan!');
			redirect('User');
			return;
		}

		// Title sudah di-set otomatis di MY_Controller
		$data['page'] = "Ubah Password";
		$this->load_template('user/layout/user_template', 'user/user_changePassword', $data);
	}

	public function updatePassword()
	{

		// Get user data by joining with pegawai table using session username - include id for form
		$this->db->select('tb_users.id, tb_users.username, pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		// Set validation rules with custom callback for password matching
		$this->form_validation->set_rules('new_password', 'Password Baru', 'required|trim|min_length[8]', [
			'required' => 'Password baru harus diisi',
			'min_length' => 'Password minimal 8 karakter'
		]);
		$this->form_validation->set_rules('repeat_password', 'Konfirmasi Password', 'required|trim|callback_password_match', [
			'required' => 'Konfirmasi password harus diisi'
		]);

		if ($this->form_validation->run() == false) {

			// Title sudah di-set otomatis di MY_Controller
			$data['page'] = "Ubah Password";
			$this->load_template('user/layout/user_template', 'user/user_changePassword', $data);
		} else {

			$new_password = $this->input->post('new_password');
			$password_hash = password_hash($new_password, PASSWORD_DEFAULT);

			$this->db->set('password', $password_hash);
			$this->db->where('id', $this->input->post('user_id'));
			$this->db->update('tb_users');

			// Clear any existing flash data before setting new alert
			$this->clear_flash_data();

			// Set success message
			$this->session->set_flashdata('success', 'Password berhasil diubah!');

			// Use safe redirect to ensure clean session state
			$this->safe_redirect('User/pengaturanUserEdit');
		}
	}

	// Custom validation callback for password matching
	public function password_match($repeat_password)
	{
		$new_password = $this->input->post('new_password');

		if ($new_password !== $repeat_password) {
			$this->form_validation->set_message('password_match', 'Password dan konfirmasinya tidak sama');
			return FALSE;
		}
		return TRUE;
	}
}
