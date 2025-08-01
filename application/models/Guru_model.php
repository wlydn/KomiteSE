<?php defined('BASEPATH') or exit('No direct script access allowed');

class Guru_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getIndikatorPenilaian()
	{
		$query = "SELECT * FROM tb_indikator";
		return $this->db->query($query)->result();
	}

	public function getOneIndikatorPenilaian($id)
	{
		$query = "SELECT * FROM tb_indikator WHERE id = '$id' ";
		return $this->db->query($query)->row();
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
		$query = "SELECT COUNT(nik) as nik FROM pegawai";
		return $this->db->query($query)->row()->nik;
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

	public function getKategoriPenilaian()
	{
		$query = "SELECT * FROM tb_penilaian";
		return $this->db->query($query)->result();
	}

	public function getOneKategoriPelanggaran($id)
	{
		$query = "SELECT * FROM tb_indikator WHERE id = '$id' ";
		return $this->db->query($query)->row();
	}

	public function getGuru()
	{
		$query = "SELECT * FROM pegawai WHERE stts_aktif = 'AKTIF'";
		return $this->db->query($query)->result();
	}

	public function getOneGuru($id)
	{
		$query = "SELECT * FROM pegawai WHERE id = '$id' ";
		return $this->db->query($query)->row();
	}

	public function getCountPelanggaran($id)
	{
		$query = "SELECT COUNT(id) AS total_penilaian FROM tb_penilaian WHERE pegawai_id = $id";
		return $this->db->query($query)->row();
	}

	public function getKaryawan()
	{
		$query = "SELECT * FROM pegawai WHERE stts_aktif = 'AKTIF'";
		return $this->db->query($query)->result();
	}

	public function getOneKaryawan($id)
	{
		$query = "SELECT * FROM pegawai WHERE id = '$id' ";
		return $this->db->query($query)->row();
	}

	public function getOneUsers($id)
	{
		$query = "SELECT * FROM tb_users WHERE id = '$id' ";
		return $this->db->query($query)->row();
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

	// Method tambahan untuk kompatibilitas
	public function getOneSiswa($id)
	{
		// Return data karyawan sebagai pengganti siswa
		return $this->getOneKaryawan($id);
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

	public function getKategoriPelanggaran()
	{
		$query = "SELECT * FROM tb_indikator";
		return $this->db->query($query)->result();
	}

	public function getOneWali($id)
	{
		// Return data karyawan sebagai pengganti wali
		return $this->getOneKaryawan($id);
	}

	public function getCountIndikatorPenilaian()
	{

		$query = "SELECT COUNT(violation_name) as nmPelanggaran FROM tb_indikator";
		return $this->db->query($query)->row()->nmPelanggaran;
	}

	public function getCountPenilaian()
	{
		// Return count pegawai sebagai pengganti guru
		$query = "SELECT COUNT(id) as total FROM tb_penilaian";
		return $this->db->query($query)->row()->total;
	}

	public function getCountPenilaianMingguan()
	{
		// Return count penilaian dalam minggu ini (Senin sampai Minggu) berdasarkan kolom date (tanggal penilaian)
		$query = "SELECT COUNT(id) as total FROM tb_penilaian WHERE date >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY) AND date <= DATE_ADD(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 6 DAY)";
		return $this->db->query($query)->row()->total;
	}

	public function getCountPenilaianBulanan()
	{
		// Return count penilaian dalam bulan ini berdasarkan kolom date (tanggal penilaian)
		$query = "SELECT COUNT(id) as total FROM tb_penilaian WHERE MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE())";
		return $this->db->query($query)->row()->total;
	}
}
