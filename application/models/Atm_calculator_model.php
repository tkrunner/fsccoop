<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Atm_calculator_model extends CI_Model {

    private $_setting = null;
    private $_item_type = null;
    private $_calc = null;

    public function __construct()
    {
        parent::__construct();
        self::init();
    }

    public function init(){
        self::setting();
    }

    public function setting(){
        $this->_setting = $this->db->get('coop_atm_type_setting')->result_array();
        $num = 0;
        foreach ($this->_setting as $key => $setting){
            $this->_item_type[$setting['atm_type_code']] = $setting['atm_sign_mode'];
            $num++;
        }
    }

    public function calc($type_code, $entry_data = array()){

        if( $this->_item_type[$type_code] == "-1"){
            return self::calcPayment($type_code, $entry_data);
        }else if($this->_item_type[$type_code] == "1"){
            return self::calcEntry($type_code, $entry_data);
        }else {
            return self::calcNoop($type_code, $entry_data);
        }
    }

    private function calcPayment($type_code, $entry_data = array()){

        $last_stm = self::getStmBeforePayment($entry_data['loan_atm_id']);
        $start =  date('Y-m-d', strtotime($last_stm['transaction_datetime']));
        $end = date('Y-m-d', strtotime($entry_data['entry_date']));
        $cal_atm_interest = array();
        $cal_atm_interest['loan_atm_id'] = $entry_data['loan_atm_id'];
        $cal_atm_interest['date_interesting'] = $end;
        $interest = $this->loan_libraries->calc_loan_atm_interest_multi_rate($last_stm['loan_amount_balance'], $start, $end);
        $int_arrears_bal = $last_stm['interest_arrear_bal']+$interest;


        $result = array();
        $result['loan_type_code'] = $type_code;
        $result['interest_from'] = $start;
        $result['interest_to'] = $end;
        $result['interest_arrears'] = round($last_stm['interest_arrear_bal'], 2);
        $result['interest_calculate_arrears'] = round($interest, 2);
        $result['interest_arrear_bal'] = round(abs($entry_data['interest'] - $int_arrears_bal), 2);
        $result['interest_notpay'] = round($entry_data['interest']-$int_arrears_bal, 2);
        return $result;
    }

    private function calcEntry( $type_code, $entry_data = array()){

        $last_stm = self::getStmBeforePayment($entry_data['loan_atm_id']);
        $start =  date('Y-m-d', strtotime($last_stm['transaction_datetime']));
        $end = date('Y-m-d', strtotime($entry_data['entry_date']));
        $interest = $this->loan_libraries->calc_loan_atm_interest_multi_rate($last_stm['loan_amount_balance'], $start, $end);
        $int_arrears_bal = $last_stm['interest_arrear_bal']+$interest;

        $result = array();
        $result['loan_type_code'] = $type_code;
        $result['interest_from'] = $start;
        $result['interest_to'] = $end;
        $result['interest_arrears'] = round($last_stm['interest_arrear_bal'], 2);
        $result['interest_calculate_arrears'] = round($interest, 2);
        $result['interest_arrear_bal'] = round($int_arrears_bal, 2);
        $result['interest_notpay'] = round($entry_data['interest']-$int_arrears_bal, 2);
        return $result;
    }

    private function calcNoop($type_code, $entry_data = array()){

        $last_stm = self::getStmBeforePayment($entry_data['loan_atm_id']);
        if(sizeof($last_stm)){
            $result['interest_arrear_bal'] = $last_stm['interest_arrear_bal'];
        }else{
            $result['interest_arrear_bal'] = "";
        }

        $result = array();
        $result['loan_type_code'] = $type_code;
        $result['interest_from'] = "";
        $result['interest_to'] = "";
        $result['interest_arrears'] = "";
        $result['interest_calculate_arrears'] = "";
        $result['interest_notpay'] = "";
        return $result;
    }

    private function calcSpecial($type_code, $entry_data = array()){
        return array();
    }

    public function getStmBeforePayment($loan_id){
        $this->db->order_by('loan_atm_transaction_id', 'desc');
        $this->db->where(array('loan_atm_id' => $loan_id));
        return $this->db->get('coop_loan_atm_transaction', 1)->row_array();
    }

    public function getStmPreventDate($loan_id, $entry_date){
        $this->db->order_by('loan_atm_transaction_id', 'desc');
        $this->db->where(array('loan_atm_id' => $loan_id, 'transaction_datetime <=' => $entry_date ));
        return $this->db->get('coop_loan_atm_transaction', 1)->row_array();
    }

    public function  getStmScopeTarget($loan_id, $prev_date){
        $this->db->order_by('loan_atm_transaction_id', 'desc');
        $this->db->where(array('loan_atm_id' => $loan_id, 'transaction_datetime >=' => $prev_date ));
        return $this->db->get('coop_loan_atm_transaction', 1)->row_array();
    }


}