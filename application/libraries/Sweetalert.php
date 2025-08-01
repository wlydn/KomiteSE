<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sweetalert {
    
    protected $CI;
    
    public function __construct() {
        $this->CI =& get_instance();
    }
    
    /**
     * Set success message
     */
    public function success($message, $title = 'Success!') {
        $this->CI->session->set_flashdata('sweetalert', [
            'type' => 'success',
            'title' => $title,
            'message' => $message
        ]);
    }
    
    /**
     * Set error message
     */
    public function error($message, $title = 'Error!') {
        $this->CI->session->set_flashdata('sweetalert', [
            'type' => 'error',
            'title' => $title,
            'message' => $message
        ]);
    }
    
    /**
     * Set warning message
     */
    public function warning($message, $title = 'Warning!') {
        $this->CI->session->set_flashdata('sweetalert', [
            'type' => 'warning',
            'title' => $title,
            'message' => $message
        ]);
    }
    
    /**
     * Set info message
     */
    public function info($message, $title = 'Info!') {
        $this->CI->session->set_flashdata('sweetalert', [
            'type' => 'info',
            'title' => $title,
            'message' => $message
        ]);
    }
    
    /**
     * Display sweetalert if exists in session
     */
    public function show() {
        $sweetalert = $this->CI->session->flashdata('sweetalert');
        if ($sweetalert) {
            // Escape special characters to prevent JavaScript errors
            $message = addslashes($sweetalert['message']);
            $title = addslashes($sweetalert['title']);
            
            // Comprehensive flash data clearing to prevent persistence
            $this->clear_all_sweetalert_data();
            
            return "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Check if SweetAlert has already been shown to prevent duplicates
                    if (!window.sweetAlertShown) {
                        window.sweetAlertShown = true;
                        Swal.fire({
                            icon: '{$sweetalert['type']}',
                            title: '{$title}',
                            text: '{$message}',
                            showConfirmButton: true,
                            timer: 3000
                        }).then(function() {
                            // Reset flag after alert is closed
                            window.sweetAlertShown = false;
                            // Clear any remaining session data via AJAX
                            if (typeof $ !== 'undefined') {
                                $.post('" . base_url('Ajax/clearSweetAlertSession') . "');
                            }
                        });
                    }
                });
            </script>";
        }
        return '';
    }
    
    /**
     * Comprehensive method to clear all SweetAlert related session data
     */
    private function clear_all_sweetalert_data() {
        // Clear all possible flash data variations
        $this->CI->session->unset_userdata('sweetalert');
        $this->CI->session->unset_userdata('__ci_flash_sweetalert');
        
        // Clear flash data markers
        $flash_data = $this->CI->session->userdata();
        if (is_array($flash_data)) {
            foreach ($flash_data as $key => $value) {
                if (strpos($key, '__ci_flash_') === 0 && strpos($key, 'sweetalert') !== false) {
                    $this->CI->session->unset_userdata($key);
                }
            }
        }
    }
    
    /**
     * Force clear all SweetAlert session data (can be called from controllers)
     */
    public function force_clear() {
        $this->clear_all_sweetalert_data();
    }
}
