<?php
    class Validate {

        public $mysqli;

        public function __construct($mysqli) {
            $this->mysqli = $mysqli;
        }

        public function checkPID($pid) {
            if(strlen($pid) != 13) return false;
            for($i=0, $sum=0; $i<12;$i++) $sum += (int)($pid{$i})*(13-$i);
            if((11-($sum%11))%10 == (int)($pid{12})) return true;
            return false;
        }

        public function validate_card_id($data) {
            $request_id = $data["benefits_request_id"];
            $benefits_id = $data['benefits_id'];
            $card_id = $data["card_id"];
            $where_req_id = !empty($request_id) ? " AND t2.benefits_request_id != '".$request_id."'" : "";
            $sql = "SELECT benefits_request_id FROM coop_benefits_request AS tb1
            LEFT JOIN coop_benefits_request_detail AS tb2 ON tb2.benefits_request_id = tb1.benefits_request_id
            WHERE tb1.benefits_type_id = {$benefits_id}
            AND tb1.benefits_status not in (3,5,6) 
            AND t2.card_id = '{$card_id}'".$where_req_id;
            $rs = $this->mysqli->query($sql);
            return ( $rs->num_rows AND $card_id != 11 ) ? false : true;
        }

        public function request_time($data) {
            $member_id = $data['member_id'];
            $benefits_id = $data['benefits_id'];
            $card_id = $data["card_id"];
            $result = 1;
            if($data["request_time_unit"] == "per_person") {
                $sql = "SELECT benefits_request_id FROM coop_benefits_request WHERE member_id = '{$member_id}' AND benefits_type_id = '{$benefits_id}' AND benefits_status not in (3,5,6)";
                $rs = $this->mysqli->query($sql);
                $result = ( $rs->num_rows >= (int)$data["request_time"] ) ? 0 : 1;
            } else if ($data["request_time_unit"] == "per_year") {
                if ( $benefits_id == 11 ) {
                    $current_day = date('Y-m-d');
                    $current_month = date('m');
                    $sql = "SELECT scholarship_period_month_start, scholarship_period_date_start FROM coop_benefits_type_detail 
                    WHERE benefits_id = '{$benefits_id}' AND start_date <= '{$current_day}' ORDER BY start_date LIMIT 1";
                    $rs = $this->mysqli->query($sql);
                    $row = $rs->fetch_assoc();

                    $period_year_start = $row['scholarship_period_month_start'] <= $current_month ? date('Y') : date('Y') - 1;
                    // $period_start = date($period_year_start.'-'.$row['scholarship_period_month_start'].'-'.sprintf('%08d', $row['scholarship_period_date_start'].' 00:00:00'));
                    $period_start = date($period_year_start.'-'.$row['scholarship_period_month_start'].'-'.$row['scholarship_period_date_start'].' 00:00:00');

                    $sql = "SELECT tb1.benefits_request_id 
                    FROM coop_benefits_request AS tb1
                    INNER JOIN coop_benefits_request_detail AS tb2 ON tb2.benefits_request_id = tb1.benefits_request_id
                    WHERE tb1.member_id = '{$member_id}' AND tb1.benefits_type_id = '{$benefits_id}' AND tb1.benefits_status not in (3,5,6) AND tb1.createdatetime >= '{$period_start}' AND t2.card_id = '{$card_id}'";
                    $rs = $this->mysqli->query($sql);

                    $result = ( $rs->num_rows >= (int)$data["request_time"] ) ? 0 : 1;                                           
                } else {
                    $sql = "SELECT accm_month_ini FROM coop_account_period_setting ORDER BY accm_date_create desc LIMIT 1";
                    $rs = $this->mysqli->query($sql);
                    $row = $rs->fetch_assoc();

                    $process_timestamp = date('Y-m-d H:i:s');
                    $current_month = date('m');
                    
                    $period_year_start = $row['accm_month_ini'] <= $current_month ? date('Y') : date('Y') - 1;
					$period_start = date($period_year_start.'-'.$row['accm_month_ini'].'-01 00:00:00');

                    $sql = "SELECT benefits_request_id FROM coop_benefits_request 
                    WHERE member_id = '{$member_id}' 
                    AND benefits_type_id = '{$benefits_id}'
                    AND benefits_status not in (3,5,6) AND createdatetime >= '{$period_start}'";
                    $rs = $this->mysqli->query($sql);

					if ( $benefits_id == 3 AND $rs->num_rows >= (int)$data["request_time"] ) {
						$result = 0;
					} else if( $rs->num_rows >= (int)$data["request_time"] ) {
						$result = 0;
                    }                  
                }
            }

            return $result;
        }
        
        public function benefit_validate($data) {

            $check = 1; $member_id = $data['member_id'];

            if ( $data['benefits_id'] == '13' ) {
                $sql = "SELECT benefits_request_id FROM coop_benefits_request 
                WHERE member_id = '{$member_id}' AND benefits_type_id in (3,11) AND benefits_status not in (3,5,6)";
                $rs = $this->mysqli->query($sql);
                $check = ($rs->num_rows) ? 1 : 0;
            } 

            if ( !empty($data["card_id"]) ) {
                $check = ( $this->checkPID($data["card_id"]) ) ? $this->validate_card_id($data) : 0;
            } 
            
            if ( (int)$data["age_grester"] ) {
                $sql = "SELECT member_date FROM coop_mem_apply WHERE member_id = '{$member_id}'";
                $rs = $this->mysqli->query($sql);
                $row = $rs->fetch_assoc();
                $date1 = new DateTime($row['member_date']);
                $date2 = new DateTime(date("Y-m-d"));
                $interval = date_diff($date1, $date2);
                $member_age = $interval->y;
                $check = ( $member_age < (int)$data["age_grester"] ) ? 0 : 1;
            }

            if( (int)$data["member_age_grester"] ) {
                $sql = "SELECT member_date FROM coop_mem_apply WHERE member_id = '{$member_id}'";
                $rs = $this->mysqli->query($sql);
                $row = $rs->fetch_assoc();
                $date1 = new DateTime($row['member_date']);
                $date2 = new DateTime(date("Y-m-d"));
                $interval = date_diff($date1, $date2);
                $member_year = $interval->y;
                if($interval->m >= 6) {
                    $member_year += 1;
                }
                $check = ( $member_year < (int)$data["member_age_grester"] ) ? 0 : 1;
            }

            if( (int)$data["request_time"] ) {
                $check = $this->request_time($data);
            }

            return $check;
        }

    }
?>