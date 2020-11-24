<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documents extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model("Interest_modal", "Interest_modal");
	}
	public function index()
	{
		echo "TEST";
    }

    /**
     * เอกสารคำขอกู้เงินเพื่อการศึกษา
     * @author Nook
     */
    public function Contract($loan_id)
	{
		$arr_data = array();
		$this->load->library('Center_function');
		$this->load->model('Document', 'document');

		$data 	  = $this->document->fscoop_full($loan_id);
		$loan_period 	  = $this->document->loan_period($loan_id);
		$profile_location = $this->document->profile_location();
		$share_group 	  = $this->document->share_group($loan_id);
		$month_arr 		  = $this->center_function->month_arr();
		$month_short_arr  = $this->center_function->month_short_arr();

		$arr_data['profile_location']= $profile_location;
		$arr_data['data'] 	 = $data;
		$arr_data['loan_period'] 	 = $loan_period;
		$arr_data['share_group']  	 = $share_group;
		$arr_data['month_arr'] 	  	 = $month_arr;
		$arr_data['month_short_arr'] = $month_short_arr;

		$this->load->view('documents/contract',$arr_data);
	}
	
	public function Contract02($loan_id)
	{

		$arr_data = array();
		$this->load->model('Document', 'document');
		$this->load->library('Center_function');

		$data	 = $this->document->fscoop_full($loan_id);
		$profile_location = $this->document->profile_location();
		$loan_period 	 = $this->document->loan_period($loan_id);
		$share_group 	 = $this->document->share_group($loan_id);
		$month_arr 		 = $this->center_function->month_arr();
		$month_short_arr = $this->center_function->month_short_arr();

		$arr_data['share_group']	 = $share_group;
		$arr_data['loan_period'] 	 = $loan_period;
		$arr_data['data'] 	 = $data;
		$arr_data['profile_location']= $profile_location;
		$arr_data['month_arr'] 	 	 = $month_arr;
		$arr_data['month_short_arr'] = $month_short_arr;  

        $this->load->view('documents/contract02',$arr_data);
	}
	public function Contract03($loan_id)
	{
		$arr_data = array();
		$this->load->model('Document', 'document');
		$this->load->library('Center_function');

		$profile_location = $this->document->profile_location();
		$data 	 = $this->document->fscoop_full($loan_id);
		$loan_period 	 = $this->document->loan_period($loan_id);
		$share_group 	 = $this->document->share_group($loan_id);
		$month_arr 	 	 = $this->center_function->month_arr();
		$month_short_arr = $this->center_function->month_short_arr();

		$arr_data['data'] 	 = $data;
		$arr_data['loan_period'] 	 = $loan_period;
		$arr_data['share_group'] 	 = $share_group;
		$arr_data['month_arr'] 	 	 = $month_arr;
		$arr_data['month_short_arr'] = $month_short_arr;
		$arr_data['profile_location']= $profile_location;

        $this->load->view('documents/contract03',$arr_data);
	}
	public function Contract04($loan_id)
	{
		$arr_data = array();
		$this->load->model('Document', 'document');
		$this->load->library('Center_function');

		$share_group 	 	 = $this->document->share_group($loan_id);
		$data 	 	 = $this->document->fscoop_full($loan_id);
		$loan_period 	 	 = $this->document->loan_period($loan_id);
		$profile_location	 = $this->document->profile_location();
		$month_arr 	 	 	 = $this->center_function->month_arr();
		$month_short_arr 	 = $this->center_function->month_short_arr();

		$arr_data['data']	 = $data;
		$arr_data['loan_period']	 = $loan_period;
		$arr_data['share_group'] 	 = $share_group;
		$arr_data['month_arr'] 		 = $month_arr;
		$arr_data['month_short_arr'] = $month_short_arr;      
		$arr_data['profile_location']= $profile_location;                                                                                                           
		
        $this->load->view('documents/contract04',$arr_data);
	}
    function petition_emergent_atm_pdf($loan_id){
        $arr_data = array();

        $this->load->model('loan_model');
        $this->load->model('Document', 'document');
        $code_name = 'petition_emergent_atm';
        $setting_report = $this->document->setting_report($code_name);

        if($setting_report == '1'){
            $this->db->select(array(
                't1.*',
                't2.*',
                't3.prename_short',
                't4.district_name',
                't5.amphur_name',
                't6.province_name',
                't7.mem_group_name',
                't8.loan_reason',
                't9.date_transfer'
            ));
            $this->db->from('coop_loan as t1');
            $this->db->join("coop_mem_apply as t2","t2.member_id = t1.member_id","inner");
            $this->db->join("coop_prename as t3","t3.prename_id = t2.prename_id","left");
            $this->db->join("coop_district as t4","t2.c_district_id = t4.district_id","left");
            $this->db->join("coop_amphur as t5","t2.c_amphur_id = t5.amphur_id","left");
            $this->db->join("coop_province as t6","t2.c_province_id = t6.province_id","left");
            $this->db->join("coop_mem_group as t7","t2.level = t7.id","left");
            $this->db->join("coop_loan_reason as t8","t1.loan_reason = t8.loan_reason_id","left");
            $this->db->join("coop_loan_transfer as t9","t1.id = t9.loan_id","left");
            $this->db->where("t1.id = '".$loan_id."'");
            $row = $this->db->get()->result_array();
            $arr_data['data'] = $row[0];

            $this->db->select(array('date_period','principal_payment','total_paid_per_month'));
            $this->db->from('coop_loan_period');
            $this->db->where("loan_id = '".$loan_id."' AND period_count = '1'");
            $row = $this->db->get()->result_array();
            $arr_data['data_period_1'] = $row[0];

            $this->db->select(array('date_period','principal_payment','total_paid_per_month'));
            $this->db->from('coop_loan_period');
            $this->db->where("loan_id = '".$loan_id."'");
            $this->db->order_by("id DESC");
            $this->db->limit(1);
            $row = $this->db->get()->result_array();
            $arr_data['data_period_last'] = $row[0];

            $this->load->view('loan/petition_emergent_atm_pdf',$arr_data);
        }else if($setting_report == '2'){
            $data 		= $this->document->fscoop_full($loan_id);
            $month_arr 		 	= $this->center_function->month_arr();
            $month_short_arr 	= $this->center_function->month_short_arr();
            $share_group 		= $this->document->share_group($loan_id);
            $profile_location	= $this->document->profile_location();

            $loan_cost_code  = $this->document->loan_cost($loan_id, $data['member_id']); //รายจ่ายต่อเดือน
            $contract	= $this->document->contract_current($loan_id, $data['member_id'], $data['createdatetime']); //เงินกู้เดิม
            $deposit	= $this->document->deposit($data['member_id']); //เงินฝาก

            $arr_data['data'] 	 = $data;
            $arr_data['share_group']	 = $share_group;
            $arr_data['month_arr'] 		 = $month_arr;
            $arr_data['month_short_arr'] = $month_short_arr;
            $arr_data['profile_location']= $profile_location;
            $arr_data['contract']        = $contract;
            $arr_data['loan_cost_code']  = $loan_cost_code;
            $arr_data['deposit']         = $deposit;

            if($_GET['dev']=='dev'){
                echo '<pre>';print_r($arr_data);exit;
            }
            $this->load->view('documents/petition_emergent_atm_pdf',$arr_data);
        }
    }
	public function petition_emergent_pdf($loan_id)
	{
        $this->load->model('loan_model');
        $this->load->model('Document', 'document');
        $code_name = 'petition_emergent';
        $setting_report = $this->document->setting_report($code_name);

        if($setting_report == '1'){
            $arr_data['data'] = $this->loan_model->get_member($loan_id);
            $arr_data['data_period_1'] = $this->loan_model->get_data_period_1($loan_id);
            $arr_data['data_period_last'] = $this->loan_model->data_period_last($loan_id);
            $this->load->view('loan/petition_emergent_pdf',$arr_data);
        }else if($setting_report == '2'){
            $data 		= $this->document->fscoop_full($loan_id);
            $month_arr 		 	= $this->center_function->month_arr();
            $month_short_arr 	= $this->center_function->month_short_arr();
            $share_group 		= $this->document->share_group($loan_id);
            $profile_location	= $this->document->profile_location();

            $loan_cost_code  = $this->document->loan_cost($loan_id, $data['member_id']); //รายจ่ายต่อเดือน
            $contract	= $this->document->contract_current($loan_id, $data['member_id'], $data['createdatetime']); //เงินกู้เดิม
            $deposit	= $this->document->deposit($data['member_id']); //เงินฝาก

            $arr_data['data'] 	 = $data;
            $arr_data['share_group']	 = $share_group;
            $arr_data['month_arr'] 		 = $month_arr;
            $arr_data['month_short_arr'] = $month_short_arr;
            $arr_data['profile_location']= $profile_location;
            $arr_data['contract']        = $contract;
            $arr_data['loan_cost_code']  = $loan_cost_code;
            $arr_data['deposit']         = $deposit;

            if($_GET['dev']=='dev'){
                echo '<pre>';print_r($arr_data);exit;
            }
            $this->load->view('documents/petition_emergent_pdf',$arr_data);
        }else if($setting_report == '3'){
            //สำนักงบประมาณ
			$data 	 = $this->document->fscoop_full($loan_id);
            $loan_period_first 	 = $this->document->loan_period_first($loan_id);

            if(!empty($data['member_id'])) {
                $contract = $this->document->contract_current($loan_id, $data['member_id'], $data['createdatetime']); //เงินกู้เดิม
            }
			
            $arr_data['data'] 	         = $data;
            $arr_data['loan_guarantee']  = $data['coop_loan_guarantee'];
            $arr_data['data_old_loan']  = $contract['data'];
          
			$arr_data['full_name']= $arr_data['data']['prename_short'].$arr_data['data']['firstname_th']." ".$arr_data['data']['lastname_th'];
			$arr_data['position']= $arr_data['data']['position_name'];
			$arr_data['member_id']= $arr_data['data']['member_id'];
			$arr_data['phone_number']= $arr_data['data']['mobile'];
			$arr_data['contract_number']= $arr_data['data']['contract_number'];

			$arr_data['c_address_no'] = (@$arr_data['data']['c_address_no'] != '')?$arr_data['data']['c_address_no']:'';
			$arr_data['c_address_moo'] = (@$arr_data['data']['c_address_moo'] != '')?' หมู่ที่ '.$arr_data['data']['c_address_moo']:'';
			$arr_data['c_address_village'] = (@$arr_data['data']['c_address_village'] != '')?' หมู่บ้าน '.$arr_data['data']['c_address_village']:'';
			$arr_data['c_address_soi'] = (@$arr_data['data']['c_address_soi'] != '')?' ซ.'.$arr_data['data']['c_address_soi']:'';
			$arr_data['c_address_road'] = (@$arr_data['data']['c_address_road'] != '')?' '.$arr_data['data']['c_address_road']:'';
			$arr_data['c_district_name'] = (@$arr_data['data']['c_district_name'] != '')?(trim($arr_data['data']['c_province_name']) == $chk_bankkok)?' '.$arr_data['data']['c_district_name']:' '.trim($arr_data['data']['c_district_name']):'';
			$arr_data['c_amphur_name'] = (@$arr_data['data']['c_amphur_name'] != '')?(trim($arr_data['data']['c_province_name']) == $chk_bankkok)?' '.$arr_data['data']['c_amphur_name']:'  '.trim($arr_data['data']['c_amphur_name']):'';
			$arr_data['c_province_name'] = (@$arr_data['data']['c_province_name'] != '')?(trim($arr_data['data']['c_province_name']) == $chk_bankkok)?' '.$arr_data['data']['c_province_name']:'  '.trim($arr_data['data']['c_province_name']):'';
			$arr_data['c_zipcode'] = (@$arr_data['data']['c_zipcode'] != '')?$arr_data['data']['c_zipcode']:'';
		
			$arr_data['date_start_period'] = $arr_data['data']['date_start_period'];			
			//ยอดผ่อนต่อเดือน
			$arr_data['paid_per_month'] = ($arr_data['data']['pay_type']==1)?$loan_period_first['principal_payment']:$loan_period_first['total_paid_per_month'];
			
			$arr_data['level'] = @$arr_data['data']['mem_group_name_level'];   				                                    //สังกัด/โรงเรียน
			$arr_data['faction'] = @$arr_data['data']['mem_group_name_faction']; 
			$arr_data['member_income'] = @$arr_data['data']['salary']+@$arr_data['data']['other_income'];	
			$arr_data['write_at'] = 'สำนักงบประมาณ';

			$pdf = new FPDI();
			$arr_data['pdf'] = $pdf;
            $this->load->view('documents/bb_coop/petition_emergent',$arr_data);
        }

	}
	public function Contract07($loan_id)
	{
		$arr_data = array();
		$this->load->model('Document', 'document');
		$this->load->library('Center_function');

		$loan_period 		= $this->document->loan_period($loan_id);
		$data 		= $this->document->fscoop_full($loan_id);
		$month_arr 			= $this->center_function->month_arr();
		$month_short_arr 	= $this->center_function->month_short_arr();
		$share_group 		= $this->document->share_group($loan_id);
		$profile_location	= $this->document->profile_location();

		$arr_data['data'] 	 = $data;
		$arr_data['loan_period']	 = $loan_period;
		$arr_data['share_group'] 	 = $share_group;
		$arr_data['month_arr'] 		 = $month_arr;
		$arr_data['month_short_arr'] = $month_short_arr;
		$arr_data['profile_location']= $profile_location;  

		$this->load->view('documents/contract07',$arr_data);
	}

	public function share_guarantee_normal_pdf($loan_id)
	{
		$arr_data = array();
        $this->load->model('Document', 'document');
        $code_name = 'share_guarantee_normal';
        $setting_report = $this->document->setting_report($code_name);
        if($setting_report == '1'){
            $data 	 	= $this->document->fscoop_full($loan_id);
            $loan_period 	 	= $this->document->loan_period($loan_id);
            $share_group 	 	= $this->document->share_group($loan_id);
            $month_arr 		 	= $this->center_function->month_arr();
            $month_short_arr 	= $this->center_function->month_short_arr();
            $profile_location	= $this->document->profile_location();

            $arr_data['data'] 	 = $data;
            $arr_data['loan_period'] 	 = $loan_period;
            $arr_data['share_group'] 	 = $share_group;
            $arr_data['month_arr'] 	 	 = $month_arr;
            $arr_data['month_short_arr'] = $month_short_arr;
            $arr_data['profile_location']= $profile_location;

            $this->load->view('documents/share_guarantee_normal_pdf',$arr_data);
        }
	}

    function petition_normal_pdf($loan_id){
        $arr_data = array();

        $this->load->model('Document', 'document');
        $code_name = 'petition_normal';
        $setting_report = $this->document->setting_report($code_name);
        if($switch_code == '1'){
            $this->db->select(array(
                't1.*',
                't2.*',
                't3.prename_short',
                't4.district_name',
                't5.amphur_name',
                't6.province_name',
                't7.mem_group_name',
                't8.loan_reason'
            ));
            $this->db->from('coop_loan as t1');
            $this->db->join("coop_mem_apply as t2","t2.member_id = t1.member_id","inner");
            $this->db->join("coop_prename as t3","t3.prename_id = t2.prename_id","left");
            $this->db->join("coop_district as t4","t2.c_district_id = t4.district_id","left");
            $this->db->join("coop_amphur as t5","t2.c_amphur_id = t5.amphur_id","left");
            $this->db->join("coop_province as t6","t2.c_province_id = t6.province_id","left");
            $this->db->join("coop_mem_group as t7","t2.level = t7.id","left");
            $this->db->join("coop_loan_reason as t8","t1.loan_reason = t8.loan_reason_id","left");
            $this->db->where("t1.id = '".$loan_id."'");
            $row = $this->db->get()->result_array();
            $arr_data['data'] = $row[0];

            $this->db->select(array('principal_payment','total_paid_per_month'));
            $this->db->from('coop_loan_period');
            $this->db->where("loan_id = '".$loan_id."' AND period_count = '1'");
            $row = $this->db->get()->result_array();
            $arr_data['data_period_1'] = $row[0];

            $this->load->view('loan/petition_normal_pdf',$arr_data);
        }else if($setting_report == '2'){
            $data 	 = $this->document->fscoop_full($loan_id);
            $loan_period 	 = $this->document->loan_period($loan_id);
            $share_group 	 = $this->document->share_group($loan_id);
            $month_arr 		 = $this->center_function->month_arr();
            $month_short_arr = $this->center_function->month_short_arr();
            $profile_location= $this->document->profile_location();

            if(!empty($data['member_id'])) {
                $loan_cost_code = $this->document->loan_cost($loan_id, $data['member_id']); //รายจ่ายต่อเดือน
                $contract = $this->document->contract_current($loan_id, $data['member_id'], $data['createdatetime']); //เงินกู้เดิม
            }
            $deposit	= $this->document->deposit($data['member_id']); //เงินฝาก
            $coop_signature	= $this->document->coop_signature($data['createdatetime']); //เงินฝาก

            $arr_data['data'] 	         = $data;
            $arr_data['share_group']	 = $share_group;
            $arr_data['month_arr'] 		 = $month_arr;
            $arr_data['month_short_arr'] = $month_short_arr;
            $arr_data['profile_location']= $profile_location;
            $arr_data['contract']        = $contract;
            $arr_data['loan_cost_code']  = $loan_cost_code;
            $arr_data['deposit']         = $deposit;
            $arr_data['coop_signature']  = $coop_signature;

            $this->load->view('documents/petition_normal_pdf',$arr_data);
        }else if($setting_report == '3'){
            //สำนักงบประมาณ
			$data 	 = $this->document->fscoop_full($loan_id);
            $loan_period_first 	 = $this->document->loan_period_first($loan_id);

            if(!empty($data['member_id'])) {
                $contract = $this->document->contract_current($loan_id, $data['member_id'], $data['createdatetime']); //เงินกู้เดิม
            }
			
			array_multisort( array_column($contract['data'], "loan_type_code"), SORT_ASC,$contract['data']);

			$arr_old_loan = array();
			$j=0;
			$loan_type_code = '';
			if(!empty($contract['data'])){
				foreach($contract['data'] AS $key_c=>$val_c){
					$arr_old_loan[$val_c['loan_type_code']][$j] = $val_c;
					if($loan_type_code != $val_c['loan_type_code']){
						$j++;
						$loan_type_code = $val_c['loan_type_code'];											
					}else{							
						$j=0;
					}
				}
			}
			//echo '<pre>'; print_r($arr_old_loan); echo '</pre>'; exit;
            $arr_data['data'] 	         = $data;
            $arr_data['loan_guarantee']  = $data['coop_loan_guarantee'];
            $arr_data['data_old_loan']  = $arr_old_loan;
          
			$arr_data['full_name']= $arr_data['data']['prename_short'].$arr_data['data']['firstname_th']." ".$arr_data['data']['lastname_th'];
			$arr_data['position']= $arr_data['data']['position_name'];
			$arr_data['member_id']= $arr_data['data']['member_id'];
			$arr_data['phone_number']= $arr_data['data']['mobile'];
			$arr_data['contract_number']= $arr_data['data']['contract_number'];

			$arr_data['c_address_no'] = (@$arr_data['data']['c_address_no'] != '')?$arr_data['data']['c_address_no']:'';
			$arr_data['c_address_moo'] = (@$arr_data['data']['c_address_moo'] != '')?' หมู่ที่ '.$arr_data['data']['c_address_moo']:'';
			$arr_data['c_address_village'] = (@$arr_data['data']['c_address_village'] != '')?' หมู่บ้าน '.$arr_data['data']['c_address_village']:'';
			$arr_data['c_address_soi'] = (@$arr_data['data']['c_address_soi'] != '')?' ซ.'.$arr_data['data']['c_address_soi']:'';
			$arr_data['c_address_road'] = (@$arr_data['data']['c_address_road'] != '')?' '.$arr_data['data']['c_address_road']:'';
			$arr_data['c_district_name'] = (@$arr_data['data']['c_district_name'] != '')?(trim($arr_data['data']['c_province_name']) == $chk_bankkok)?' '.$arr_data['data']['c_district_name']:' '.trim($arr_data['data']['c_district_name']):'';
			$arr_data['c_amphur_name'] = (@$arr_data['data']['c_amphur_name'] != '')?(trim($arr_data['data']['c_province_name']) == $chk_bankkok)?' '.$arr_data['data']['c_amphur_name']:'  '.trim($arr_data['data']['c_amphur_name']):'';
			$arr_data['c_province_name'] = (@$arr_data['data']['c_province_name'] != '')?(trim($arr_data['data']['c_province_name']) == $chk_bankkok)?' '.$arr_data['data']['c_province_name']:'  '.trim($arr_data['data']['province_name']):'';
			$arr_data['c_zipcode'] = (@$arr_data['data']['c_zipcode'] != '')?$arr_data['data']['c_zipcode']:'';
		
			$arr_data['date_start_period'] = $arr_data['data']['date_start_period'];			
			//ยอดผ่อนต่อเดือน
			$arr_data['paid_per_month'] = ($arr_data['data']['pay_type']==1)?$loan_period_first['principal_payment']:$loan_period_first['total_paid_per_month'];
			
			$arr_data['level'] = @$arr_data['data']['mem_group_name_level'];   				                                    //สังกัด/โรงเรียน
			$arr_data['faction'] = @$arr_data['data']['mem_group_name_faction']; 
			$arr_data['member_income'] = @$arr_data['data']['salary']+@$arr_data['data']['other_income'];	
			$arr_data['write_at'] = 'สำนักงบประมาณ';
		
			$pdf = new FPDI();
			$arr_data['pdf'] = $pdf;
            $this->load->view('documents/bb_coop/petition_normal',$arr_data);
        }
    }
	public function variable_value($loan_id)
	{
		$arr_data = array();
		$this->load->model('Document', 'document');
		$this->load->library('Center_function');

		$result 		= $this->document->fscoop_full($loan_id);
		$profile_location 	= $this->document->profile_location($loan_id);
		$loan_period 	= $this->document->loan_period($loan_id);
		$share_group 	= $this->document->share_group($loan_id);
		$month_arr 		= $this->center_function->month_arr();
		$month_short_arr= $this->center_function->month_short_arr();

		$arr_data['data'] = $result;
		$arr_data['profile_location'] = $profile_location;
		$arr_data['loan_period'] = $loan_period;	
		$arr_data['share_group'] = $share_group;
		$arr_data['month_arr'] = $month_arr;
		$arr_data['month_short_arr'] = $month_short_arr;
		// echo '<pre>';print_r($arr_data);exit;
		$this->load->view('documents/variable_value',$arr_data);
		
	}
	
	//หนังสือกู้เงินสามัญ
	function book_normal_pdf($loan_id){
        $arr_data = array();

        $this->load->model('Document', 'document');
        $code_name = 'petition_normal';
        $setting_report = $this->document->setting_report($code_name);
        if($setting_report == '3'){
            //สำนักงบประมาณ
			$data 	 = $this->document->fscoop_full($loan_id);
            $loan_period_first 	 = $this->document->loan_period_first($loan_id);
            $loan_period_last 	 = $this->document->loan_period_last($loan_id);
			$share_group 	 = $this->document->share_group($loan_id);
			//echo '<pre>'; print_r($share_group); echo '</pre>'; exit;
            if(!empty($data['member_id'])) {
                $contract = $this->document->contract_current($loan_id, $data['member_id'], $data['createdatetime']); //เงินกู้เดิม
            }
			
			$arr_old_loan = array();
			$j=0;
			if(!empty($contract['data'])){
				foreach($contract['data'] AS $key_c=>$val_c){					
					$arr_old_loan[$val_c['loan_type_code']][$j] = $val_c;
					$j++;
				}
			}
			
            $arr_data['data'] 	         = $data;
            $arr_data['loan_guarantee']  = $data['coop_loan_guarantee'];
            $arr_data['data_old_loan']  = $arr_old_loan;
            $arr_data['data_share']  = $share_group;
          
			$arr_data['full_name']= $arr_data['data']['prename_short'].$arr_data['data']['firstname_th']." ".$arr_data['data']['lastname_th'];
			$arr_data['position']= $arr_data['data']['position_name'];
			$arr_data['member_id']= $arr_data['data']['member_id'];
			$arr_data['phone_number']= $arr_data['data']['mobile'];
			$arr_data['contract_number']= $arr_data['data']['contract_number'];

			$arr_data['c_address_no'] = (@$arr_data['data']['c_address_no'] != '')?$arr_data['data']['c_address_no']:'';
			$arr_data['c_address_moo'] = (@$arr_data['data']['c_address_moo'] != '')?' หมู่ที่ '.$arr_data['data']['c_address_moo']:'';
			$arr_data['c_address_village'] = (@$arr_data['data']['c_address_village'] != '')?' หมู่บ้าน '.$arr_data['data']['c_address_village']:'';
			$arr_data['c_address_soi'] = (@$arr_data['data']['c_address_soi'] != '')?' ซ.'.$arr_data['data']['c_address_soi']:'';
			$arr_data['c_address_road'] = (@$arr_data['data']['c_address_road'] != '')?' '.$arr_data['data']['c_address_road']:'';
			$arr_data['c_district_name'] = (@$arr_data['data']['c_district_name'] != '')?(trim($arr_data['data']['c_province_name']) == $chk_bankkok)?' '.$arr_data['data']['c_district_name']:' '.trim($arr_data['data']['c_district_name']):'';
			$arr_data['c_amphur_name'] = (@$arr_data['data']['c_amphur_name'] != '')?(trim($arr_data['data']['c_province_name']) == $chk_bankkok)?' '.$arr_data['data']['c_amphur_name']:'  '.trim($arr_data['data']['c_amphur_name']):'';
			$arr_data['c_province_name'] = (@$arr_data['data']['c_province_name'] != '')?(trim($arr_data['data']['c_province_name']) == $chk_bankkok)?' '.$arr_data['data']['c_province_name']:'  '.trim($arr_data['data']['province_name']):'';
			$arr_data['c_zipcode'] = (@$arr_data['data']['c_zipcode'] != '')?$arr_data['data']['c_zipcode']:'';
		
			$arr_data['date_start_period'] = $arr_data['data']['date_start_period'];			
			//ยอดผ่อนต่อเดือน
			$arr_data['paid_per_month'] = ($arr_data['data']['pay_type']==1)?$loan_period_first['principal_payment']:$loan_period_first['total_paid_per_month'];
			//ยอดผ่อนต่อเดือนงวดสุดท้าย
			$arr_data['paid_per_month_last'] = ($arr_data['data']['pay_type']==1)?$loan_period_last['principal_payment']:$loan_period_last['total_paid_per_month'];
			
			$arr_data['level'] = @$arr_data['data']['mem_group_name_level'];   				                                    //สังกัด/โรงเรียน
			$arr_data['faction'] = @$arr_data['data']['mem_group_name_faction']; 
			$arr_data['member_income'] = @$arr_data['data']['salary']+@$arr_data['data']['other_income'];	
			$arr_data['write_at'] = 'สำนักงบประมาณ';
		
			$pdf = new FPDI();
			$arr_data['pdf'] = $pdf;
            $this->load->view('documents/bb_coop/book_normal',$arr_data);
        }
    }
	
	//หนังสือกู้เงินฉุกเฉิน
	function book_emergent_pdf($loan_id){
        $arr_data = array();

        $this->load->model('Document', 'document');
        $code_name = 'petition_normal';
        $setting_report = $this->document->setting_report($code_name);
        if($setting_report == '3'){
            //สำนักงบประมาณ
			$data 	 = $this->document->fscoop_full($loan_id);
            $loan_period_first 	 = $this->document->loan_period_first($loan_id);
            $loan_period_last 	 = $this->document->loan_period_last($loan_id);
			$share_group 	 = $this->document->share_group($loan_id);
			//echo '<pre>'; print_r($share_group); echo '</pre>'; exit;
            if(!empty($data['member_id'])) {
                $contract = $this->document->contract_current($loan_id, $data['member_id'], $data['createdatetime']); //เงินกู้เดิม
            }
			
			$arr_old_loan = array();
			$j=0;
			if(!empty($contract['data'])){
				foreach($contract['data'] AS $key_c=>$val_c){					
					$arr_old_loan[$val_c['loan_type_code']][$j] = $val_c;
					$j++;
				}
			}
			
            $arr_data['data'] 	         = $data;
            $arr_data['loan_guarantee']  = $data['coop_loan_guarantee'];
            $arr_data['data_old_loan']  = $arr_old_loan;
            $arr_data['data_share']  = $share_group;
          
			$arr_data['full_name']= $arr_data['data']['prename_short'].$arr_data['data']['firstname_th']." ".$arr_data['data']['lastname_th'];
			$arr_data['position']= $arr_data['data']['position_name'];
			$arr_data['member_id']= $arr_data['data']['member_id'];
			$arr_data['phone_number']= $arr_data['data']['mobile'];
			$arr_data['contract_number']= $arr_data['data']['contract_number'];

			$arr_data['c_address_no'] = (@$arr_data['data']['c_address_no'] != '')?$arr_data['data']['c_address_no']:'';
			$arr_data['c_address_moo'] = (@$arr_data['data']['c_address_moo'] != '')?' หมู่ที่ '.$arr_data['data']['c_address_moo']:'';
			$arr_data['c_address_village'] = (@$arr_data['data']['c_address_village'] != '')?' หมู่บ้าน '.$arr_data['data']['c_address_village']:'';
			$arr_data['c_address_soi'] = (@$arr_data['data']['c_address_soi'] != '')?' ซ.'.$arr_data['data']['c_address_soi']:'';
			$arr_data['c_address_road'] = (@$arr_data['data']['c_address_road'] != '')?' '.$arr_data['data']['c_address_road']:'';
			$arr_data['c_district_name'] = (@$arr_data['data']['c_district_name'] != '')?(trim($arr_data['data']['c_province_name']) == $chk_bankkok)?' '.$arr_data['data']['c_district_name']:' '.trim($arr_data['data']['c_district_name']):'';
			$arr_data['c_amphur_name'] = (@$arr_data['data']['c_amphur_name'] != '')?(trim($arr_data['data']['c_province_name']) == $chk_bankkok)?' '.$arr_data['data']['c_amphur_name']:'  '.trim($arr_data['data']['c_amphur_name']):'';
			$arr_data['c_province_name'] = (@$arr_data['data']['c_province_name'] != '')?(trim($arr_data['data']['c_province_name']) == $chk_bankkok)?' '.$arr_data['data']['c_province_name']:'  '.trim($arr_data['data']['province_name']):'';
			$arr_data['c_zipcode'] = (@$arr_data['data']['c_zipcode'] != '')?$arr_data['data']['c_zipcode']:'';
		
			$arr_data['date_start_period'] = $arr_data['data']['date_start_period'];			
			//ยอดผ่อนต่อเดือน
			$arr_data['paid_per_month'] = ($arr_data['data']['pay_type']==1)?$loan_period_first['principal_payment']:$loan_period_first['total_paid_per_month'];
			//ยอดผ่อนต่อเดือนงวดสุดท้าย
			$arr_data['paid_per_month_last'] = ($arr_data['data']['pay_type']==1)?$loan_period_last['principal_payment']:$loan_period_last['total_paid_per_month'];
			
			$arr_data['level'] = @$arr_data['data']['mem_group_name_level'];   				                                    //สังกัด/โรงเรียน
			$arr_data['faction'] = @$arr_data['data']['mem_group_name_faction']; 
			$arr_data['member_income'] = @$arr_data['data']['salary']+@$arr_data['data']['other_income'];	
			$arr_data['write_at'] = 'สำนักงบประมาณ';
		
			$pdf = new FPDI();
			$arr_data['pdf'] = $pdf;
            $this->load->view('documents/bb_coop/book_emergent',$arr_data);
        }
    }
}