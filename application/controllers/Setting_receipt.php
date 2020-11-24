<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_receipt extends CI_Controller {
	
	function __construct() {
		parent::__construct();
	}

    public function index(){
        $arr_data = array();

        $arr_data['receipt_size'] = $this->db->select('*')->from('receipt_size')->where("`nav_hidden` = '0' or `nav_hidden` is null")->get()->result_array();
        $arr_data['setting_receipt'] = $this->db->select('*')->from('coop_setting_receipt')->get()->row_array();

        $this->libraries->template('setting_receipt/index', $arr_data);
    }


    function save_setting_receipt()
    {
        $update_data = array(
            array(
                'id' => 1,
                'receipt_size_id' => $_POST['approval_id'][0],
                'copy_status' => $_POST['copy_receipt'],
                'sign_manager' => $_POST['sign_manager'],
                'header_status' => $_POST['header_status'],
                'loan_int_debt' => $_POST['loan_int_debt'],
                'alpha' => $_POST['alpha']
            )
        );
        $this->db->update_batch('coop_setting_receipt', $update_data, 'id');

        echo '<meta http-equiv= "refresh" content="0; url=/setting_receipt"/>';
    }
}