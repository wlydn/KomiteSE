<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_model extends CI_Model {

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}


	/*-----------------------------------*/
	/*--  Server Side List Pelanggaran --*/
	/*-----------------------------------*/

	/*-- nama tabel dari database  --*/
	// var $table = 'tb_pelanggaran';
	/*-- field yang ada di table user yang akan ditampilkan --*/
	// var $column_order = array(null, 'nisn','student_id','class_id','note');
	/*-- field yang diizin untuk pencarian --*/
	// var $column_search = array('nisn','note');
	/*-- Default Order --*/
	// var $order = array('id' => 'desc');



	private function _get_pelanggaran_query(){

		$this->db->from($this->table);

		$i = 0;
		/*-- looping awal  --*/
		foreach ($this->column_search as $item){

			/*-- jika datatable mengirimkan pencarian dengan metode POST   --*/
			if($_POST['search']['value']) {

				/*-- looping awal  --*/
				if($i===0){

					$this->db->group_start(); 
					$this->db->like($item, $_POST['search']['value']);

				}else{

					$this->db->or_like($item, $_POST['search']['value']);

				}

				if(count($this->column_search) - 1 == $i) 
					$this->db->group_end(); 
			}

			$i++;
		}

		if(isset($_POST['order'])){

			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);

		}else if(isset($this->order)){

			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);

		}
	}

	/*-----------------------------------*/
	/*--  Server Side Data User --------*/
	/*-----------------------------------*/

	/*-- nama tabel dari database  --*/
	var $tablePengguna = 'tb_users';
	/*-- field yang ada di table user yang akan ditampilkan --*/
	var $column_orderPengguna = array(null, 'tb_users.username','pegawai.nama','tb_users.level','tb_users.status');
	/*-- field yang diizin untuk pencarian --*/
	var $column_searchPengguna = array('pegawai.nama','tb_users.username');
	/*-- Default Order --*/
	var $orderPengguna = array('tb_users.id' => 'desc');



	private function _get_pengguna_query(){

		$this->db->select('
			tb_users.id, 
			tb_users.username as username_nik, 
			tb_users.level, 
			tb_users.status,
			pegawai.nama as full_name
		');
		$this->db->from($this->tablePengguna);
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');

		$i = 0;
		/*-- looping awal  --*/
		foreach ($this->column_searchPengguna as $item){

			/*-- jika datatable mengirimkan pencarian dengan metode POST   --*/
			if(isset($_POST['search']['value']) && $_POST['search']['value'] != '') {

				/*-- looping awal  --*/
				if($i===0){

					$this->db->group_start(); 
					$this->db->like($item, $_POST['search']['value']);

				}else{

					$this->db->or_like($item, $_POST['search']['value']);

				}

				if(count($this->column_searchPengguna) - 1 == $i) 
					$this->db->group_end(); 
			}

			$i++;
		}

		if(isset($_POST['order'])){

			$this->db->order_by($this->column_orderPengguna[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);

		}else if(isset($this->orderPengguna)){

			$orderPengguna = $this->orderPengguna;
			$this->db->order_by(key($orderPengguna), $orderPengguna[key($orderPengguna)]);

		}
	}

	function get_pengguna(){

		$this->_get_pengguna_query();
		if(isset($_POST['length']) && $_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();

	}


	function count_filtered_pengguna(){

		$this->_get_pengguna_query();
		$query = $this->db->get();
		return $query->num_rows();

	}


	function count_all_pengguna(){

		$this->db->from($this->tablePengguna);
		return $this->db->count_all_results();

	}

	/*-----------------------------------*/
	/*--  Server Side Data Karyawan -----*/
	/*-----------------------------------*/

	/*-- nama tabel dari database  --*/
	var $tableKaryawan = 'pegawai';
	/*-- field yang ada di table karyawan yang akan ditampilkan --*/
	var $columnKaryawan = array(null, 'nik','nama','departemen','jbtn','stts_aktif');
	/*-- field yang diizin untuk pencarian --*/
	var $column_searchKaryawan = array('nik','nama','departemen','jbtn');
	/*-- Default Order --*/
	var $orderKaryawan = array('nik' => 'asc');

	private function _get_karyawan_query(){

		$this->db->from($this->tableKaryawan);
		$this->db->where('stts_aktif', 'AKTIF');

		$i = 0;
		/*-- looping awal  --*/
		foreach ($this->column_searchKaryawan as $item){

			/*-- jika datatable mengirimkan pencarian dengan metode POST   --*/
			if(isset($_POST['search']['value']) && $_POST['search']['value'] != '') {

				/*-- looping awal  --*/
				if($i===0){

					$this->db->group_start(); 
					$this->db->like($item, $_POST['search']['value']);

				}else{

					$this->db->or_like($item, $_POST['search']['value']);

				}

				if(count($this->column_searchKaryawan) - 1 == $i) 
					$this->db->group_end(); 
			}

			$i++;
		}

		if(isset($_POST['order'])){

			$this->db->order_by($this->columnKaryawan[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);

		}else if(isset($this->orderKaryawan)){

			$orderKaryawan = $this->orderKaryawan;
			$this->db->order_by(key($orderKaryawan), $orderKaryawan[key($orderKaryawan)]);

		}
	}

	function get_karyawan(){

		$this->_get_karyawan_query();
		if(isset($_POST['length']) && $_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();

	}

	function count_filtered_karyawan(){

		$this->_get_karyawan_query();
		$query = $this->db->get();
		return $query->num_rows();

	}

	function count_all_karyawan(){

		$this->db->from($this->tableKaryawan);
		$this->db->where('stts_aktif', 'AKTIF');
		return $this->db->count_all_results();

	}

	/*-----------------------------------*/
	/*--  Server Side Data Penilaian ---*/
	/*-----------------------------------*/

	/*-- nama tabel dari database  --*/
	var $tablePenilaian = 'tb_penilaian';
	/*-- field yang ada di table penilaian yang akan ditampilkan --*/
	var $columnPenilaian = array(null, 'pegawai.nik','pegawai.nama','pegawai.jbtn','tb_indikator.violation_name','tb_penilaian.date','pelapor.nama');
	/*-- field yang diizin untuk pencarian --*/
	var $column_searchPenilaian = array('pegawai.nik','pegawai.nama','tb_indikator.violation_name','pelapor.nama');
	/*-- Default Order --*/
	var $orderPenilaian = array('tb_penilaian.date' => 'desc');

	private function _get_penilaian_query(){

		$this->db->select('
			tb_penilaian.id,
			tb_penilaian.user_id,
			tb_penilaian.pegawai_id,
			tb_penilaian.indikator_id,
			tb_penilaian.date,
			tb_penilaian.catatan,
			pegawai.nik,
			pegawai.nama,
			pegawai.jbtn,
			tb_indikator.violation_name,
			tb_indikator.get_point,
			tb_users.username,
			pelapor.nama as pelapor_nama
		');
		$this->db->from($this->tablePenilaian);
		$this->db->join('pegawai', 'tb_penilaian.pegawai_id = pegawai.id', 'left');
		$this->db->join('tb_indikator', 'tb_penilaian.indikator_id = tb_indikator.id', 'left');
		$this->db->join('tb_users', 'tb_penilaian.user_id = tb_users.id', 'left');
		$this->db->join('pegawai as pelapor', 'tb_users.username = pelapor.nik', 'left');

		// Filter by unit if provided
		if(isset($_POST['filterUnit']) && $_POST['filterUnit'] != '') {
			$this->db->where('pegawai.jbtn', $_POST['filterUnit']);
		}

		// Filter by date range if provided
		if(isset($_POST['filterTanggalMulai']) && $_POST['filterTanggalMulai'] != '') {
			$this->db->where('tb_penilaian.date >=', $_POST['filterTanggalMulai']);
		}

		if(isset($_POST['filterTanggalSelesai']) && $_POST['filterTanggalSelesai'] != '') {
			$this->db->where('tb_penilaian.date <=', $_POST['filterTanggalSelesai']);
		}

		$i = 0;
		/*-- looping awal  --*/
		foreach ($this->column_searchPenilaian as $item){

			/*-- jika datatable mengirimkan pencarian dengan metode POST   --*/
			if(isset($_POST['search']['value']) && $_POST['search']['value'] != '') {

				/*-- looping awal  --*/
				if($i===0){

					$this->db->group_start(); 
					$this->db->like($item, $_POST['search']['value']);

				}else{

					$this->db->or_like($item, $_POST['search']['value']);

				}

				if(count($this->column_searchPenilaian) - 1 == $i) 
					$this->db->group_end(); 
			}

			$i++;
		}

		if(isset($_POST['order'])){

			$this->db->order_by($this->columnPenilaian[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);

		}else if(isset($this->orderPenilaian)){

			$orderPenilaian = $this->orderPenilaian;
			$this->db->order_by(key($orderPenilaian), $orderPenilaian[key($orderPenilaian)]);

		}
	}

	function get_penilaian(){

		$this->_get_penilaian_query();
		if(isset($_POST['length']) && $_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();

	}

	function count_filtered_penilaian(){

		$this->_get_penilaian_query();
		$query = $this->db->get();
		return $query->num_rows();

	}

	function count_all_penilaian(){

		$this->db->from($this->tablePenilaian);
		return $this->db->count_all_results();

	}

	/*-----------------------------------*/
	/*--  Get Data by ID Methods -------*/
	/*-----------------------------------*/

	// Get penilaian by ID with all relations
	function get_penilaian_by_id($id){
		$this->db->select('
			tb_penilaian.id,
			tb_penilaian.user_id,
			tb_penilaian.pegawai_id,
			tb_penilaian.indikator_id,
			tb_penilaian.date,
			pegawai.nik,
			pegawai.nama as nama_karyawan,
			pegawai.jbtn as jabatan,
			pegawai.departemen,
			tb_indikator.code as kode_indikator,
			tb_indikator.violation_name as nama_penilaian,
			tb_indikator.get_point as poin,
			tb_users.username as penilai,
			tb_users.level as level_penilai
		');
		$this->db->from($this->tablePenilaian);
		$this->db->join('pegawai', 'tb_penilaian.pegawai_id = pegawai.id', 'left');
		$this->db->join('tb_indikator', 'tb_penilaian.indikator_id = tb_indikator.id', 'left');
		$this->db->join('tb_users', 'tb_penilaian.user_id = tb_users.id', 'left');
		$this->db->where('tb_penilaian.id', $id);
		return $this->db->get()->row();
	}

	// Get pegawai by ID
	function get_pegawai_by_id($id){
		$this->db->where('id', $id);
		return $this->db->get('pegawai')->row();
	}

	// Get indikator by ID
	function get_indikator_by_id($id){
		$this->db->where('id', $id);
		return $this->db->get('tb_indikator')->row();
	}

	// Get user by ID
	function get_user_by_id($id){
		$this->db->select('
			tb_users.id,
			tb_users.username,
			tb_users.level,
			tb_users.status,
			pegawai.nama as full_name,
			pegawai.nik,
			pegawai.jbtn as jabatan
		');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.pegawai_id = pegawai.id', 'left');
		$this->db->where('tb_users.id', $id);
		return $this->db->get()->row();
	}

	// Get penilaian by pegawai ID
	function get_penilaian_by_pegawai_id($pegawai_id){
		$this->db->select('
			tb_penilaian.id,
			tb_penilaian.date,
			tb_indikator.violation_name as penilaian,
			tb_indikator.get_point as poin,
			tb_users.username as penilai
		');
		$this->db->from($this->tablePenilaian);
		$this->db->join('tb_indikator', 'tb_penilaian.indikator_id = tb_indikator.id', 'left');
		$this->db->join('tb_users', 'tb_penilaian.user_id = tb_users.id', 'left');
		$this->db->where('tb_penilaian.pegawai_id', $pegawai_id);
		$this->db->order_by('tb_penilaian.date', 'desc');
		return $this->db->get()->result();
	}

	// Get penilaian by user ID (who made the assessment)
	function get_penilaian_by_user_id($user_id){
		$this->db->select('
			tb_penilaian.id,
			tb_penilaian.date,
			pegawai.nama as nama_karyawan,
			pegawai.nik,
			tb_indikator.violation_name as penilaian,
			tb_indikator.get_point as poin
		');
		$this->db->from($this->tablePenilaian);
		$this->db->join('pegawai', 'tb_penilaian.pegawai_id = pegawai.id', 'left');
		$this->db->join('tb_indikator', 'tb_penilaian.indikator_id = tb_indikator.id', 'left');
		$this->db->where('tb_penilaian.user_id', $user_id);
		$this->db->order_by('tb_penilaian.date', 'desc');
		return $this->db->get()->result();
	}

	// Get penilaian by indikator ID
	function get_penilaian_by_indikator_id($indikator_id){
		$this->db->select('
			tb_penilaian.id,
			tb_penilaian.date,
			pegawai.nama as nama_karyawan,
			pegawai.nik,
			tb_users.username as penilai
		');
		$this->db->from($this->tablePenilaian);
		$this->db->join('pegawai', 'tb_penilaian.pegawai_id = pegawai.id', 'left');
		$this->db->join('tb_users', 'tb_penilaian.user_id = tb_users.id', 'left');
		$this->db->where('tb_penilaian.indikator_id', $indikator_id);
		$this->db->order_by('tb_penilaian.date', 'desc');
		return $this->db->get()->result();
	}
}
?>
