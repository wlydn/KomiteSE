<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/**
 * @property Home_model $home_model
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_DB_query_builder $db
 * @property Sweetalert $sweetalert
 */
class Home extends MY_Controller
{

	public function __construct()
	{

		parent::__construct();


		/*-- untuk mengatasi error confirm form resubmission  --*/
		header('Cache-Control: no-cache, must-revalidate, max-age=0');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
		$this->load->model('home_model');
	}

	public function index()
	{

		if ($this->session->userdata('level') == 'Admin') {
			redirect('Admin');
		} elseif ($this->session->userdata('level') == 'User') {
			redirect('User');
		}

		$this->set_page_title("Komite SE Rayhan");
		$data['page'] = "Login";
		$this->load_template('home/layout/home_template', 'home/home_login', $data);
	}

	public function login()
	{

		$this->form_validation->set_rules('username', 'Username', 'trim|required|htmlspecialchars');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if ($this->form_validation->run() == false) {

			// Title sudah di-set otomatis di MY_Controller
			$data['page'] = "Login";
			$this->load_template('home/layout/home_template', 'home/home_login', $data);
		} else {

			$nik = $this->input->post('username'); // NIK diambil dari input username
			$password = $this->input->post('password');

			// Query untuk mendapatkan user berdasarkan NIK pegawai dengan relasi
			$user = $this->db->select('tb_users.*')
				->from('tb_users')
				->join('pegawai', 'tb_users.pegawai_id = pegawai.id')
				->where('pegawai.nik', $nik)
				->get()
				->row();

			//Jika usernya Ada
			if ($user) {

				//jika usernya aktif
				if ($user->status == 1) {

					//cek password
					if (password_verify($password, $user->password)) {

						// Only allow Admin and User access
						if ($user->level == 'Admin' || $user->level == 'User') {
							// Clear any existing alert flashdata before successful login
							$this->session->unset_userdata('sweetalert');

							$data = [
								'username' => $user->username,
								'level' => $user->level,
							];

							$this->session->set_userdata($data);

							if ($user->level == 'Admin') {
								redirect('Admin');
							} elseif ($user->level == 'User') {
								redirect('User');
							}
						} else {
							$this->sweetalert->error('Access Denied! Only Admin and User are allowed.');
							redirect('Home');
						}
					} else {

						$this->sweetalert->error('Wrong Password!');
						redirect('Home');
					}
				} else {

					$this->sweetalert->error('User Not Active!');
					redirect('Home');
				}
			} else {

				$this->sweetalert->error('username Not Found!');
				redirect('Home');
			}
		}
	}
	public function blocked()
	{

		$this->set_page_title("Acces Forbidden");
		$data = array(); // Empty data array
		$this->load->view('home/layout/home_403', $data);
	}


	public function logout()
	{

		$this->session->unset_userdata('username');
		$this->session->unset_userdata('level');

		// Destroy the entire session to prevent any leftover data
		$this->session->sess_destroy();

		$this->sweetalert->success('You have been logged out!');
		redirect(base_url());
	}
}
