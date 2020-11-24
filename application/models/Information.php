<?php



class Information extends CI_Model {

    var $_member = null;

    public function  member($member_id = ""){

        if($member_id == ""){
            return null;
        }
        $this->_member = $this->db->get_where('coop_mem_apply', "member_id='{$member_id}'")->row();
        return $this;
    }

    private function exceptionMember(){
        try{
            if($this->_member == null){
                throw new Exception("Must be call member() function");
            }
        }catch (Exception $e){
            show_error($e->getMessage(), $e->getCode(), $e->getMessage());
        }
    }

    public function getInfoArray(){
        return (array) $this->_member;
    }

    public function getInfo(){
        return $this->_member;
    }

    public function getRetire($max_age_retire = 60){

        if ($this->_member->birthday == "") {
            return null;
        }
        return date("Y-m-d", strtotime($this->_member->birthday)." + ".$max_age_retire." YEAR");
    }

    public function getFullName($type = 'short'){
        $prename = "";
        if($this->_member->prename_id != ""){
            $prename = $this->db->get_where('coop_prename', "prename_id='{$this->_member->prename_id}' ")->row_array()['prename_'.$type];
        }
        return $prename.$this->_member->firstname_th." ".$this->_member->lastname_th;
    }

    public function getGroup($type = ''){

        self::exceptionMember();

        if($type == ""){
            return null;
        }
        $res = null;
        if($type == 'level') {
            $res = $this->db->get_where('coop_mem_group', "id ='{$this->_member->level}' ")->row();
        }else if($type == 'faction'){
            $res = $this->db->get_where('coop_mem_group', "id ='{$this->_member->faction}' ")->row();
        }else if($type == 'depart'){
            $res = $this->db->get_where('coop_mem_group', "id ='{$this->_member->department}' ")->row();
        }else{
            $this->db->select("concat('t2.mem_group_name', ' ','t3.mem_group_name', ' ','t4.mem_group_name') mem_group_name");
            $this->db->from("coop_mem_group AS t2");
            $this->db->join("coop_mem_group AS t3","t1.faction = t3.id","left");
            $this->db->join("coop_mem_group AS t4","t1.level = t4.id","left");
            $this->db->where("t1.member_id = '".$this->_member->department ."'");
            $res = $this->db->get()->row();
        }

        //echo $this->db->last_query(); exit;
        return $res->mem_group_name;
    }

    public function getMemberType() {

        self::exceptionMember();
        $result = $this->db->get_where('coop_mem_type', array('mem_type_id' => $this->_member->mem_tyep_id))->row();
        return $result;

    }

    public function getPosition(){
        return 'ไม่ระบุ';
    }

    public function getStStatus(){
        self::exceptionMember();
        return $this->db->get_where("coop_mem_req_resign", array("member_id" => $this->_member->member_id, "req_resign_status" => 1))->result_array()[0]['approve_date'];
    }
    
    public function get_province() {
        return $this->db->select('*')->from('coop_province')->get()->result_array();
    }

    public function get_province_by_id($id) {
        return $this->db->select('*')->from('coop_province')->where("province_id = '".$id."'")->get()->row();
    }

    public function getIncoming(){
        //รายได้
        $sql = "SELECT
			*, 
			IFNULL((select coop_income_member.income_value from coop_income_member where coop_income_member.member_id = '".$this->_member->member_id."' and coop_income_member.income_id = coop_income.id), 0) as income_value
		FROM
			`coop_income`
		ORDER BY
			`seq` ASC";
        return $this->db->query($sql)->result_array();
    }

    public function saveInComing($data){


            $member_id 			= $data['member_id'];
            $income_id 			= $data['income_id'];
            $income_value 		= $data['income_value'];
            if($member_id!="" && $income_id!="" && $income_value!=""){
                $this->db->where("member_id", $member_id);
                $this->db->where("income_id", $income_id);
                $this->db->delete("coop_income_member");
                //insert
                $this->db->set("income_id", $income_id);
                $this->db->set("member_id", $member_id);
                $this->db->set("income_value", $income_value);
                $this->db->insert("coop_income_member");

            }

    }

}


