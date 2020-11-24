<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');


class Loan_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();

        //set member_id
    }

    public function get_member($loan_id)
    {
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
        return $row[0];
    }
    public function get_data_period_1($loan_id)
    {
        $this->db->select(array('date_period','principal_payment','total_paid_per_month'));
        $this->db->from('coop_loan_period');
        $this->db->where("loan_id = '".$loan_id."' AND period_count = '1'");
        $row = $this->db->get()->result_array();
        return $row[0];
    }

    public function data_period_last($loan_id)
    {
        $this->db->select(array('date_period','principal_payment','total_paid_per_month'));
        $this->db->from('coop_loan_period');
        $this->db->where("loan_id = '".$loan_id."'");
        $this->db->order_by("id DESC");
        $this->db->limit(1);
        $row = $this->db->get()->result_array();
        return $row[0];
    }

}