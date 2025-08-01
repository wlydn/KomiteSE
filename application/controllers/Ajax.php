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
			<a class="btn btn-sm btn-danger" style="margin-right:10px; height:32px; width:32px;" id="'.$field->id.'" onclick="deleteDataPengguna('.$field->id.')"  title="Delete"><i class="fas fa-trash text-white"></i></a>
			';

			$data[] = $row;

		}

		$output = array(

			"draw" => isset($_POST['draw']) ? $_POST['draw'] : 1,
			"recordsTotal" => $this->ajax_model->count_all_pengguna(),
			"recordsFiltered" => $this->ajax_model->count_filtered_pengguna(),
			"data" => $data,

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
				<a class="btn btn-sm btn-warning" style="margin-right:10px; height:30px; width:30px;" href="../Admin/listPenilaianEdit/'.$this->encrypt->encode($penilaian->id).'" title="Edit"><i class="fas fa-edit text-light"></i></a>
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
		);
		
		echo json_encode($output);
	}

	/*-- Delete Penilaian Data --*/
	public function penilaianDelete(){
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
				'message' => 'Akses ditolak. Hanya Admin yang dapat menghapus data penilaian.'
			);
			echo json_encode($response);
			return;
		}

		$id = $this->input->post("id");
		
		// Validate ID
		if(empty($id) || !is_numeric($id)) {
			$response = array(
				'status' => 'error',
				'message' => 'ID penilaian tidak valid!'
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
					'message' => 'Data penilaian berhasil dihapus!'
				);
			} else {
				$response = array(
					'status' => 'error',
					'message' => 'Gagal menghapus data penilaian!'
				);
			}
		} else {
			$response = array(
				'status' => 'error',
				'message' => 'Data penilaian tidak ditemukan!'
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
}
