<?php
require_once("config.inc.php");
require_once("token.validate.php");


$member_no = isset($_POST['member_no']) ? $mysqli->real_escape_string($_POST['member_no']) : null;
$platform = isset($_POST['platform'])? $mysqli->real_escape_string($_POST['platform']) : null;
$sql = "SELECT t1.*,t2.prename_short,t3.district_name,t4.amphur_name,t5.province_name,t6.relation_name,t7.user_name
from coop_mem_gain_detail as t1 
left join coop_prename AS t2 on t2.prename_id = t1.g_prename_id
left join coop_district AS t3  on t3.district_id = t1.g_district_id
left join coop_amphur AS t4 on t4.amphur_id = t1.g_amphur_id 
left join coop_province AS t5 on t5.province_id = t1.g_province_id
left join coop_mem_relation AS t6 on t6.relation_id = t1.g_relation_id 
left join coop_user AS t7 on t7.user_id = t1.admin_id 
 WHERE t1.member_id = '{$member_id}' ORDER BY t1.g_create ASC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        while( ($row = $rs->fetch_assoc()) ){
            $data['gain'][] = $row;
        }
    } else {
        $data['errGain'] = "ไม่มีข้อมูลผู้รับผลประโยชน์";
    }

    echo json_encode($data);
    exit();   

?>
