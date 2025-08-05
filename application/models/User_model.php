<?php defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getIndikatorPenilaian()
	{
		return $this->db->get('tb_indikator')->result();
	}

	public function getOneIndikatorPenilaian($id)
	{
		return $this->db->get_where('tb_indikator', ['id' => $id])->row();
	}

	public function TopKrywn()
	{
		// Return top karyawan berdasarkan jumlah penilaian dalam minggu ini
		$this->db->select('COUNT(tb_penilaian.id) as total_penilaian');
		$this->db->select('pegawai.id as id_karyawan');
		$this->db->select('pegawai.nama as std_name');
		$this->db->select('pegawai.nik');
		$this->db->select('pegawai.jbtn');
		$this->db->from('tb_penilaian');
		$this->db->join('pegawai', 'tb_penilaian.pegawai_id = pegawai.id', 'left');
		// Filter untuk minggu ini (Senin sampai Minggu)
		$this->db->where('tb_penilaian.date >=', 'DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)', FALSE);
		$this->db->where('tb_penilaian.date <=', 'DATE_ADD(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 6 DAY)', FALSE);
		$this->db->group_by('tb_penilaian.pegawai_id');
		$this->db->order_by('total_penilaian', 'desc');
		$this->db->limit(5);
		$query = $this->db->get();
		return $query->result();
	}

	public function getCountKaryawan()
	{
		return $this->db->count_all_results('pegawai');
	}

	public function TopPenilaian()
	{
		// Return top indikator berdasarkan penggunaan dalam minggu ini
		$this->db->select('COUNT(tb_penilaian.id) as total_penilaian');
		$this->db->select('tb_indikator.violation_name');
		$this->db->select('tb_indikator.code');
		$this->db->from('tb_penilaian');
		$this->db->join('tb_indikator', 'tb_penilaian.indikator_id = tb_indikator.id');
		// Filter untuk minggu ini (Senin sampai Minggu)
		$this->db->where('tb_penilaian.date >=', 'DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)', FALSE);
		$this->db->where('tb_penilaian.date <=', 'DATE_ADD(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 6 DAY)', FALSE);
		$this->db->group_by('tb_penilaian.indikator_id');
		$this->db->order_by('total_penilaian', 'desc');
		$this->db->limit(5);
		$query = $this->db->get();
		return $query->result();
	}


	public function getCountPelanggaran($id)
	{
		return $this->db->where('pegawai_id', $id)->from('tb_penilaian')->count_all_results();
	}

	public function getKaryawan()
	{
		return $this->db->get_where('pegawai', ['stts_aktif' => 'AKTIF'])->result();
	}

	public function getOneKaryawan($id)
	{
		return $this->db->get_where('pegawai', ['id' => $id])->row();
	}

	public function getOneUsers($id)
	{
		return $this->db->get_where('tb_users', ['id' => $id])->row();
	}

	public function getPenilaianData()
	{
		$this->db->select('tb_penilaian.*, pegawai.jbtn as unit, pegawai.nama, tb_indikator.violation_name');
		$this->db->from('tb_penilaian');
		$this->db->join('pegawai', 'tb_penilaian.pegawai_id = pegawai.id', 'left');
		$this->db->join('tb_indikator', 'tb_penilaian.indikator_id = tb_indikator.id', 'left');
		$this->db->order_by('tb_penilaian.date', 'desc');
		$query = $this->db->get();
		return $query->result();
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

	public function getCountIndikatorPenilaian()
	{
		return $this->db->count_all_results('tb_indikator');
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
}
