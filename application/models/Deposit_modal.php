<?php


class Deposit_modal extends CI_Model
{

    public function __construct()
    {
        ini_set('precision', '16');
        parent::__construct();
    }

    private $_account = null;
    private $_isCall = false;

    public function current($member_id, $date = ''){
        $this->_isCall = true;
        $where = " 1=1 and mem_id = '{$member_id}' ";

        if($date){
            $date = date('Y-m-d');
            $where .= " AND close_account_date <= '{$date}' ";
            $where .= " AND account_status = 0 ";
        }

        $this->_account = $this->db->get_where('coop_maco_account', $where)->result_array();

        //echo $this->db->last_query(); exit;
        return $this;
    }

    public function getAccount(){
        self::exceptionAccount();
        return $this->_account;
    }

    private function exceptionAccount(){
        try{

            if($this->_account == null && !$this->_isCall) throw new Exception("Must be Call account() Function");

        }catch (Exception $e){
            show_error($e->getTraceAsString(), $e->getCode(), $e->getMessage());
        }
    }

    public function nonInterest(){
        $this->db->where_not_in('transaction_list' , array('INT'));
        return $this;
    }

    public function getBalance($account_id){
        $this->db->select('*');
        $this->db->from('coop_account_transaction');
        $this->db->where("account_id = '".$account_id."'");
        $this->db->order_by('transaction_time DESC, transaction_id DESC');
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    public function getBalanceAll(){
        self::exceptionAccount();
        $result = 0;
        foreach ($this->_account as $key => $account){
            $result += self::getBalance($account['account_id'])->transaction_balance;
        }
        return $result;

    }

    public function getBalanceList(){
        self::exceptionAccount();
        $result = [];
        $num = 0;
        foreach ($this->_account as $key => $acc){
            $result[$num] = $acc;
            $result[$num]['balance'] = self::nonInterest()->getBalance($acc['account_id'])->transaction_balance;
            $num++;
        }

        return $result;
    }

    public function getAccountCount(){
        return sizeof($this->_account);
    }

    public function getDeposit(){

        return $this;
    }

    /**
     * ยอดเงินคงเหลือในบัญชีที่ต้องใช้ในการประเมิณ
     * @param $member_id
     * @return mixed
     */
    public function guaranteeAccount($member_id){
        $acc_type = '10'; //Todo เพิ่มตารางตั้งค่าการบัญชีหลักเกณการกู้
        $act = $this->db->get_where('coop_maco_account',
            "mem_id='{$member_id}' and account_id like '{$acc_type}%' and account_status=0", 1)->row();

        return self::nonInterest()->getBalance($act->account_id)->transaction_balance;

    }
	
	/*	จำนวนวันในปีที่ใช้ในการคำนวณ
		1=365
		2=366
		3=depend of calendar
	*/
	function get_day_of_year_by_type($type, $date) {
		if($type == 2) {
			return 366;
		} else if ($type == 3) {
			return date("z", strtotime(substr($date, 0, 4)."-12-31")) + 1;
		} else {
			return 365;
		}
	}
	
	function check_interest_step_year($date_start,$date_end){
		$arr_data = array();
		$src_year = date("z", strtotime(substr($date_start, 0, 4)."-12-31")) + 1;
		$des_year = date("z", strtotime(substr($date_end, 0, 4)."-12-31")) + 1;

		$_date_start = date("Y-m-d", strtotime($date_start));
		if($date_start <= $date_end){
			if($src_year == $des_year){
				$arr_data[0]['date_start'] = $date_start;
				$arr_data[0]['date_end'] = $date_end;			
			}else{
				$date_middle = date("Y", strtotime($date_end)).'-01-01';

				$arr_data[0]['date_start'] = $date_start;
				$arr_data[0]['date_end'] = $date_middle;
				
				$arr_data[1]['date_start'] = $date_middle;
				$arr_data[1]['date_end'] = $date_end;
			}
		}
		return $arr_data;
	}	
	
	function check_interest_step($date_start,$date_end, $type_id){
		$this->db->select(array(
			't1.type_detail_id',
			't1.type_id',
			't2.num_month',
			't1.percent_depositor',
			't2.percent_interest as interest_rate',					
			'DATE(t1.start_date) AS start_date'
		));
		$this->db->from('coop_deposit_type_setting_detail as t1');
		$this->db->join('coop_deposit_type_setting_interest as t2','t1.type_detail_id = t2.type_detail_id AND t1.condition_interest = t2.condition_interest','inner');
		$this->db->where("t1.type_id = '{$type_id}'");
		$this->db->order_by("start_date ASC");
		$row_interest_rate = $this->db->get()->result_array();
		
		$arr_interest_rate = array();
		$i=0;
		foreach($row_interest_rate as $key2 => $value2){
			//$start_date_interest = $value2['start_date'];
			$start_date_interest = ($date_start <= $value2['start_date'])?$value2['start_date']:$date_start;
			//echo @$row_interest_rate[($key2+1)]['start_date'].'<br>';
			//if(@$row_interest_rate[($key2+1)]['start_date'] != ''){
			if(@$row_interest_rate[($key2+1)]['start_date'] != '' && $date_end >= @$row_interest_rate[($key2+1)]['start_date']){
				$end_date_interest = date('Y-m-d',strtotime($row_interest_rate[($key2+1)]['start_date']));
			}else{
				$end_date_interest = $date_end;
			}
	
			if(strtotime($date_start) >= strtotime($start_date_interest) && strtotime($date_start) <= strtotime($end_date_interest) || strtotime($date_end) <= strtotime($end_date_interest)){	
				//echo $start_date_interest.','.$end_date_interest.'<br>';
				$arr_new = $this->check_interest_step_year($start_date_interest,$end_date_interest);
				//echo '<pre>'; print_r($arr_new); echo '</pre>';
				if(!empty($arr_new)){
					foreach($arr_new AS $val_new){						
						$arr_interest_rate[$i] = $value2;
						$arr_interest_rate[$i]['start_date'] = $val_new['date_start'];
						$arr_interest_rate[$i]['end_date'] = $val_new['date_end'];
						$i++;
					}					
				}
			}
		}
		return $arr_interest_rate;
	}	
	
	public function cal_accu_int($data){
		$account_id = $data['account_id'];
		$date_end = $data['date_cal'];

		$data_last = $this->db->select("DATE(t1.transaction_time) AS transaction_time,
													t1.old_acc_int,
													t1.accu_int_item,
													t1.transaction_balance,
													t2.type_id,
													t2.account_status")
										->from("coop_account_transaction  AS t1")
										->join("coop_maco_account AS t2","t1.account_id = t2.account_id	","inner")
										->where("t1.account_id = '{$account_id}'  AND t2.account_status = '0'")
										->order_by('t1.transaction_time DESC,t1.transaction_id DESC ')
										->limit(1)
										->get()->row_array();
		if(!empty($data_last)){								
			$last_old_acc_int = $data_last['old_acc_int'];
			$transaction_balance = $data_last['transaction_balance'];
			$date_start = $data_last['transaction_time'];		
			$type_id = $data_last['type_id'];

			$arr_interest_step = $this->check_interest_step($date_start, $date_end, $type_id);
			$arr_data = array();
			$accu_int_item = 0;
			foreach($arr_interest_step AS $key=>$val){
				$days_of_year = $this->get_day_of_year_by_type($type_id,$val['start_date']);			
				$diff = date_diff(date_create($val['start_date']),date_create($val['end_date']));
				$date_count = $diff->format("%a");			
				$interest_rate = $val['interest_rate'];		
				//echo 'ROUND(((('.$transaction_balance.'*'.$interest_rate.')/100)/'.$days_of_year.')*'.$date_count.',2)<br>';
				$accu_int_item_rate = ROUND(((($transaction_balance*$interest_rate)/100)/$days_of_year)*$date_count,2);
				$accu_int_item += $accu_int_item_rate;
				
				$arr_data['cal'][$key]['start_date'] = $val['start_date'];
				$arr_data['cal'][$key]['end_date'] = $val['end_date'];
				$arr_data['cal'][$key]['interest_rate'] = $interest_rate;
				$arr_data['cal'][$key]['days_of_year'] = $days_of_year;
				$arr_data['cal'][$key]['date_count'] = $date_count;
				$arr_data['cal'][$key]['transaction_balance'] = $transaction_balance;
				$arr_data['cal'][$key]['accu_int_item'] = $accu_int_item_rate;
				$arr_data['cal'][$key]['formula'] = 'ROUND(((('.$transaction_balance.'*'.$interest_rate.')/100)/'.$days_of_year.')*'.$date_count.',2)';
			}
			
			$old_acc_int = $last_old_acc_int+$accu_int_item;
			
			$arr_data['account_id'] = $account_id;
			$arr_data['accu_int_item'] = $accu_int_item;
			$arr_data['old_acc_int'] = $old_acc_int;
			$arr_data['date_start'] = $date_start;
			$arr_data['date_end'] = $date_end;
		}else{
			$arr_data = array();
		}
		//echo '<pre>'; print_r($arr_data); echo '</pre>';
		return $arr_data;
		exit;
    }


}