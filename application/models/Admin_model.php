<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	// getCountKaryawan 
	public function getCountKaryawan()
	{
		$this->db->where('stts_aktif', 'AKTIF');
		return $this->db->count_all_results('pegawai');
	}

	public function getCountIndikatorPenilaian()
	{
		return $this->db->count_all_results('tb_indikator');
	}

	public function getCountUsers()
	{
		return $this->db->count_all_results('tb_users');
	}

	public function TopKrywn()
	{
		// Return top karyawan berdasarkan jumlah penilaian dalam minggu ini
		$this->db->select('COUNT(tb_penilaian.id) as total_pelanggaran');
		$this->db->select('pegawai.nama as std_name');
		$this->db->select('pegawai.nik');
		$this->db->select('pegawai.jbtn');
		$this->db->from('tb_penilaian');
		$this->db->join('pegawai', 'tb_penilaian.pegawai_id = pegawai.id', 'left');
		// Filter untuk minggu ini (Senin sampai Minggu)
		$this->db->where('tb_penilaian.date >=', 'DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)', FALSE);
		$this->db->where('tb_penilaian.date <=', 'DATE_ADD(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 6 DAY)', FALSE);
		$this->db->group_by('tb_penilaian.pegawai_id');
		$this->db->order_by('total_pelanggaran', 'desc');
		$this->db->limit(5);
		$query = $this->db->get();
		return $query->result();
	}

	public function TopPelanggaran()
	{
		// Return top indikator berdasarkan penggunaan dalam minggu ini
		$this->db->select('COUNT(tb_penilaian.id) as total_pelanggaran');
		$this->db->select('tb_indikator.violation_name');
		$this->db->select('tb_indikator.code');
		$this->db->from('tb_penilaian');
		$this->db->join('tb_indikator', 'tb_penilaian.indikator_id = tb_indikator.id');
		// Filter untuk minggu ini (Senin sampai Minggu)
		$this->db->where('tb_penilaian.date >=', 'DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)', FALSE);
		$this->db->where('tb_penilaian.date <=', 'DATE_ADD(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 6 DAY)', FALSE);
		$this->db->group_by('tb_penilaian.indikator_id');
		$this->db->order_by('total_pelanggaran', 'desc');
		$this->db->limit(5);
		$query = $this->db->get();
		return $query->result();
	}

	public function getPelanggaranByID($id, $single = false)
	{
		// Return penilaian berdasarkan karyawan ID
		$this->db->select('tb_penilaian.*, pegawai.nama as nama_pegawai, tb_indikator.violation_name');
		$this->db->from('tb_penilaian');
		$this->db->join('pegawai', 'tb_penilaian.pegawai_id = pegawai.id', 'left');
		$this->db->join('tb_indikator', 'tb_penilaian.indikator_id = tb_indikator.id', 'left');
		$this->db->where('pegawai.id', $id);

		if ($single) {
			return $this->db->get()->row();
		}

		return $this->db->get()->result();
	}

	public function getCountPelanggaran($id)
	{
		return $this->db->where('pegawai_id', $id)->from('tb_penilaian')->count_all_results();
	}

	public function getKaryawan($id = null)
	{
		if ($id === null) {
			return $this->db->get_where('pegawai', ['stts_aktif' => 'AKTIF'])->result();
		} else {
			return $this->db->get_where('pegawai', ['id' => $id])->row();
		}
	}

	public function getOnePelanggaran($id)
	{
		// Return satu penilaian berdasarkan ID penilaian
		$this->db->select('*');
		$this->db->select('tb_penilaian.id as id_penilaian');
		$this->db->select('pegawai.id as id_karyawan');
		$this->db->select('tb_indikator.violation_name');
		$this->db->from('tb_penilaian');
		$this->db->join('pegawai', 'tb_penilaian.pegawai_id = pegawai.id');
		$this->db->join('tb_indikator', 'tb_penilaian.indikator_id = tb_indikator.id');
		$this->db->where('tb_penilaian.id', $id);
		$query = $this->db->get();
		return $query->row();
	}

	public function getUsers()
	{
		return $this->db->get('tb_users')->result();
	}

	public function getOneUsers($id)
	{
		$this->db->select('
			tb_users.id,
			tb_users.username,
			tb_users.level,
			tb_users.status,
			pegawai.nama as nama_pegawai,
			pegawai.nik,
			pegawai.jk as jenis_kelamin
		');
		$this->db->from('tb_users');
		$this->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
		$this->db->where('tb_users.id', $id);
		return $this->db->get()->row();
	}

	// Method tambahan untuk mendukung sistem penilaian karyawan
	public function getIndikatorPenilaian()
	{
		return $this->db->get('tb_indikator')->result();
	}

	public function getOneIndikatorPenilaian($id)
	{
		return $this->db->get_where('tb_indikator', ['id' => $id])->row();
	}

	public function getPenilaian()
	{
		$this->db->select('pn.*, p.nama as nama_pegawai, i.violation_name, u.username as pelapor');
		$this->db->from('tb_penilaian as pn');
		$this->db->join('pegawai as p', 'pn.pegawai_id = p.id', 'left');
		$this->db->join('tb_indikator as i', 'pn.indikator_id = i.id', 'left');
		$this->db->join('tb_users as u', 'pn.user_id = u.id', 'left');
		$this->db->order_by('pn.date', 'desc');
		return $this->db->get()->result();
	}

	public function getOnePenilaian($id)
	{
		return $this->db->get_where('tb_penilaian', ['id' => $id])->row();
	}

	public function getCountPenilaian()
	{
		// Return count pegawai sebagai pengganti guru
		return $this->db->count_all_results('tb_penilaian');
	}

	public function getCountPenilaianMingguan()
	{
		// Return count penilaian dalam minggu ini (Senin sampai Minggu) berdasarkan kolom date (tanggal penilaian)
		$this->db->where('date >=', 'DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)', FALSE);
		$this->db->where('date <=', 'DATE_ADD(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 6 DAY)', FALSE);
		return $this->db->from('tb_penilaian')->count_all_results();
	}

	public function getCountPenilaianBulanan()
	{
		// Return count penilaian dalam bulan ini berdasarkan kolom date (tanggal penilaian)
		$this->db->where('MONTH(date)', 'MONTH(CURDATE())', FALSE);
		$this->db->where('YEAR(date)', 'YEAR(CURDATE())', FALSE);
		return $this->db->from('tb_penilaian')->count_all_results();
	}

	/**
	 * Get website name from database
	 * Used for page titles
	 */
}
