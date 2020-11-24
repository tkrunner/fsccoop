<?php
    require_once("config.inc.php");
    require_once("token.validate.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    $account_id = isset($_POST['account_id']) ? $mysqli->real_escape_string($_POST['account_id']) : null;
    @$check = isset($_POST['check']) ? $mysqli->real_escape_string($_POST['check']) : null;
    @$index = isset($_POST['index']) ? $mysqli->real_escape_string($_POST['index']) : null;
    // get2
    if($check == 'get2'&&$index != '0'){
        $sql = "SELECT tb1.account_id,tb1.account_name,tb2.type_name FROM coop_maco_account AS tb1
    INNER JOIN coop_deposit_type_setting AS tb2 ON tb1.type_id = tb2.type_id
    WHERE tb1.account_id = '{$account_id}'  AND tb1.account_status = '0' ORDER BY tb1.created ASC";
    }else{
        $sql = "SELECT tb1.account_id,tb1.account_name,tb2.type_name FROM coop_maco_account AS tb1
        INNER JOIN coop_deposit_type_setting AS tb2 ON tb1.type_id = tb2.type_id
        WHERE tb1.account_id = '{$account_id}' AND tb1.mem_id = '{$member_id}' AND tb1.account_status = '0' ORDER BY tb1.created ASC";
    }
   
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $sqlAcc = "SELECT transaction_balance FROM coop_account_transaction WHERE account_id = '$account_id' ORDER BY transaction_id DESC";
        $rsAcc = $mysqli->query($sqlAcc);
        $rowAcc = $rsAcc->fetch_assoc();
        $data['data'] = [
            'account_id' => $row['account_id'],
            'account_name' => $row['account_name'],
            'account_type' => $row['type_name'],
            'balance' => ( $rowAcc['transaction_balance'] > 0) ? number_format($rowAcc['transaction_balance'], 2) : '0.00',
            'sv_ac_aval' => ( $rowAcc['transaction_balance'] > 0) ? number_format($rowAcc['transaction_balance'], 2) : '0.00',  //ยอดเงินที่ใช้ได้
        ];
    }

    $getYearLimit = (string)(date("Y") - 5 ).'-12-31';
    $sql = "SELECT tb1.transaction_time,tb1.transaction_deposit,tb1.transaction_withdrawal,tb2.money_type_name_th,tb2.money_type_name_eng ,tb1.transaction_balance, tb1.transaction_list
    FROM coop_account_transaction AS tb1
    LEFT JOIN coop_money_type AS tb2 ON tb1.transaction_list = tb2.money_type_name_short
    WHERE tb1.account_id = '{$account_id}' AND transaction_time > '{$getYearLimit}'
    ORDER BY transaction_time DESC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $data['status'] = 1;
        while( ($row = $rs->fetch_assoc()) ){
            $data['transaction'][] = [
                'transaction_time' => dateDB2thaidate($row['transaction_time'],true,false),
                'transaction_deposit' => number_format($row['transaction_deposit'], 2),
                'transaction_withdrawal' => number_format($row['transaction_withdrawal'], 2),
                'money_type_name_th' => $row['money_type_name_th'],
                'money_type_name_eng' => $row['transaction_list'],
                // 'money_type_name_th' => ($row['money_type_name_th'] == null OR $row['money_type_name_th'] == '') ? $row['transaction_list'] : $row['money_type_name_th'],
                // 'money_type_name_eng' => ($row['money_type_name_eng'] == null OR $row['money_type_name_eng'] == '') ? $row['transaction_list'] : $row['money_type_name_eng'],
                // 'money_type_name_th' => $row['money_type_name_th'],
                // 'money_type_name_eng' => $row['money_type_name_eng'],
                'is_status' => ( $row['transaction_deposit'] > 0 ) ? '+' : '-',
                'transaction_balance' => number_format($row['transaction_balance'], 2),
                
            ];
        }
    } else {
        $data['responseText'] = 'ไม่พบรายการเคลื่อนไหว';
    }


    echo json_encode($data); 
    exit();
?>