<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/**
 * @property Admin_model $admin_model
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_DB_query_builder $db
 * @property CI_Encrypt $encrypt
 * @property Sweetalert $sweetalert
 * @property CI_URI $uri
 * @property CI_Security $security
 */
class Admin extends MY_Controller
{

	public function __construct()
	{

		parent::__construct();
		/*-- Check Session  --*/
		is_login('Admin');
		/*-- untuk mengatasi error confirm form resubmission  --*/
		header('Cache-Control: no-cache, must-revalidate, max-age=0');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
		$this->load->model('admin_model');
	}

	public function index()
	{

		$this->db->select('pegawai.nik as nik_pegawai, pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		$data['ttlkaryawan'] = $this->admin_model->getCountKaryawan();
		$data['ttlIndikator'] = $this->admin_model->getCountIndikatorPenilaian();
		$data['ttlUsers'] = $this->admin_model->getCountUsers();
		$data['ttlPenilaianMingguan'] = $this->admin_model->getCountPenilaianMingguan();
		$data['ttlPenilaianBulanan'] = $this->admin_model->getCountPenilaianBulanan();

		$data['murid'] = $this->admin_model->TopKrywn();
		$data['pelanggaran'] = $this->admin_model->TopPelanggaran();

		// Title sudah di-set otomatis di MY_Controller
		$data['parent'] = "Dashbord";
		$data['page'] = "Dashboard";
		$this->load_template('admin/layout/admin_template', 'admin/admin_dashboard', $data);
	}

	public function dashboardDetail($id)
	{

		if (count($this->uri->segment_array()) > 3) {
			redirect('Admin');
		}
		$decoded_id = $this->encrypt->decode($id);
		if (!isset($id) || !validate_id($decoded_id)) {
			redirect('Admin');
		}

		$data['user'] = get_user_data();

		$data['onePelanggaranAll'] = $this->admin_model->getPelanggaranByID($decoded_id, true);
		$data['pelanggaranTotal'] = $this->admin_model->getCountPelanggaran($decoded_id);
		$data['pelanggaran'] = $this->admin_model->getPelanggaranByID($decoded_id);

		// Title sudah di-set otomatis di MY_Controller
		$data['parent'] = "Dashboard";
		$data['page'] = "Detail Pelanggaran";
		$this->load_template('admin/layout/admin_template', 'admin/admin_dashboardDetail', $data);
	}


	public function dataIndikatorPenilaian()
	{

		$this->db->select('pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		$data['tipePelanggaran'] = $this->admin_model->getIndikatorPenilaian();

		// Title sudah di-set otomatis di MY_Controller
		$data['parent'] = "Data Kategori";
		$data['page'] = "Indikator Penilaian";
		$this->load_template('admin/layout/admin_template', 'admin/modul_dataKategori/admin_indikatorPenilaian', $data);
	}

	public function dataIndikatorPenilaianAdd()
	{
		$this->form_validation->set_rules('code', 'Code', 'required|trim|htmlspecialchars');
		$this->form_validation->set_rules('nama', 'Nama Indikator', 'required|trim|htmlspecialchars');

		if ($this->form_validation->run() == false) {
			$this->db->select('pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
			$this->db->from('tb_users');
			$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
			$this->db->where('tb_users.username', $this->session->userdata('username'));
			$data['user'] = $this->db->get()->row();

			$data['tipePelanggaran'] = $this->admin_model->getIndikatorPenilaian();
			$data['parent'] = "Data Kategori";
			$data['page'] = "Indikator Penilaian";
			$this->load_template('admin/layout/admin_template', 'admin/modul_dataKategori/admin_indikatorPenilaian', $data);
		} else {
			$code = $this->input->post('code');
			$nama = $this->input->post('nama');

			$insert_data = [
				'code' => $code,
				'violation_name' => $nama,
				'get_point' => 1
			];

			$insert_result = $this->db->insert('tb_indikator', $insert_data);

			if ($insert_result) {
				$this->session->set_flashdata('success', 'Indikator Penilaian "' . $nama . '" berhasil ditambahkan!');
			} else {
				$this->session->set_flashdata('error', 'Gagal menyimpan data ke database!');
			}

			redirect('Admin/dataIndikatorPenilaian');
		}
	}


	public function dataIndikatorPenilaianEdit()
	{
		$this->form_validation->set_rules('nama', 'Nama Kategori Pelanggaran', 'required|trim|htmlspecialchars');
		$this->form_validation->set_rules('point', 'Jumlah Point', 'required|trim|integer');
		$this->form_validation->set_rules('z', 'ID', 'required|trim|integer');

		if ($this->form_validation->run() == false) {
			$data['user'] = $this->db->get_where('tb_users', ['username' => $this->session->userdata('username')])->row();
			$data['tipePelanggaran'] = $this->admin_model->getIndikatorPenilaian();
			$data['parent'] = "Data Kategori";
			$data['page'] = "Kategori Pelanggaran Edit";
			$this->load_template('admin/layout/admin_template', 'admin/modul_dataKategori/admin_indikatorPenilaian', $data);
		} else {
			$data = [
				'violation_name' => $this->input->post('nama'),
				'get_point' => $this->input->post('point')
			];

			$this->db->where('id', $this->input->post('z'));
			$this->db->update('tb_indikator', $data);
			$this->sweetalert->success('Kategori Pelanggaran ' . $this->input->post('nama') . ' Telah Di Update!');
			redirect('Admin/dataIndikatorPenilaian');
		}
	}


	public function deleteIndikator()
	{
		// Disable all output buffering and error reporting that might interfere
		while (ob_get_level()) {
			ob_end_clean();
		}
		
		// Set headers first
		header('Content-Type: application/json; charset=utf-8');
		header('Cache-Control: no-cache, must-revalidate');
		
		// Initialize response
		$response = array();
		
		try {
			// Check if request is POST
			if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
				throw new Exception('Metode request tidak valid. Hanya POST yang diizinkan.');
			}

			// Check if user is logged in
			if (!$this->session->userdata('username')) {
				$response = array(
					'status' => 'error',
					'message' => 'Sesi Anda telah berakhir. Silakan login kembali.',
					'redirect' => true
				);
				echo json_encode($response);
				exit;
			}

			// Check if user is Admin
			if ($this->session->userdata('level') != 'Admin') {
				throw new Exception('Akses ditolak. Hanya Admin yang dapat menghapus indikator penilaian.');
			}

			// Get and validate ID
			$id = $this->input->post("id");
			if (empty($id) || !is_numeric($id)) {
				throw new Exception('ID indikator penilaian tidak valid.');
			}

			// Check if indikator penilaian exists
			$indikator = $this->db->get_where('tb_indikator', ['id' => $id])->row();
			if (!$indikator) {
				throw new Exception('Data indikator penilaian tidak ditemukan.');
			}

			// Delete the indikator penilaian record
			$delete_result = $this->db->delete('tb_indikator', ['id' => $id]);
			if (!$delete_result) {
				throw new Exception('Gagal menghapus data dari database.');
			}

			// Prepare success response
			$response = array(
				'status' => 'success',
				'message' => 'Indikator penilaian berhasil dihapus!',
				'csrf_hash' => $this->security->get_csrf_hash()
			);

		} catch (Exception $e) {
			$response = array(
				'status' => 'error',
				'message' => $e->getMessage(),
				'csrf_hash' => $this->security->get_csrf_hash()
			);
		}

		// Output clean JSON
		echo json_encode($response);
		exit;
	}

	// Keep old method for backward compatibility
	public function dataIndikatorPenilaianDelete()
	{
		$this->deleteIndikator();
	}

	public function dataListKaryawan()
	{

		$this->db->select('pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();
		$data['karyawanAll'] = $this->admin_model->getKaryawan();

		// Title sudah di-set otomatis di MY_Controller
		$data['parent'] = "Data Kategori";
		$data['page'] = "List Karyawan";
		$this->load_template('admin/layout/admin_template', 'admin/modul_dataKategori/admin_listKaryawan', $data);
	}

	public function pengaturanPengguna()
	{

		$this->db->select('pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		// Title sudah di-set otomatis di MY_Controller
		$data['parent'] = "Pengguna";
		$data['page'] = "Data Pengguna";
		$this->load_template('admin/layout/admin_template', 'admin/modul_pengaturan/admin_pengguna', $data);
	}


	public function pengaturanPenggunaAdd()
	{

		$this->db->select('pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		$data['usersAll'] = $this->admin_model->getUsers();

		$data['userAll'] = $this->admin_model->getKaryawan();

		if ($this->input->post('level') == 'Admin') {

			$this->form_validation->set_rules('addNIKAdmin', 'NIK Pegawai', 'required|integer');
			$this->form_validation->set_rules('fullnameAdmin', 'FullName', 'required|trim|htmlspecialchars');
			$this->form_validation->set_rules('usernameAdmin', 'Username', 'required|is_unique[tb_users.username]|trim|htmlspecialchars', [
				'is_unique' => 'Username Sudah Dipakai!'
			]);
			$this->form_validation->set_rules('passwordAdmin', 'Password', 'required');
		} elseif ($this->input->post('level') == 'User') {

			$this->form_validation->set_rules('addNIKUser', 'NIK Pegawai', 'required|integer');
			$this->form_validation->set_rules('fullnameUser', 'FullName', 'required|trim|htmlspecialchars');
			$this->form_validation->set_rules('usernameUser', 'Username', 'required|is_unique[tb_users.username]|trim|htmlspecialchars', [
				'is_unique' => 'Username Sudah Dipakai!'
			]);
			$this->form_validation->set_rules('passwordUser', 'Password', 'required');
		}

		$this->form_validation->set_rules('level', 'Level', 'required');


		if ($this->form_validation->run() == false) {

			// Title sudah di-set otomatis di MY_Controller
			$data['parent'] = "Data Pengguna";
			$data['page'] = "Data Pengguna Add";
			$this->load_template('admin/layout/admin_template', 'admin/modul_pengaturan/admin_penggunaAdd', $data);
		} else {

			if ($this->input->post('level') == 'Admin') {

				$pegawai_id = $this->input->post('addNIKAdmin');
				$pegawai_data = $this->admin_model->getKaryawan($pegawai_id);
				$data = [
					'pegawai_id' => $pegawai_id,
					'username' => $pegawai_data->nik,
					'password' => password_hash($this->input->post('passwordAdmin'), PASSWORD_DEFAULT),
					'level' => $this->input->post('level'),
					'status' => 1,
					'remember_me' => ''
				];
			} elseif ($this->input->post('level') == 'User') {

				$pegawai_id = $this->input->post('addNIKUser');
				$pegawai_data = $this->admin_model->getKaryawan($pegawai_id);

				$data = [
					'pegawai_id' => $pegawai_id,
					'username' => $pegawai_data->nik,
					'password' => password_hash($this->input->post('passwordUser'), PASSWORD_DEFAULT),
					'level' => $this->input->post('level'),
					'status' => 1,
					'remember_me' => ''
				];
			}

			$this->db->insert('tb_users', $data);
			$this->sweetalert->success('Data User Telah Ditambahkan!');
			redirect('Admin/pengaturanPengguna');
		}
	}

	public function pengaturanPenggunaEdit($id = null)
	{

		if (count($this->uri->segment_array()) > 3) {
			redirect('Admin');
		}
		$decoded_id = $this->encrypt->decode($id);
		if (!isset($id) || !validate_id($decoded_id)) {
			redirect('Admin/pengaturanPengguna');
		}

		$this->db->select('pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		$data['oneUsers'] = $this->admin_model->getOneUsers($decoded_id); /*-- Load One Data Administrator --*/
		$this->form_validation->set_rules('level', 'Level', 'required');

		// Add password validation only if password is provided
		if (!empty($this->input->post('password'))) {
			$this->form_validation->set_rules('password', 'Password', 'trim|min_length[4]');
		}

		if ($this->form_validation->run() == false) {

			// Title sudah di-set otomatis di MY_Controller
			$data['parent'] = "Data Pengguna";
			$data['page'] = "Data Pengguna Edit";
			$this->load_template('admin/layout/admin_template', 'admin/modul_pengaturan/admin_penggunaEdit', $data);
		} else {

			// Prepare data array - hanya update kolom yang bisa diubah
			$update_data = [
				'level' => $this->input->post('level', true)
			];

			// Only update password if it's provided
			if (!empty($this->input->post('password'))) {
				// Don't use escape_str on password hash as it corrupts the hash
				$update_data['password'] = password_hash($this->input->post('password', true), PASSWORD_DEFAULT);
			}

			$this->db->where('id', $this->input->post('z'));
			$this->db->update('tb_users', $update_data);
			$this->sweetalert->success('Data User Telah Di Update!');
			redirect('Admin/pengaturanPengguna');
		}
	}


	public function pengaturanPenggunaDelete()
	{
		// Set JSON header
		header('Content-Type: application/json');

		// Check if request is POST
		if ($this->input->method() !== 'post') {
			$response = array(
				'status' => 'error',
				'message' => 'Invalid request method.'
			);
			echo json_encode($response);
			return;
		}

		// Check if user is logged in
		if (!$this->session->userdata('level')) {
			$response = array(
				'status' => 'error',
				'message' => 'Sesi Anda telah berakhir. Silakan login kembali.',
				'redirect' => true
			);
			echo json_encode($response);
			return;
		}

		// Check if user is Admin
		if ($this->session->userdata('level') != 'Admin') {
			$response = array(
				'status' => 'error',
				'message' => 'Akses ditolak. Hanya Admin yang dapat menghapus pengguna.'
			);
			echo json_encode($response);
			return;
		}

		// Get username from POST data (changed from id to username)
		$username = $this->input->post("username");

		// Validate username
		if (empty($username)) {
			$response = array(
				'status' => 'error',
				'message' => 'Username pengguna tidak valid!'
			);
			echo json_encode($response);
			return;
		}

		// Check if user exists by username
		$user = $this->db->get_where('tb_users', ['username' => $username])->row();
		if (!$user) {
			$response = array(
				'status' => 'error',
				'message' => 'Data pengguna tidak ditemukan!'
			);
			echo json_encode($response);
			return;
		}

		// Prevent deleting current logged in user
		if ($user->username == $this->session->userdata('username')) {
			$response = array(
				'status' => 'error',
				'message' => 'Anda tidak dapat menghapus akun yang sedang digunakan!'
			);
			echo json_encode($response);
			return;
		}

		// Delete the user by username
		$delete_result = $this->db->delete('tb_users', ['username' => $username]);

		if ($delete_result) {
			$response = array(
				'status' => 'success',
				'message' => 'Data pengguna berhasil dihapus!'
			);
		} else {
			$response = array(
				'status' => 'error',
				'message' => 'Gagal menghapus data pengguna!'
			);
		}

		echo json_encode($response);
		exit; // Ensure no additional output
	}

	public function changePassword()
	{

		$this->db->select('pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		$this->form_validation->set_rules('bb', 'New Password', 'required|trim|min_length[4]|matches[cc]');
		$this->form_validation->set_rules('cc', 'Confirm New Password', 'required|trim|min_length[4]|matches[bb]');

		if ($this->form_validation->run() == false) {

			// Title sudah di-set otomatis di MY_Controller
			$data['parent'] = "Profile";
			$data['page'] = "Profile";
			$this->load_template('admin/layout/admin_template', 'admin/admin_profile', $data);
		} else {

			$new_password = $this->input->post('bb');

			$password_hash = password_hash($new_password, PASSWORD_DEFAULT);

			$this->db->set('password', $password_hash);
			$this->db->where('id', $this->input->post('dd'));
			$this->db->update('tb_users');

			// Clear any existing flash data before setting new alert
			$this->clear_flash_data();

			$this->sweetalert->success('Password berhasil diubah!');
			redirect('Admin/profile');
		}
	}

	public function dataListPenilaian()
	{

		$this->db->select('pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		// Get all penilaian data
		$data['penilaianAll'] = $this->admin_model->getPenilaian();
		$data['tipePenilaian'] = $this->admin_model->getIndikatorPenilaian();

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
		$data['parent'] = "Data Laporan";
		$data['page'] = "List Penilaian";
		$this->load_template('admin/layout/admin_template', 'admin/modul_laporan/admin_listPenilaian', $data);
	}

	public function listPelanggaran()
	{

		$this->db->select('pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		// Get all penilaian data
		$data['penilaianAll'] = $this->admin_model->getPenilaian();
		$data['tipePenilaian'] = $this->admin_model->getIndikatorPenilaian();

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
		$data['parent'] = "Data Kategori";
		$data['page'] = "List Pelanggaran";
		$this->load_template('admin/layout/admin_template', 'admin/modul_listPelanggaran/admin_listPelanggaran', $data);
	}

	public function dataListPenilaianAdd()
	{

		// Get user data with employee name from pegawai table
		$this->db->select('tb_users.id as user_id, tb_users.username, pegawai.nama as nama_pegawai, pegawai.nik, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		$data['karyawanAll'] = $this->admin_model->getKaryawan();
		$data['indikatorPenilaianAll'] = $this->admin_model->getIndikatorPenilaian();

		$this->form_validation->set_rules('karyawan', 'Nama Karyawan', 'required|integer');
		$this->form_validation->set_rules('indikatorPenilaian', 'Indikator Penilaian', 'required|integer');
		$this->form_validation->set_rules('pelapor', 'Pelapor', 'required|trim|htmlspecialchars');
		$this->form_validation->set_rules('tanggalPelaporan', 'Tanggal Pelaporan', 'required');
		$this->form_validation->set_rules('catatanPenilaian', 'Catatan Penilaian', 'required|trim|htmlspecialchars');

		if ($this->form_validation->run() == false) {

			// Title sudah di-set otomatis di MY_Controller
			$data['parent'] = "List Penilaian";
			$data['page'] = "Add Penilaian Karyawan";
			$this->load_template('admin/layout/admin_template', 'admin/modul_laporan/admin_listPenilaianAdd', $data);
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
			redirect('Admin/dataListPenilaian');
		}
	}

	public function listPenilaianEdit($id = null)
	{

		if (count($this->uri->segment_array()) > 3) {
			redirect('Admin');
		}
		$decoded_id = $this->encrypt->decode($id);
		if (!isset($id) || !validate_id($decoded_id)) {
			redirect('Admin/dataListPenilaian');
		}

		$this->db->select('tb_users.id as user_id, tb_users.username, pegawai.nama as nama_pegawai, pegawai.nik, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		// Get penilaian data with reporter information
		$this->db->select('tb_penilaian.*, reporter_pegawai.nama as pelapor_nama');
		$this->db->from('tb_penilaian');
		$this->db->join('tb_users as reporter_users', 'tb_penilaian.user_id = reporter_users.id', 'left');
		$this->db->join('pegawai as reporter_pegawai', 'reporter_users.username = reporter_pegawai.nik', 'left');
		$this->db->where('tb_penilaian.id', $decoded_id);
		$data['onepel'] = $this->db->get()->row();

		if (!$data['onepel']) {
			redirect('Admin/dataListPenilaian');
			return;
		}

		$data['indikatorAll'] = $this->admin_model->getIndikatorPenilaian();
		$data['karyawanAll'] = $this->admin_model->getKaryawan();

		$this->form_validation->set_rules('karyawan', 'Nama Karyawan', 'required|integer');
		$this->form_validation->set_rules('indikatorPenilaian', 'Indikator Penilaian', 'required|integer');
		$this->form_validation->set_rules('tanggalPenilaian', 'Tanggal Penilaian', 'required');
		$this->form_validation->set_rules('catatan', 'Catatan', 'required|trim|htmlspecialchars');

		if ($this->form_validation->run() == false) {

			//Edit//
			// Title sudah di-set otomatis di MY_Controller
			$data['parent'] = "List Penilaian";
			$data['page'] = "List Penilaian Edit";
			$this->load_template('admin/layout/admin_template', 'admin/modul_laporan/admin_listPenilaianEdit', $data);
		} else {

			$data_update = [
				'pegawai_id' => $this->input->post('karyawan'),
				'indikator_id' => $this->input->post('indikatorPenilaian'),
				'date' => $this->input->post('tanggalPenilaian'),
				'catatan' => $this->input->post('catatan')
			];

			$this->db->where('id', $this->input->post('z'));
			$this->db->update('tb_penilaian', $data_update);
			$this->sweetalert->success('Penilaian karyawan berhasil diupdate!');
			redirect('Admin/dataListPenilaian');
		}
	}


	public function deletePelanggaran()
	{
		// Set JSON header
		header('Content-Type: application/json');

		// Check if request is POST
		if ($this->input->method() !== 'post') {
			$response = array(
				'status' => 'error',
				'message' => 'Invalid request method.'
			);
			echo json_encode($response);
			return;
		}

		// Check if user is Admin
		if ($this->session->userdata('level') != 'Admin') {
			$response = array(
				'status' => 'error',
				'message' => 'Access denied. Only Admin can delete pelanggaran.'
			);
			echo json_encode($response);
			return;
		}

		$id = $this->input->post("id");

		// Validate ID
		if (empty($id) || !is_numeric($id)) {
			$response = array(
				'status' => 'error',
				'message' => 'Invalid ID provided.'
			);
			echo json_encode($response);
			return;
		}

		// Check if pelanggaran exists
		$dataPelanggaran = $this->db->get_where('tb_penilaian', ['id' => $id])->row();

		if ($dataPelanggaran) {
			// Delete the pelanggaran record
			$delete_result = $this->db->delete('tb_penilaian', ['id' => $id]);

			if ($delete_result) {
				$response = array(
					'status' => 'success',
					'message' => 'Data pelanggaran berhasil dihapus!'
				);
			} else {
				$response = array(
					'status' => 'error',
					'message' => 'Gagal menghapus data dari database.'
				);
			}
		} else {
			$response = array(
				'status' => 'error',
				'message' => 'Data pelanggaran tidak ditemukan!'
			);
		}

		echo json_encode($response);
		exit; // Ensure no additional output
	}
}
