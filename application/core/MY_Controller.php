<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    
    protected $data = array();
    
    public function __construct() {
        parent::__construct();
        
        // Load database dan library yang diperlukan
        $this->load->database();
        $this->load->library('template');
        $this->load->library('sweetalert');
        
        // Set default title dari database
        $this->set_website_title();
    }
    
    /**
     * Set website title
     */
    private function set_website_title() {
        $this->data['title'] = 'Komite SE Rayhan'; // Static title
    }
    
    /**
     * Set page title (akan menggabungkan dengan website name)
     * @param string $page_title
     * @param bool $append_website_name
     */
    protected function set_page_title($page_title = '', $append_website_name = false) {
        if ($append_website_name && !empty($page_title)) {
            $this->data['title'] = $page_title . ' - ' . $this->get_website_name();
        } elseif (!empty($page_title)) {
            $this->data['title'] = $page_title;
        }
        // Jika page_title kosong, gunakan default website name yang sudah di-set
    }
    
    /**
     * Get website name
     * @return string
     */
    protected function get_website_name() {
        return 'Komite SE Rayhan'; // Static website name
    }
    
    /**
     * Set data untuk view
     * @param string $key
     * @param mixed $value
     */
    protected function set_data($key, $value) {
        $this->data[$key] = $value;
    }
    
    /**
     * Get all data untuk view
     * @return array
     */
    protected function get_data() {
        return $this->data;
    }
    
    /**
     * Load template dengan data yang sudah di-set
     * @param string $template
     * @param string $view
     * @param array $additional_data
     */
    protected function load_template($template, $view, $additional_data = array()) {
        // Gabungkan data default dengan additional data
        $view_data = array_merge($this->data, $additional_data);
        
        // Load template menggunakan Template library
        $this->template->load($template, $view, $view_data);
    }
    
    /**
     * Clear all flash data to prevent persistent alerts
     */
    protected function clear_flash_data() {
        // Clear all possible flash data variations
        $flash_keys = [
            'sweetalert', 'message', 'error', 'success', 'warning', 'info',
            '__ci_flash_sweetalert', '__ci_flash_message', '__ci_flash_error',
            '__ci_flash_success', '__ci_flash_warning', '__ci_flash_info'
        ];
        
        foreach ($flash_keys as $key) {
            $this->session->unset_userdata($key);
        }
        
        // Clear any remaining flash data markers
        $session_data = $this->session->userdata();
        if (is_array($session_data)) {
            foreach ($session_data as $key => $value) {
                if (strpos($key, '__ci_flash_') === 0) {
                    $this->session->unset_userdata($key);
                }
            }
        }
    }
    
    /**
     * Safe redirect with flash data cleanup
     * @param string $uri
     * @param string $method
     * @param int $code
     */
    protected function safe_redirect($uri = '', $method = 'auto', $code = NULL) {
        // Clear any persistent flash data before redirect
        $this->clear_flash_data();
        redirect($uri, $method, $code);
    }
}
