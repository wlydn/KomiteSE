<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Ajax extends CI_Controller {

	public function __construct(){

		parent::__construct();


		/*-- untuk mengatasi error confirm form resubmission  --*/
		header('Cache-Control: no-cache, must-revalidate, max-age=0');
		header('Cache-Control: post-check=0, pre-check=0',false);
		header('Pragma: no-cache');
		$this->load->model('ajax_model');
		$this->load->model('admin_model');
	}


	/*-- Server-side Data Karyawan --*/
	public function karyawanData(){

		$list = $this->ajax_model->get_karyawan();
		$data = array();
		$no = isset($_POST['start']) ? $_POST['start'] : 0;
		foreach ($list as $karyawan) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $karyawan->nik;
			$row[] = $karyawan->nama;
			$row[] = $karyawan->departemen;
			$row[] = $karyawan->jbtn;
			$row[] = '<span class="badge badge-success">'.$karyawan->stts_aktif.'</span>';

			$data[] = $row;
		}

		$output = array(
			"draw" => isset($_POST['draw']) ? $_POST['draw'] : 1,
			"recordsTotal" => $this->ajax_model->count_all_karyawan(),
			"recordsFiltered" => $this->ajax_model->count_filtered_karyawan(),
			"data" => $data,
			"csrf_hash" => $this->security->get_csrf_hash()
		);
		//output to json format
		echo json_encode($output);

	}

	/*-- Server-side Data Pengguna --*/
	public function penggunaData(){

		$list = $this->ajax_model->get_pengguna();
		$data = array();
		$no = isset($_POST['start']) ? $_POST['start'] : 0;
		foreach ($list as $field) {

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $field->username_nik ? $field->username_nik : '-';
			$row[] = $field->full_name ? $field->full_name : '-';
			$row[] = $field->level ? $field->level : '-';

			if($field->status == 1){
				$row[] = '<button class="btn btn-sm btn-success" title="Detail" readonly >Active</button>';
			}else{
				$row[] = '<button class="btn btn-sm btn-danger" title="Detail" readonly >Not Active</button>';
			};
			$row[] = '
			<a class="btn btn-sm btn-warning" style="margin-right:10px; height:32px; width:32px;" href="../Admin/pengaturanPenggunaEdit/'.$this->encrypt->encode($field->id).'" title="Edit"><i class="fas fa-edit text-light"></i></a>
			<a class="btn btn-sm btn-danger" style="margin-right:10px; height:32px; width:32px;" id="'.$field->username_nik.'" onclick="deleteDataPengguna(\''.$field->username_nik.'\')"  title="Delete"><i class="fas fa-trash text-white"></i></a>
			';

			$data[] = $row;

		}

		$output = array(

			"draw" => isset($_POST['draw']) ? $_POST['draw'] : 1,
			"recordsTotal" => $this->ajax_model->count_all_pengguna(),
			"recordsFiltered" => $this->ajax_model->count_filtered_pengguna(),
			"data" => $data,
			"csrf_hash" => $this->security->get_csrf_hash()

		);

		/*-- Output Dalam Format JSON --*/
		echo json_encode($output);

	}

	public function fetch_nikUser(){

		if($this->input->post('idUser')){

			$dataUser = $this->db->get_where('pegawai',['id' => $this->input->post('idUser')])->row();
			$data['nama'] = $dataUser->nama;
			$data['nik'] = $dataUser->nik;
			echo json_encode($data);
		}
	}

	
	/*-- Server-side Data Penilaian --*/
	public function penilaianData(){

		$list = $this->ajax_model->get_penilaian();
		$data = array();
		$no = isset($_POST['start']) ? $_POST['start'] : 0;
		foreach ($list as $penilaian) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<div style="width: 80px; white-space: normal;">' . ($penilaian->nik ? $penilaian->nik : '-') . '</div>';
			$row[] = '<div style="width: 150px; white-space: normal;">' . ($penilaian->nama ? $penilaian->nama : '-') . '</div>';
			$row[] = $penilaian->jbtn ? $penilaian->jbtn : '-';
			$row[] = '<div style="width: 250px; white-space: normal;">' . ($penilaian->violation_name ? $penilaian->violation_name : '-') . '</div>';
			$row[] = $penilaian->catatan ? $penilaian->catatan : '-';
			$row[] = $penilaian->date ? date('d-m-Y', strtotime($penilaian->date)) : '-';
			$row[] = $penilaian->pelapor_nama ? $penilaian->pelapor_nama : '-';
			
			if($this->session->userdata('level') == 'Admin'){
				$row[] = '
				<a class="btn btn-sm btn-warning" style="margin-right:10px; height:30px; width:30px;" href="#" title="Edit" disabled><i class="fas fa-edit text-light"></i></a>
				<a class="btn btn-sm btn-danger" style="margin-right:10px; height:30px; width:30px;" id="'.$penilaian->id.'" onclick="deleteDataPenilaianAdmin('.$penilaian->id.')" title="Delete"><i class="fas fa-trash text-white"></i></a>';
			} elseif($this->session->userdata('level') == 'User') {
				$row[] = ''; // No actions displayed for User role
			}
			$data[] = $row;
		}

		$output = array(
			"draw" => isset($_POST['draw']) ? $_POST['draw'] : 1,
			"recordsTotal" => $this->ajax_model->count_all_penilaian(),
			"recordsFiltered" => $this->ajax_model->count_filtered_penilaian(),
			"data" => $data,
			"csrf_hash" => $this->security->get_csrf_hash()
		);
		
		echo json_encode($output);
	}

	/*-- Delete Penilaian Data --*/
	public function penilaianDelete(){
		// Set content type to JSON
		header('Content-Type: application/json');
		
		try {
			// Check if user is logged in
			if(!$this->session->userdata('level')) {
				$response = array(
					'status' => 'error',
					'message' => 'Sesi Anda telah berakhir. Silakan login kembali.',
					'redirect' => true,
					'csrf_hash' => $this->security->get_csrf_hash()
				);
				echo json_encode($response);
				return;
			}
			
			// Check if user is Admin
			if($this->session->userdata('level') != 'Admin') {
				$response = array(
					'status' => 'error',
					'message' => 'Akses ditolak. Hanya Admin yang dapat menghapus data penilaian.',
					'csrf_hash' => $this->security->get_csrf_hash()
				);
				echo json_encode($response);
				return;
			}

			$id = $this->input->post("id");
			
			// Validate ID
			if(empty($id) || !is_numeric($id)) {
				$response = array(
					'status' => 'error',
					'message' => 'ID penilaian tidak valid!',
					'csrf_hash' => $this->security->get_csrf_hash()
				);
				echo json_encode($response);
				return;
			}
			
			// Check if penilaian exists
			$penilaian = $this->db->get_where('tb_penilaian', ['id' => $id])->row();
			
			if($penilaian) {
				// Delete the penilaian record
				$delete_result = $this->db->delete('tb_penilaian', ['id' => $id]);
				
				if($delete_result) {
					$response = array(
						'status' => 'success',
						'message' => 'Data penilaian berhasil dihapus!', // Redirect to List Penilaian page
						'csrf_hash' => $this->security->get_csrf_hash()
					);
				} else {
					$response = array(
						'status' => 'error',
						'message' => 'Gagal menghapus data penilaian!',
						'csrf_hash' => $this->security->get_csrf_hash()
					);
				}
			} else {
				$response = array(
					'status' => 'error',
					'message' => 'Data penilaian tidak ditemukan!',
					'csrf_hash' => $this->security->get_csrf_hash()
				);
			}
			
		} catch (Exception $e) {
			$response = array(
				'status' => 'error',
				'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
				'csrf_hash' => $this->security->get_csrf_hash()
			);
		}
		
		echo json_encode($response);
	}

	/*-- Clear SweetAlert Session Data --*/
	public function clearSweetAlertSession(){
		// Load sweetalert library
		$this->load->library('sweetalert');
		
		// Force clear all sweetalert session data
		$this->sweetalert->force_clear();
		
		// Return success response
		echo json_encode(['status' => 'success', 'message' => 'SweetAlert session cleared']);
	}

	/*-- Delete Pengguna Data --*/
	public function penggunaDelete(){
		// Set content type to JSON
		header('Content-Type: application/json');
		
		// Check if user is logged in
		if(!$this->session->userdata('level')) {
			$response = array(
				'status' => 'error',
				'message' => 'Sesi Anda telah berakhir. Silakan login kembali.',
				'redirect' => true
			);
			echo json_encode($response);
			return;
		}
		
		// Check if user is Admin
		if($this->session->userdata('level') != 'Admin') {
			$response = array(
				'status' => 'error',
				'message' => 'Akses ditolak. Hanya Admin yang dapat menghapus pengguna.'
			);
			echo json_encode($response);
			return;
		}

		// Get username from POST data
		$username = $this->input->post("username");
		
		// Validate username
		if(empty($username)) {
			$response = array(
				'status' => 'error',
				'message' => 'Username pengguna tidak valid!'
			);
			echo json_encode($response);
			return;
		}
		
		// Check if user exists by username
		$user = $this->db->get_where('tb_users', ['username' => $username])->row();
		if(!$user) {
			$response = array(
				'status' => 'error',
				'message' => 'Data pengguna tidak ditemukan!'
			);
			echo json_encode($response);
			return;
		}
		
		// Prevent deleting current logged in user
		if($user->username == $this->session->userdata('username')) {
			$response = array(
				'status' => 'error',
				'message' => 'Anda tidak dapat menghapus akun yang sedang digunakan!'
			);
			echo json_encode($response);
			return;
		}
		
		// Delete the user by username
		$delete_result = $this->db->delete('tb_users', ['username' => $username]);
		
		if($delete_result) {
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
	}

	/*-- Get CSRF Token for AJAX requests --*/
	public function getCSRFToken(){
		header('Content-Type: application/json');
		
		$response = array(
			'csrf_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash()
		);
		
		echo json_encode($response);
	}
}
