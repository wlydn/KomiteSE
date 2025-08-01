<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Admin extends MY_Controller
{

	public function __construct()
	{

		parent::__construct();
		/*-- Check Session  --*/
		is_login();
		/*-- untuk mengatasi error confirm form resubmission  --*/
		header('Cache-Control: no-cache, must-revalidate, max-age=0');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
		$this->load->model('admin_model');
		
		// Clear persistent flash data on navigation to prevent repeated alerts
		if ($this->input->method() === 'get' && !$this->input->is_ajax_request()) {
			$this->clear_flash_data();
		}
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

		$data['murid'] = $this->admin_model->TopMurid();
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
		if (!isset($id)) {
			redirect('Admin');
		}
		if (is_numeric($id)) {
			redirect('Admin');
		}

		$data['user'] = get_user_data();

		$data['onePelanggaranAll'] = $this->admin_model->getOnePelanggaranByID($this->encrypt->decode($id));
		$data['oneSiswa'] = $this->admin_model->getOneSiswa($this->encrypt->decode($id));
		$data['pelanggaranTotal'] = $this->admin_model->getCountPelanggaran($this->encrypt->decode($id));
		$data['pelanggaran'] = $this->admin_model->getPelanggaranByID($this->encrypt->decode($id));

		// Title sudah di-set otomatis di MY_Controller
		$data['parent'] = "Dashboard";
		$data['page'] = "Detail Pelanggaran";
		$this->load_template('admin/layout/admin_template', 'admin/admin_dashboardDetail', $data);
	}


	public function dataKategoriKategoriPelanggaran()
	{

		$this->db->select('pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		$data['tipePelanggaran'] = $this->admin_model->getKategoriPelanggaran();

		// Title sudah di-set otomatis di MY_Controller
		$data['parent'] = "Data Kategori";
		$data['page'] = "Indikator Penilaian";
		$this->load_template('admin/layout/admin_template', 'admin/modul_dataKategori/admin_kategoriPelanggaran', $data);
	}

	public function dataKategoriKategoriPelanggaranAdd()
	{
		// Set JSON header for AJAX requests
		if($this->input->is_ajax_request()) {
			header('Content-Type: application/json');
		}

		$data['user'] = $this->db->get_where('tb_users', ['username' => $this->session->userdata('username')])->row();
		$data['tipePelanggaran'] = $this->admin_model->getKategoriPelanggaran();

		// Check if this is a POST request (form submission)
		if($this->input->method() === 'post') {
			
			// Set validation rules
			$this->form_validation->set_rules('code', 'Code', 'required|trim');
			$this->form_validation->set_rules('nama', 'Indikator Penilaian', 'required|trim');

			if ($this->form_validation->run() == false) {
				
				// If AJAX request, return JSON error
				if($this->input->is_ajax_request()) {
					$response = array(
						'status' => 'error',
						'message' => 'Validation failed',
						'errors' => $this->form_validation->error_array()
					);
					echo json_encode($response);
					return;
				}
				
				// For regular form submission, show form with errors
				$data['parent'] = "Data Kategori";
				$data['page'] = "Kategori Pelanggaran Add";
				$this->load_template('admin/layout/admin_template', 'admin/modul_dataKategori/admin_kategoriPelanggaran', $data);
				
			} else {
				
				// Validation passed, insert data
				$insert_data = [
					'code' => trim($this->input->post('code')),
					'violation_name' => htmlspecialchars(trim($this->input->post('nama')), ENT_QUOTES, 'UTF-8')
				];

				// Insert to database
				$insert_result = $this->db->insert('tb_tipe_pelanggaran', $insert_data);
				
				if($insert_result) {
					
					// If AJAX request, return JSON success
					if($this->input->is_ajax_request()) {
						$response = array(
							'status' => 'success',
							'message' => 'Indikator Penilaian "' . $this->input->post('nama') . '" berhasil ditambahkan!'
						);
						echo json_encode($response);
						return;
					}
					
					// For regular form submission
					$this->sweetalert->success('Indikator Penilaian "' . $this->input->post('nama') . '" Telah Ditambahkan!');
					redirect('Admin/dataKategoriKategoriPelanggaran');
					
				} else {
					
					// Database insert failed
					if($this->input->is_ajax_request()) {
						$response = array(
							'status' => 'error',
							'message' => 'Gagal menyimpan data ke database. Silakan coba lagi.'
						);
						echo json_encode($response);
						return;
					}
					
					$this->sweetalert->error('Gagal menyimpan data. Silakan coba lagi.');
					redirect('Admin/dataKategoriKategoriPelanggaran');
				}
			}
			
		} else {
			
			// GET request - show the form
			$data['parent'] = "Data Kategori";
			$data['page'] = "Indikator Penilaian";
			$this->load_template('admin/layout/admin_template', 'admin/modul_dataKategori/admin_kategoriPelanggaran', $data);
		}
	}


	public function dataKategoriKategoriPelanggaranEdit()
	{

		$data['user'] = $this->db->get_where('tb_users', ['username' => $this->session->userdata('username')])->row();

		$data['tipePelanggaran'] = $this->admin_model->getKategoriPelanggaran();

		$this->form_validation->set_rules('nama', 'Nama Kategori Pelanggaran', 'required');
		$this->form_validation->set_rules('point', 'Jumlah Point', 'required|trim|is_natural', [
			'is_natural' => 'Jumlah Point hanya berisi Angka'
		]);

		if ($this->form_validation->run() == false) {

			// Title sudah di-set otomatis di MY_Controller
			$data['parent'] = "Data Kategori";
			$data['page'] = "Kategori Pelanggaran Edit";
			$this->load_template('admin/layout/admin_template', 'admin/modul_dataKategori/admin_kategoriPelanggaran', $data);
		} else {

			$data = [

				'violation_name' => htmlspecialchars($this->input->post('nama')),
				'get_point' => $this->input->post('point')

			];

			$this->db->where('id', $this->input->post('z'));
			$this->db->update('tb_tipe_pelanggaran', $data);
			$this->sweetalert->success('Kategori Pelanggaran ' . $this->input->post('nama') . ' Telah Di Update!');
			redirect('Admin/dataKategoriKategoriPelanggaran');
		}
	}


	public function deleteIndikator()
	{
		// Set JSON header
		header('Content-Type: application/json');
		
		// Check if request is POST
		if($this->input->method() !== 'post') {
			$response = array(
				'status' => 'error',
				'message' => 'Invalid request method.'
			);
			echo json_encode($response);
			return;
		}
		
		// Check if user is Admin
		if($this->session->userdata('level') != 'Admin') {
			$response = array(
				'status' => 'error',
				'message' => 'Access denied. Only Admin can delete indikator penilaian.'
			);
			echo json_encode($response);
			return;
		}

		$id = $this->input->post("id");
		
		// Validate ID
		if(empty($id) || !is_numeric($id)) {
			$response = array(
				'status' => 'error',
				'message' => 'Invalid ID provided.'
			);
			echo json_encode($response);
			return;
		}
		
		// Check if indikator penilaian exists
		$indikator = $this->db->get_where('tb_tipe_pelanggaran', ['id' => $id])->row();
		
		if($indikator) {
			// Delete the indikator penilaian record
			$delete_result = $this->db->delete('tb_tipe_pelanggaran', ['id' => $id]);
			
			if($delete_result) {
				$response = array(
					'status' => 'success',
					'message' => 'Data indikator penilaian berhasil dihapus!'
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
				'message' => 'Data indikator penilaian tidak ditemukan!'
			);
		}
		
		echo json_encode($response);
		exit; // Ensure no additional output
	}

	// Keep old method for backward compatibility
	public function dataKategoriKategoriPelanggaranDelete()
	{
		$this->deleteIndikator();
	}

	public function dataKategoriListKaryawan()
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

	public function dataKategoriListPelanggaranAdd()
	{

		$data['user'] = $this->db->get_where('tb_users', ['username' => $this->session->userdata('username')])->row();


		$data['userAll'] = $this->admin_model->getKaryawan();
		$data['pelanggaranAll'] = $this->admin_model->getKategoriPelanggaran();

		$this->form_validation->set_rules('kelas', 'Kelas', 'required');
		$this->form_validation->set_rules('namaKelas', 'Nama Kelas', 'required');
		$this->form_validation->set_rules('namaSiswa', 'Nama Siswa', 'required');
		$this->form_validation->set_rules('pelapor', 'Pelapor', 'required');
		$this->form_validation->set_rules('pelanggaran', 'Kategori Pelanggaran', 'required');
		$this->form_validation->set_rules('catatan', 'Catatan', 'required');

		if ($this->form_validation->run() == false) {

			// Title sudah di-set otomatis di MY_Controller
			$data['parent'] = "List Pelanggaran";
			$data['page'] = "List Pelanggaran Add";
			$this->load_template('admin/layout/admin_template', 'admin/modul_dataKategori/admin_listPelanggaranAdd', $data);
		} else {

			$data = [

				'class_id' => $this->input->post('namaKelas'),
				'teacher_id' => $this->input->post('pelapor'),
				'student_id' => $this->input->post('namaSiswa'),
				'nisn' => $this->admin_model->getOneSiswa($this->input->post('namaSiswa'))->nisn,
				'wali_id' => $this->admin_model->getOneWali($this->input->post('namaSiswa'))->id,
				'type_id' => $this->input->post('pelanggaran'),
				'point' => $this->admin_model->getOneKategoriPelanggaran($this->input->post('pelanggaran'))->get_point,
				'note' => $this->input->post('catatan'),
				'reported_on' => date('Y-m-d H:i:s')

			];


			$kelasPoint = $this->db->get_where('tb_kelas', ['id' => $this->input->post('namaKelas')])->row()->total_poin;

			$point = array($kelasPoint, $this->admin_model->getOneKategoriPelanggaran($this->input->post('pelanggaran'))->get_point);

			$totalPoint = array_sum($point);

			$data1 = [

				'total_poin' => $totalPoint
			];

			$this->db->where('id', $this->input->post('namaKelas'));
			$this->db->update('tb_kelas', $data1);

			$this->sweetalert->success('Pelanggaran Siswa ' . $this->admin_model->getOneSiswa($this->input->post('namaSiswa'))->std_name . ' Telah Ditambahkan!');
			redirect('Admin/dataKategoriListPelanggaran');
		}
	}

	public function dataKategoriListPelanggaranPrint($id)
	{

		if (count($this->uri->segment_array()) > 3) {
			redirect('Admin');
		}
		if (!isset($id)) {
			redirect('Admin/dataKategoriListPelanggaran');
		}
		if (is_numeric($id)) {
			redirect('Admin/dataKategoriListPelanggaran');
		}

		$data['user'] = $this->db->get_where('tb_users', ['username' => $this->session->userdata('username')])->row();
		$data['oneWeb'] = $this->admin_model->getOneWebsite($this->session->userdata('school_name'));
		$data['onepel'] = $this->admin_model->getOnePelanggaran($this->encrypt->decode($id)); /*-- Load One Data Administrator --*/
		$data['oneSis'] = $this->admin_model->getOneSiswa($this->admin_model->getOnePelanggaran($this->encrypt->decode($id))->student_id);

		$data['title'] = "List Pelanggaran Detail";
		$data['page'] = "List Pelanggaran Detail";
		$this->load->view('admin/modul_dataKategori/admin_listPelanggaranPrint', $data);
	}


	public function dataKategoriListPelanggaranDetail($id = null)
	{

		if (count($this->uri->segment_array()) > 3) {
			redirect('Admin');
		}
		if (!isset($id)) {
			redirect('Admin/dataKategoriListPelanggaran');
		}
		if (is_numeric($id)) {
			redirect('Admin/dataKategoriListPelanggaran');
		}

		$data['user'] = $this->db->get_where('tb_users', ['username' => $this->session->userdata('username')])->row();

		$data['onepel'] = $this->admin_model->getOnePelanggaran($this->encrypt->decode($id)); /*-- Load One Data Administrator --*/

		// Title sudah di-set otomatis di MY_Controller
		$data['parent'] = "List Pelanggaran";
		$data['page'] = "List Pelanggaran Detail";
		$this->load_template('admin/layout/admin_template', 'admin/modul_dataKategori/admin_listPelanggaranDetail', $data);
	}


	public function dataKategoriListPelanggaranEdit($id = null)
	{

		if (count($this->uri->segment_array()) > 3) {
			redirect('Admin');
		}
		if (!isset($id)) {
			redirect('Admin/dataKategoriListPelanggaran');
		}
		if (is_numeric($id)) {
			redirect('Admin/dataKategoriListPelanggaran');
		}

		$data['user'] = $this->db->get_where('tb_users', ['username' => $this->session->userdata('username')])->row();

		$data['onepel'] = $this->admin_model->getOnePelanggaran($this->encrypt->decode($id)); /*-- Load One Data Administrator --*/
		$data['pelanggaranAll'] = $this->admin_model->getKategoriPelanggaran();
		$data['userAll'] = $this->admin_model->getKaryawan();

		$this->form_validation->set_rules('kelas', 'Kelas', 'required');
		$this->form_validation->set_rules('namaKelas', 'Nama Kelas', 'required');
		$this->form_validation->set_rules('namaSiswa', 'Nama Siswa', 'required');
		$this->form_validation->set_rules('pelapor', 'Pelapor', 'required');
		$this->form_validation->set_rules('pelanggaran', 'Kategori Pelanggaran', 'required');
		$this->form_validation->set_rules('catatan', 'Catatan', 'required');

		if ($this->form_validation->run() == false) {

			// Title sudah di-set otomatis di MY_Controller
			$data['parent'] = "List Pelanggaran";
			$data['page'] = "List Pelanggaran Edit";
			$this->load_template('admin/layout/admin_template', 'admin/modul_dataKategori/admin_listPelanggaranEdit', $data);
		} else {

			$data = [

				'class_id' => $this->input->post('namaKelas'),
				'teacher_id' => $this->input->post('pelapor'),
				'student_id' => $this->input->post('namaSiswa'),
				'nisn' => $this->admin_model->getOneSiswa($this->input->post('namaSiswa'))->nisn,
				'wali_id' => $this->admin_model->getOneWali($this->input->post('namaSiswa'))->id,
				'type_id' => $this->input->post('pelanggaran'),
				'point' => $this->admin_model->getOneKategoriPelanggaran($this->input->post('pelanggaran'))->get_point,
				'note' => $this->input->post('catatan'),
				'reported_on' => date('Y-m-d H:i:s')

			];

			$this->db->where('id', $this->input->post('z'));
			$this->db->update('tb_pelanggaran', $data);
			$this->sweetalert->success('List Pelanggarn Data Siswa  ' . $this->input->post('namaSiswa') . ' Telah Di Update!');
			redirect('Admin/dataKategoriListPelanggaran');
		}
	}

	public function dataMasterKaryawan()
	{

		$data['user'] = $this->db->get_where('tb_users', ['username' => $this->session->userdata('username')])->row();

		$data['userAll'] = $this->admin_model->getKaryawan();

		// Title sudah di-set otomatis di MY_Controller
		$data['parent'] = "Data Master";
		$data['page'] = "Data Semua Karyawan";
		$this->load_template('admin/layout/admin_template', 'admin/modul_dataMaster/admin_guru', $data);
	}

	public function dataMasterKaryawanAdd()
	{

		$data['user'] = $this->db->get_where('tb_users', ['username' => $this->session->userdata('username')])->row();

		$data['userAll'] = $this->admin_model->getKaryawan();

		$this->form_validation->set_rules('nik', 'NIK', 'required|trim|is_natural|is_unique[tb_guru.nik]', [
			'is_natural' => 'NIK Hanya Berisi Angka',
			'is_unique' => 'NIk Yang Anda Masukkan Sudah Terpakai!',
		]);
		$this->form_validation->set_rules('nama', 'Nama Guru', 'required');
		$this->form_validation->set_rules('mapel', 'Mata Pelajaran', 'required');

		if ($this->form_validation->run() == false) {

			// Title sudah di-set otomatis di MY_Controller
			$data['parent'] = "Data Guru";
			$data['page'] = "Add Data Guru";
			$this->load_template('admin/layout/admin_template', 'admin/modul_dataMaster/admin_guruAdd', $data);
		} else {

			$data = [

				'nik' => $this->input->post('nik'),
				'teacher_name' => $this->input->post('nama'),
				'subject' => $this->input->post('mapel')

			];

			$this->db->insert('tb_guru', $data);
			$this->sweetalert->success('Data Guru ' . $this->input->post('nama') . ' Telah Ditambahkan!');
			redirect('Admin/dataMasterKaryawan');
		}
	}


	public function dataMasterKaryawanDetail($id = null)
	{

		if (count($this->uri->segment_array()) > 3) {
			redirect('Admin');
		}
		if (!isset($id)) {
			redirect('Admin/dataMasterKaryawan');
		}
		if (is_numeric($id)) {
			redirect('Admin/dataMasterKaryawan');
		}

		$data['user'] = $this->db->get_where('tb_users', ['username' => $this->session->userdata('username')])->row();

		$data['oneGuru'] = $this->admin_model->getOneGuru($this->encrypt->decode($id)); /*-- Load One Data Administrator --*/

		// Title sudah di-set otomatis di MY_Controller
		$data['parent'] = "Data Guru";
		$data['page'] = "Detail Data Guru";
		$this->load_template('admin/layout/admin_template', 'admin/modul_dataMaster/admin_guruDetail', $data);
	}


	public function dataMasterKaryawanEdit($id = null)
	{

		if (count($this->uri->segment_array()) > 3) {
			redirect('Admin');
		}
		if (!isset($id)) {
			redirect('Admin/dataMasterKaryawan');
		}
		if (is_numeric($id)) {
			redirect('Admin/dataMasterKaryawan');
		}

		$data['user'] = $this->db->get_where('tb_users', ['username' => $this->session->userdata('username')])->row();

		$data['oneGuru'] = $this->admin_model->getOneGuru($this->encrypt->decode($id)); /*-- Load One Data Administrator --*/


		$this->form_validation->set_rules('nik', 'NIK', 'required|trim|is_natural', [
			'is_natural' => 'NIK Hanya Berisi Angka'
		]);
		$this->form_validation->set_rules('nama', 'Nama Guru', 'required');
		$this->form_validation->set_rules('mapel', 'Mata Pelajaran', 'required');

		if ($this->form_validation->run() == false) {

			// Title sudah di-set otomatis di MY_Controller
			$data['parent'] = "Data Guru";
			$data['page'] = "Edit Data Guru";
			$this->load_template('admin/layout/admin_template', 'admin/modul_dataMaster/admin_guruEdit', $data);
		} else {

			$data = [

				'nik' => $this->input->post('nik'),
				'teacher_name' => $this->input->post('nama'),
				'subject' => $this->input->post('mapel')

			];

			$this->db->where('id', $this->input->post('z'));
			$this->db->update('tb_guru', $data);
			$this->sweetalert->success('Data Guru  ' . $this->input->post('nama') . ' Telah Di Update!');
			redirect('Admin/dataMasterKaryawan');
		}
	}


	public function dataMasterKaryawanDelete()
	{

		$id = $this->input->post("id");
		$this->db->delete('tb_guru', ['id' => $id]);
		$sukses = "Deleted successfully.";
		echo json_encode($sukses);
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

			$this->form_validation->set_rules('addNIKAdmin', 'NIK Pegawai', 'required');
			$this->form_validation->set_rules('fullnameAdmin', 'FullName', 'required');
			$this->form_validation->set_rules('usernameAdmin', 'Username', 'required|is_unique[tb_users.username]', [
				'is_unique' => 'Username Sudah Dipakai!'
			]);
			$this->form_validation->set_rules('passwordAdmin', 'Password', 'required');
		} elseif ($this->input->post('level') == 'User') {

			$this->form_validation->set_rules('addNIKUser', 'NIK Pegawai', 'required');
			$this->form_validation->set_rules('fullnameUser', 'FullName', 'required');
			$this->form_validation->set_rules('usernameUser', 'Username', 'required|is_unique[tb_users.username]', [
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
				$pegawai_data = $this->admin_model->getOneKaryawan($pegawai_id);
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
				$pegawai_data = $this->admin_model->getOneKaryawan($pegawai_id);

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
		if (!isset($id)) {
			redirect('Admin/pengaturanPengguna');
		}
		if (is_numeric($id)) {
			redirect('Admin/pengaturanPengguna');
		}

		$this->db->select('pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		$data['oneUsers'] = $this->admin_model->getOneUsers($this->encrypt->decode($id)); /*-- Load One Data Administrator --*/
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
			$data = [
				'level' => $this->input->post('level', true)
			];

			// Only update password if it's provided
			if (!empty($this->input->post('password'))) {
				// Don't use escape_str on password hash as it corrupts the hash
				$data['password'] = password_hash($this->input->post('password', true), PASSWORD_DEFAULT);
			}

			$this->db->where('id', $this->input->post('z'));
			$this->db->update('tb_users', $data);
			$this->sweetalert->success('Data User Level Telah Di Update!');
			redirect('Admin/pengaturanPengguna');
		}
	}


	public function pengaturanPenggunaDelete()
	{
		// Check if user is Admin
		if($this->session->userdata('level') != 'Admin') {
			$error = "Access denied. Only Admin can delete users.";
			echo json_encode($error);
			return;
		}

		$id = $this->input->post("id");
		
		// Check if user exists
		$user = $this->db->get_where('tb_users', ['id' => $id])->row();
		if(!$user) {
			$error = "User not found.";
			echo json_encode($error);
			return;
		}
		
		$this->db->delete('tb_users', ['id' => $id]);
		$sukses = "Deleted successfully.";
		echo json_encode($sukses);
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

	public function listPenilaian(){

		$this->db->select('pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		// Get all penilaian data
		$data['penilaianAll'] = $this->admin_model->getPenilaian();
		$data['tipePenilaian'] = $this->admin_model->getKategoriPenilaian();

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
		$this->load_template('admin/layout/admin_template','admin/modul_laporan/admin_listPenilaian',$data);

	}

	public function listPelanggaran(){

		$this->db->select('pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		// Get all penilaian data
		$data['penilaianAll'] = $this->admin_model->getPenilaian();
		$data['tipePenilaian'] = $this->admin_model->getKategoriPenilaian();

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
		$this->load_template('admin/layout/admin_template','admin/modul_dataKategori/admin_listPelanggaran',$data);

	}

	public function listPenilaianAdd(){

		// Get user data with employee name from pegawai table
		$this->db->select('tb_users.id as user_id, tb_users.username, pegawai.nama as nama_pegawai, pegawai.nik, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();
		
		$data['karyawanAll'] = $this->admin_model->getKaryawan();
		$data['indikatorPenilaianAll'] = $this->admin_model->getIndikatorPenilaian();

		$this->form_validation->set_rules('karyawan','Nama Karyawan','required');
		$this->form_validation->set_rules('indikatorPenilaian','Indikator Penilaian','required');
		$this->form_validation->set_rules('pelapor','Pelapor','required');
		$this->form_validation->set_rules('tanggalPelaporan','Tanggal Pelaporan','required');
		$this->form_validation->set_rules('catatanPenilaian','Catatan Penilaian','required');

		if($this->form_validation->run() == false){

			// Title sudah di-set otomatis di MY_Controller
			$data['parent'] = "List Penilaian";
			$data['page'] = "Add Penilaian Karyawan";
			$this->load_template('admin/layout/admin_template','admin/modul_listPelanggaran/admin_listPelanggaranAdd',$data);

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
			redirect('Admin/listPenilaian');

		}

	}

	public function listPenilaianEdit($id = null){

		if (count($this->uri->segment_array()) > 3) {
			redirect('Admin');
		}
		if (!isset($id)) {
			redirect('Admin/listPenilaian');
		}
		if (is_numeric($id)) {
			redirect('Admin/listPenilaian');
		}

		$this->db->select('tb_users.id as user_id, tb_users.username, pegawai.nama as nama_pegawai, pegawai.nik, pegawai.jk as jenis_kelamin');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.username', $this->session->userdata('username'));
		$data['user'] = $this->db->get()->row();

		$data['onepel'] = $this->admin_model->getOnePelanggaran($this->encrypt->decode($id));

		if (!$data['onepel']) {
			redirect('Admin/listPenilaian');
			return;
		}

		$data['pelanggaranAll'] = $this->admin_model->getKategoriPelanggaran();
		$data['userAll'] = $this->admin_model->getKaryawan();

		$this->form_validation->set_rules('karyawan','Nama Karyawan','required');
		$this->form_validation->set_rules('indikatorPenilaian','Indikator Penilaian','required');
		$this->form_validation->set_rules('tanggalPenilaian','Tanggal Penilaian','required');

		if($this->form_validation->run() == false){

			//Edit//
			// Title sudah di-set otomatis di MY_Controller
			$data['parent'] = "List Penilaian";
			$data['page'] = "List Penilaian Edit";
			$this->load_template('admin/layout/admin_template','admin/modul_listPelanggaran/admin_listPelanggaranEdit',$data);

		}else{

			$data_update = [
				'pegawai_id' => $this->input->post('karyawan'),
				'indikator_id' => $this->input->post('indikatorPenilaian'),
				'date' => $this->input->post('tanggalPenilaian')
			];

			$this->db->where('id', $this->input->post('z'));
			$this->db->update('tb_penilaian', $data_update);
			$this->sweetalert->success('Penilaian karyawan berhasil diupdate!');
			redirect('Admin/listPenilaian');

		}
	}


	public function deletePelanggaran(){
		// Set JSON header
		header('Content-Type: application/json');
		
		// Check if request is POST
		if($this->input->method() !== 'post') {
			$response = array(
				'status' => 'error',
				'message' => 'Invalid request method.'
			);
			echo json_encode($response);
			return;
		}
		
		// Check if user is Admin
		if($this->session->userdata('level') != 'Admin') {
			$response = array(
				'status' => 'error',
				'message' => 'Access denied. Only Admin can delete pelanggaran.'
			);
			echo json_encode($response);
			return;
		}

		$id = $this->input->post("id");
		
		// Validate ID
		if(empty($id) || !is_numeric($id)) {
			$response = array(
				'status' => 'error',
				'message' => 'Invalid ID provided.'
			);
			echo json_encode($response);
			return;
		}
		
		// Check if pelanggaran exists
		$dataPelanggaran = $this->db->get_where('tb_pelanggaran',['id' => $id])->row();
		
		if($dataPelanggaran) {
			// Update kelas total point
			$dataKelas = $this->db->get_where('tb_kelas',['id' => $dataPelanggaran->class_id])->row();
			if($dataKelas) {
				$pengurangan = $dataKelas->total_poin - $dataPelanggaran->point;
				$data = [
					'total_poin' => $pengurangan
				];
				$this->db->where('id', $dataPelanggaran->class_id);
				$this->db->update('tb_kelas',$data);
			}

			// Delete the pelanggaran record
			$delete_result = $this->db->delete('tb_pelanggaran',['id' => $id]);
			
			if($delete_result) {
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

	// Keep old method for backward compatibility
	public function listPelanggaranDelete(){
		$this->deletePelanggaran();
	}

}
