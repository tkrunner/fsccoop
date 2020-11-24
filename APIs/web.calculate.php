<?php
	
    require_once("config.inc.php");
    require_once("token.validate.php");
	
	function cal_age($birthday,$type = 'y'){     //รูปแบบการเก็บค่าข้อมูลวันเกิด
		if($birthday=="" || $birthday=="0000-00-00")
			return "n/a";
		$birthday = date("Y-m-d",strtotime($birthday)); 
		$today = date("Y-m-d");   //จุดต้องเปลี่ยน
		list($byear, $bmonth, $bday)= explode("-",$birthday);       //จุดต้องเปลี่ยน
		list($tyear, $tmonth, $tday)= explode("-",$today);                //จุดต้องเปลี่ยน
		$mbirthday = mktime(0, 0, 0, $bmonth, $bday, $byear);
		$mnow = mktime(0, 0, 0, $tmonth, $tday, $tyear );
		$mage = ($mnow - $mbirthday);
		//echo "วันเกิด $birthday"."<br>\n";
		//echo "วันที่ปัจจุบัน $today"."<br>\n";
		//echo "รับค่า $mage"."<br>\n";
		$u_y=date("Y", $mage)-1970;
		$u_m=date("m",$mage)-1;
		$u_d=date("d",$mage)-1;
		if($type=='y'){
			return $u_y;
		}else if($type=='m'){
			return $u_m;
		}else{
			return $u_d;
		}
	}
	
    $data = [ 'status' => 1, 'responseText' => '' , 'data' => []];
	
	
	/*
		$this->db->select(array('t1.*',
							't2.mem_group_name AS department_name',
							't3.mem_group_name AS faction_name',
							't4.mem_group_name AS level_name',
							't5.prename_short'));
			$this->db->from('coop_mem_apply as t1');			
			$this->db->join("coop_mem_group AS t2","t1.department = t2.id","left");
			$this->db->join("coop_mem_group AS t3","t1.faction = t3.id","left");
			$this->db->join("coop_mem_group AS t4","t1.level = t4.id","left");
			$this->db->join("coop_prename AS t5","t1.prename_id = t5.prename_id","left");
			$this->db->where("t1.member_id = '".$member_id."'");
			$rs = $this->db->get()->result_array();
	*/
	
	$member_id = $mysqli->real_escape_string($member_id); 
	$sql = "SELECT t1.* FROM  coop_mem_apply as t1 WHERE member_id like '{$member_id}' LIMIT 0 , 1 " ;
	$rs = $mysqli->query($sql);
	$row = $rs->fetch_assoc(); 
	
	$y = cal_age($row['apply_date'])  ; 
	$m = cal_age($row['apply_date'] , 'm')  ; 
	$memdtm_age = ($y * 12) + m ; 
	// CAL AGE 
	
	
	$salary_cal = ($row["salary"] + $row["other_income"]) * 100 ;
	if($memdtm_age 			>= ( 12 * 12)) 	$loan_limit	=	$salary_cal >= 3000000 ? 3000000 : $salary_cal ;//$loan_limit = 3000000;
	elseif($memdtm_age	>= (  9 * 12))	$loan_limit	=	$salary_cal >= 2500000 ? 2500000 : $salary_cal ;//$loan_limit = 2500000;
	elseif($memdtm_age 	>= (  6 * 12))	$loan_limit	=	$salary_cal >= 2000000 ? 2000000 : $salary_cal ;//$loan_limit = 2000000;
	elseif($memdtm_age	>= (  4 * 12))	$loan_limit = 1500000;
	elseif($memdtm_age 	>= (  3 * 12))	$loan_limit = 1000000;
	elseif($memdtm_age 	>= (  2 * 12))	$loan_limit =  500000;
	elseif($memdtm_age 	>= (  1 * 12))	$loan_limit =  500000;
	elseif($memdtm_age 	>= 6) $loan_limit =  500000;
	else $loan_limit = -1;
	
	// $is_pension = strpos($row["membtype_desc"], "บำนาญ") !== false ? 0.02 : 0.03;
	$is_pension = 0.2 ; 
	$period = $loan_limit >= 1500000 ? 20 : 15 ;
	
	$tmp = [] ; 
	
	$tmp["is_pension"] = $is_pension ; 
	$tmp['memdtm_age'] = $memdtm_age  ; 
	$tmp["loan_require"] = (double) $loan_limit ;
  	$tmp["period"] = (int) $period ;
	$tmp["incomeetc_amount"] = (double) $row["other_income"] ; 
	$tmp["salary_amount"] = (double) $row["salary"] ; 
	$tmp["member_id"] = $member_id ; 
	
	$data["data"] = $tmp ; 
    echo json_encode($data); 
    exit();
	