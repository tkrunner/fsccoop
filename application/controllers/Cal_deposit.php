<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cal_deposit extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model("Deposit_modal", "deposit_modal");
	}
	
	function test_accu_int_item() {
		$data['account_id'] ='1203183';
		$data['date_cal'] ='2020-08-18';
		$result = $this->deposit_modal->cal_accu_int($data);
		echo '<pre>'; print_r($result); echo '</pre>';
		exit;
	}
	
	function accu_int_item_view() {		
		//$account_id = '1203183';
		$arr_data = array();
		if(!empty($_POST)){			
			$data['account_id'] = @$_POST['account_id'];
			$data['date_cal'] = $this->center_function->ConvertToSQLDate(@$_POST['date_cal']);
			$arr_data['data'] = $this->deposit_modal->cal_accu_int($data);
			$arr_data['account_id'] = @$_POST['account_id'];
		}
		
		$this->libraries->template('save_money/accu_int_item_view',$arr_data);
	}
	
}
