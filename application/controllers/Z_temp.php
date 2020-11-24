<?php

class Z_temp extends CI_Controller{
    public function __construct(){
        parent::__construct();
    }

    public function adjust_loan_transaction_text_in_receipt(){
        $limit = 1000;
        $transactions = $this->db->select("t1.finance_transaction_id, t2.contract_number, t3.loan_name")
                                ->from("coop_finance_transaction as t1")
                                ->join("coop_loan as t2", "t1.loan_id = t2.id", "inner")
                                ->join("coop_loan_name as t3", "t2.loan_type = t3.loan_name_id")
                                ->where("t1.transaction_text LIKE '%เงินกู้%'")
                                ->get()->result_array();
        $index = 1;
        foreach($transactions as $transaction) {
            $transaction_text = $transaction['loan_name']." ".$transaction['contract_number'];
            // echo $transaction_text; exit;
            $data_insert = array();
			$data_insert['transaction_text'] = $transaction_text;
			$this->db->where('finance_transaction_id', $transaction['finance_transaction_id']);
            $this->db->update('coop_finance_transaction', $data_insert);
            echo $transaction['finance_transaction_id']."<br>";
            if($index == $limit) {
                break;
            }
            $index++;
        }
        exit;
    }

}
