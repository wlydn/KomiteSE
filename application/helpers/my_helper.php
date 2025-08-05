<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('validate_id')) {
    function validate_id($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return false;
        }
        return true;
    }
}

if (!function_exists('get_user_data')) {
    function get_user_data()
    {
        $CI = &get_instance();
        $CI->load->database();
        $CI->db->select('pegawai.nama as nama_pegawai, pegawai.jk as jenis_kelamin');
        $CI->db->from('tb_users');
        $CI->db->join('pegawai', 'tb_users.username = pegawai.nik', 'left');
        $CI->db->where('tb_users.username', $CI->session->userdata('username'));
        return $CI->db->get()->row();
    }
}

if (!function_exists('is_login')) {
    function is_login($role = null)
    {
        $CI = &get_instance();
        if (!$CI->session->userdata('username')) {
            redirect('Home');
        }

        if ($role && $CI->session->userdata('level') !== $role) {
            redirect('Home/blocked');
        }
    }
}
