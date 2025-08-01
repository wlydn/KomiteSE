<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

function is_login(){

	$ci = get_instance();
	if (!$ci->session->userdata('username')){
		redirect('home');
	}
	else{
		// Check if user exists in database
		$username = $ci->session->userdata('username');
		$user = $ci->db->get_where('tb_users', ['username' => $username])->row();
		
		if (!$user) {
			// User not found in database, destroy session and redirect
			$ci->session->sess_destroy();
			$ci->load->library('sweetalert');
			$ci->sweetalert->error('Session expired. Please login again.');
			redirect('home');
		}

		$level = $ci->session->userdata('level');
		$parents = $ci->uri->segment(1);

		// Only allow Admin and User access
		if($level !== 'Admin' && $level !== 'User'){
			$ci->session->sess_destroy();
			$ci->load->library('sweetalert');
			$ci->sweetalert->error('Access Denied!');
			redirect('home');
		}

		// Check role-based access
		if($level == 'Admin' && $parents !== 'Admin'){
			redirect('Admin'); // Admin should access Admin controller
		}
		
		// Allow User level to access both User and Admin controllers for user management
		if($level == 'User' && $parents !== 'User' && $parents !== 'Admin'){
			redirect('User'); // User should access User controller by default
		}
	}
}

function get_user_data(){
	$ci = get_instance();
	$username = $ci->session->userdata('username');
	
	if (!$username) {
		redirect('home');
	}
	
	$user = $ci->db->get_where('tb_users', ['username' => $username])->row();
	
	if (!$user) {
		$ci->session->sess_destroy();
		$ci->load->library('sweetalert');
		$ci->sweetalert->error('Session expired. Please login again.');
		redirect('home');
	}
	
	return $user;
}
