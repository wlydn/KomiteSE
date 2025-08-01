<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Guru extends CI_Controller {

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
		$data['user'] = get_user_data();

		$data['murid'] = $this->guru_model->TopMurid();
		$data['pelanggaran'] = $this->guru_model->TopPelanggaran();


		$data['title'] = $this->guru_model->website();
		$data['parent'] = "Dashbord";
		$data['page'] = "Dashboard";
		$this->template->load('guru/layout/guru_template','guru/guru_dashboard',$data);

	}

	public function dashboardDetail($id){

		if (count($this->uri->segment_array()) > 3) {
			redirect('guru');
		}
		if (!isset($id)) {
			$this\->sweetalert->error('Data yang Anda Inginkan Tidak Mempunyai ID');
			redirect('guru');
		}
		if (is_numeric($id)) {
			$this\->sweetalert->error('Hanya Bisa Menggunakan Enkripsi');
			redirect('guru');
		} 
		
		$data['user'] = get_user_data();

		$data['onePelanggaranAll'] = $this->guru_model->getOnePelanggaranByID($this->encrypt->decode($id));
		$data['oneSiswa'] = $this->guru_model->getOneSiswa($this->encrypt->decode($id));
		$data['pelanggaranTotal'] = $this->guru_model->getCountPelanggaran($this->encrypt->decode($id));
		$data['pelanggaran'] = $this->guru_model->getPelanggaranByID($this->encrypt->decode($id));

		$data['title'] = $this->guru_model->website();
		$data['parent'] = "Data Kategori";
		$data['page'] = "Detail Pelanggaran";
		$this->template->load('guru/layout/guru_template','guru/guru_dashboardDetail',$data);

	}


	public function listPelanggaran(){

		$data['user'] = get_user_data();

		$data['tipePelanggaran'] = $this->guru_model->getKategoriPelanggaran();

		$data['title'] = $this->guru_model->website();
		$data['parent'] = "List Pelangaran";
		$data['page'] = "List Pelangaran";
		$this->template->load('guru/layout/guru_template','guru/modul_listPelanggaran/guru_listPelanggaran',$data);

	}

	public function listPelanggaranAdd(){

		$data['user'] = $this->db->get_where('tb_users',['username' => $this->session->userdata('username')])->row();
		$data['guruAll'] = $this->guru_model->getGuru();

		$data['pelanggaranAll'] = $this->guru_model->getKategoriPelanggaran();

		$this->form_validation->set_rules('kelas','Kelas','required');
		$this->form_validation->set_rules('namaKelas','Nama Kelas','required');
		$this->form_validation->set_rules('namaSiswa','Nama Siswa','required');
		$this->form_validation->set_rules('pelapor','Pelapor','required');
		$this->form_validation->set_rules('pelanggaran','Kategori Pelanggaran','required');
		$this->form_validation->set_rules('catatan','Catatan','required');

		if($this->form_validation->run() == false){

			$data['title'] = $this->guru_model->website();
			$data['parent'] = "List Pelanggaran";
			$data['page'] = "List Pelanggaran Add";
			$this->template->load('guru/layout/guru_template','guru/modul_listPelanggaran/guru_listPelanggaranAdd',$data);

		}else{

			$data = [

				'class_id' => $this->input->post('namaKelas'),
				'teacher_id' => $this->input->post('pelapor'),
				'student_id' => $this->input->post('namaSiswa'),
				'nisn' => $this->guru_model->getOneSiswa($this->input->post('namaSiswa'))->nisn,
				'wali_id' => $this->guru_model->getOneWali($this->input->post('namaSiswa'))->id,
				'type_id' => $this->input->post('pelanggaran'),
				'point' => $this->guru_model->getOneKategoriPelanggaran($this->input->post('pelanggaran'))->get_point,
				'note' => $this->input->post('catatan'),
				'reported_on' => date('Y-m-d H:i:s')

			];


			$this->db->insert('tb_pelanggaran', $data);


			$kelasPoint = $this->db->get_where('tb_kelas',['id' => $this->input->post('namaKelas')])->row()->total_poin;

			$point = array($kelasPoint, $this->guru_model->getOneKategoriPelanggaran($this->input->post('pelanggaran'))->get_point);

			$totalPoint = array_sum($point);

			$data1 = [

				'total_poin' => $totalPoint
			];

			$this->db->where('id', $this->input->post('namaKelas'));
			$this->db->update('tb_kelas',$data1);

			$this\->sweetalert->success('Pelanggaran Siswa '.$this->guru_model->getOneSiswa($this->input->post('namaSiswa'))->std_name.' Telah Ditambahkan!');
			redirect('guru/listPelanggaran');

		}

	}

	public function listPelanggaranPrint($id){

		if (count($this->uri->segment_array()) > 3) {
			redirect('Guru');
		}
		if (!isset($id)) {
			$this\->sweetalert->error('Data yang Anda Inginkan Tidak Mempunyai ID');
			redirect('Guru/listPelanggaran');
		}
		if (is_numeric($id)) {
			$this\->sweetalert->error('Hanya Bisa Menggunakan Enkripsi');
			redirect('Guru/listPelanggaran');
		} 
		$data['user'] = $this->db->get_where('tb_users',['username' => $this->session->userdata('username')])->row();

		$data['oneWeb'] = $this->guru_model->getOneWebsite($this->session->userdata('school_name'));
		$data['onepel'] = $this->guru_model->getOnePelanggaran($this->encrypt->decode($id)); /*-- Load One Data Administrator --*/
		$data['oneSis'] = $this->guru_model->getOneSiswa($this->guru_model->getOnePelanggaran($this->encrypt->decode($id))->student_id);

		$data['title'] = "List Pelanggaran Detail";
		$data['parent'] = "List Pelanggaran";
		$data['page'] = "List Pelanggaran Detail";
		$this->load->view('guru/modul_listPelanggaran/guru_listPelanggaranPrint',$data);

	}


	public function listPelanggaranDetail($id = null){

		if (count($this->uri->segment_array()) > 3) {
			redirect('guru');
		}
		if (!isset($id)) {
			$this\->sweetalert->error('Data yang Anda Inginkan Tidak Mempunyai ID');
			redirect('guru/listPelanggaran');
		}
		if (is_numeric($id)) {
			$this\->sweetalert->error('Hanya Bisa Menggunakan Enkripsi');
			redirect('guru/listPelanggaran');
		} 

		$data['user'] = $this->db->get_where('tb_users',['username' => $this->session->userdata('username')])->row();

		$data['onepel'] = $this->guru_model->getOnePelanggaran($this->encrypt->decode($id)); /*-- Load One Data Administrator --*/

		$data['title'] = $this->guru_model->website();
		$data['parent'] = "List Pelanggaran";
		$data['page'] = "List Pelanggaran Detail";
		$this->template->load('guru/layout/guru_template','guru/modul_listPelanggaran/guru_listPelanggaranDetail',$data);

	}


	public function listPelanggaranEdit($id = null){

		if (count($this->uri->segment_array()) > 3) {
			redirect('Guru');
		}
		if (!isset($id)) {
			$this\->sweetalert->error('Data yang Anda Inginkan Tidak Mempunyai ID');
			redirect('Guru/listPelanggaran');
		}
		if (is_numeric($id)) {
			$this\->sweetalert->error('Hanya Bisa Menggunakan Enkripsi');
			redirect('Guru/listPelanggaran');
		} 

		$data['user'] = $this->db->get_where('tb_users',['username' => $this->session->userdata('username')])->row();

		$data['onepel'] = $this->guru_model->getOnePelanggaran($this->encrypt->decode($id)); /*-- Load One Data Administrator --*/
		$data['pelanggaranAll'] = $this->guru_model->getKategoriPelanggaran();
		$data['guruAll'] = $this->guru_model->getGuru();

		$this->form_validation->set_rules('kelas','Kelas','required');
		$this->form_validation->set_rules('namaKelas','Nama Kelas','required');
		$this->form_validation->set_rules('namaSiswa','Nama Siswa','required');
		$this->form_validation->set_rules('pelapor','Pelapor','required');
		$this->form_validation->set_rules('pelanggaran','Kategori Pelanggaran','required');
		$this->form_validation->set_rules('catatan','Catatan','required');

		if($this->form_validation->run() == false){

			$data['title'] = $this->guru_model->website();
			$data['parent'] = "List Pelanggaran";
			$data['page'] = "List Pelanggaran Edit";
			$this->template->load('guru/layout/guru_template','guru/modul_listPelanggaran/guru_listPelanggaranEdit',$data);

		}else{

			$data = [

				'class_id' => $this->input->post('namaKelas'),
				'teacher_id' => $this->input->post('pelapor'),
				'student_id' => $this->input->post('namaSiswa'),
				'nisn' => $this->guru_model->getOneSiswa($this->input->post('namaSiswa'))->nisn,
				'wali_id' => $this->guru_model->getOneWali($this->input->post('namaSiswa'))->id,
				'type_id' => $this->input->post('pelanggaran'),
				'point' => $this->guru_model->getOneKategoriPelanggaran($this->input->post('pelanggaran'))->get_point,
				'note' => $this->input->post('catatan'),
				'reported_on' => date('Y-m-d H:i:s')

			];

			$this->db->where('id', $this->input->post('z'));
			$this->db->update('tb_pelanggaran',$data);
			$this\->sweetalert->success('List Pelanggarn Data Siswa  '.$this->input->post('namaSiswa').' Telah Di Update!');
			redirect('Guru/listPelanggaran');

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

		$data['title'] = $this->admin_model->website();
		$data['parent'] = "Profile";
		$data['page'] = "Profile";
		$this->template->load('guru/layout/guru_template','guru/guru_profile',$data);
	}

	public function editProfile(){

		$data['user'] = $this->db->get_where('tb_users',['username' => $this->session->userdata('username')])->row();
		
		$this->form_validation->set_rules('fullname','Fullname','required');

		if($this->form_validation->run() == false){

			$data['title'] = $this->admin_model->website();
			$data['parent'] = "Profile";
			$data['page'] = "Profile";
			$this->template->load('guru/layout/guru_template','guru/guru_profile',$data);

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
					$this\->sweetalert->success('Profile Telah Di Update!');
					redirect('Guru/profile');
				}

			}
		}

	public function changePassword(){

			$data['user'] = $this->db->get_where('tb_users',['username' => $this->session->userdata('username')])->row();

			$this->form_validation->set_rules('bb', 'New Password','required|trim|min_length[4]|matches[cc]');
			$this->form_validation->set_rules('cc', 'Confirm New Password','required|trim|min_length[4]|matches[bb]');

			if($this->form_validation->run() == false){

				$data['title'] = $this->admin_model->website();
				$data['parent'] = "Profile";
				$data['page'] = "Profile";
				$this->template->load('guru/layout/guru_template','guru/guru_profile',$data);

			}else{


				$new_password = $this->input->post('bb');


				$password_hash = password_hash($new_password, PASSWORD_DEFAULT);

				$this->db->set('password', $password_hash);
				$this->db->where('id', $this->input->post('dd'));
				$this->db->update('tb_users');

				$this\->sweetalert->success('password Berahasil Di Ubah!');
				redirect('Guru/profile');
			}

		}

	}
