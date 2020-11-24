<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Script_loan_atm_rebalance extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function atm_detail_detail(){

        if(@$_GET['loan_atm_id'] != ''){
            if($_GET['loan_atm_id'] != 'all') {
                $this->db->where(array('loan_atm_id' => $_GET['loan_atm_id']));
            }
        }else{
            echo 'please enter loan_atm_id : ';
            exit;
        }

        //$this->db->where(array('loan_atm_status' => 0));
        $res =$this->db->get('coop_loan_atm')->result_array();

        //echo $this->db->last_query(); exit;

        foreach($res as $key => $val) {

            $last_trans = $this->atm_transaction_by_id($val['loan_atm_id']);

            $detail = $this->atm_detail_by_id($val['loan_atm_id']);

            if(sizeof($detail) == 0){
                continue;
            }

            $loan_balance = round($last_trans['loan_amount_balance'], 2);
            if($_GET['dev'] == 'data') {
                echo "balance : " . number_format($loan_balance, 2)."\n";
            }




            //$counter = sizeof($detail);
            foreach ($detail as $index => $item) {
                //--$counter;
                if ($loan_balance > 0) {
                    if ($loan_balance > $item['loan_amount'] && $item['loan_amount'] !=  0) {
                        $detail[$index]['loan_amount_balance'] =  round($loan_balance, 2);
                        $loan_balance = 0;
                    }

                    $loan_balance = round($loan_balance - $item['loan_amount_balance'], 2);
                    $detail[$index]['loan_status'] = 0;
                } else {
                    $detail[$index]['loan_status'] = 1;
                    $detail[$index]['loan_amount_balance'] = 0;
                }
            }

            $this->db->update_batch('coop_loan_atm_detail', $detail, 'loan_id');
            if($_GET['dev'] == 'data') {

                echo "<pre>";
                foreach ($detail as $x => $y){
                    echo implode(" ,", array_map(function($k, $v){
                        return sprintf("'%s'=>'%s'", $k, $v);
                    }, array_keys($y), $y))."\n";
                }
                echo "</pre>";
                exit;
            }
                $affected = $this->db->affected_rows();
            if($affected) {
                echo "<pre>";
                echo "loan_atm_id {$val['loan_atm_id']} success affected rows : ".number_format($affected);
                echo "\n";
                echo "</pre>";
                //exit;
            }
        }

    }

    public function loan_atm_balance(){
        ini_set("precision", 12);
        $this->db->where(array('loan_atm_status' => 1, 'activate_status' => 0));
        $res =$this->db->get('coop_loan_atm')->result_array();
        $num = 0;
        $data = [];
        foreach($res as $key => $val) {
            $last_trans = $this->atm_transaction_by_id($val['loan_atm_id']);
            $loan_balance = round($last_trans['loan_amount_balance'], 2);
            $atm_loan_balance =  round($val['total_amount_approve'] - $loan_balance, 2);
            //if($last_trans['loan_amount_balance'] == 0 && self::atm_account_lasted($res['member_id']) != $val['loan_atm_id']){
            //    $data[$num]['loan_atm_status'] = 4;
            //}
            $data[$num]['total_amount_balance'] = $atm_loan_balance;
            $data[$num]['loan_atm_id'] = $val['loan_atm_id'];
            $num++;
        }
        $this->db->update_batch('coop_loan_atm', $data, 'loan_atm_id');
    }

    private function atm_detail_by_id($id){
        $sql = "SELECT * FROM coop_loan_atm_detail WHERE loan_atm_id = ? ORDER BY loan_date DESC, loan_id DESC;";
        return $this->db->query($sql, $id)->result_array();
    }

    private function atm_transaction_by_id($id){
        $sql = "SELECT * FROM coop_loan_atm_transaction WHERE loan_atm_id = ? ORDER BY transaction_datetime DESC LIMIT 1;";
        return $this->db->query($sql, $id)->row_array();
    }

    private function atm_account_lasted($member_id = ''){

        return $this->db->select('loan_atm_id')->order_by('loan_atm_id', 'desc')->get_where('coop_loan_atm', array(
            'member_id' => $member_id
        ), 1)->row()->loan_atm_id;
    }


}
