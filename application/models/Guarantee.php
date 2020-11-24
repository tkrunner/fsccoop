<?php


class Guarantee extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    private $_guarantee = null;

    public function current($member_id){

        $this->db->select(array(
            't2.id',
            't2.petition_number',
            't2.contract_number',
            't2.member_id',
            't3.firstname_th',
            't3.lastname_th',
            't2.loan_amount',
            't2.loan_amount_balance'
        ));
        $this->db->from('coop_loan_guarantee_person as t1');
        $this->db->join('coop_loan as t2','t1.loan_id = t2.id','inner');
        $this->db->join('coop_mem_apply as t3','t2.member_id = t3.member_id','inner');
        $this->db->where("t1.guarantee_person_id = '".$member_id."' AND t2.loan_status IN('1','2')");
        $this->_guarantee = $this->db->get()->result_array();

        return $this;
    }

    public function get(){

        return $this->_guarantee;
    }

    public function getBalance(){
        $result = 0;
        foreach ($this->_guarantee as $key => $item){
            $result += $item['loan_amount_balance'];
        }

        return $result;
    }

    public function itemCount(){
        return sizeof($this->_guarantee);
    }



}