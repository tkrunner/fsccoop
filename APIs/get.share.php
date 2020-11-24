<?php
    require_once("config.inc.php");
    // require_once("token.validate.php");

    $data = [ 'status' => 0, 'responseText' => '', 'start_year_share' => '0.00', 'share_collect' => '0.00', 'share_per_month' => '0.00', 'is_trans' => 0 ];
    require_once("parameter.inc.php");
    require_once("token.validate.php");
    $getYearLimit = (string)(date("Y") - 1).'-12-31';
    $sql = "SELECT share_collect FROM coop_mem_share WHERE member_id = '{$member_id}' 
    AND share_status IN('1','2') AND share_date <= '{$getYearLimit}'
    ORDER BY  share_date DESC LIMIT 1";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $data['start_year_share'] = ( $row['share_collect'] > 0 ) ? number_format(($row['share_collect'] * 10), 2)." บาท" : number_format(0, 2)." บาท";
    } else {
        $data['start_year_share'] = number_format(0, 2)." บาท";
    }

    $sql = "SELECT share_collect FROM coop_mem_share WHERE member_id = '{$member_id}' AND share_status IN('1','2') ORDER BY  share_date DESC LIMIT 1";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $data['share_collect'] = ( $row['share_collect'] > 0 ) ? number_format(($row['share_collect'] * 10), 2)." บาท" : number_format(0, 2)." บาท";
    } else {
        $data['share_collect'] = number_format(0, 2)." บาท";
    }
    
    $sql = "SELECT share_month FROM coop_mem_apply WHERE member_id = '{$member_id}'";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();
        $data['share_per_month'] = ( $row['share_month'] > 0 ) ? number_format($row['share_month'], 2)." บาท" : number_format(0, 2)." บาท";
    } else {
        $data['share_per_month'] = number_format(0, 2)." บาท";
    }

    $getYearLimit = (string)(date("Y") - 1).'-01-01';
    $sql = "SELECT tb1.payment_date,tb1.period_count,tb1.loan_amount_balance,tb1.receipt_id,tb1.total_amount,tb1.account_list_id,tb2.account_list
    FROM coop_finance_transaction AS tb1
    INNER JOIN coop_account_list AS tb2 ON tb1.account_list_id = tb2.account_id
    WHERE member_id = '{$member_id}' AND payment_date >= '{$getYearLimit}' AND tb1.account_list_id = 14
    OR member_id = '{$member_id}' AND payment_date >= '{$getYearLimit}' AND tb1.account_list_id = 16
    ORDER BY payment_date DESC, period_count DESC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $data['is_data'] = 1;
        while( ($row = $rs->fetch_assoc()) ){
            $data['transection'][] = [
                'payment_date' => date2thaiformat($row['payment_date']),
                'date_thai' => dateDB2thaidate($row['payment_date'],true,false,false),
                'account_list_id' => $row['account_list_id'],
                'account_list' => $row['account_list'],
                'period_count' => ( $row['period_count'] == null) ? 'N/A' : $row['period_count'],
                'loan_amount_balance' => ( $row['loan_amount_balance'] > 0 ) ? number_format($row['loan_amount_balance'], 2)." บาท" : number_format(0, 2)." บาท",
                'receipt_id' => ( $row['receipt_id'] == null) ? 'N/A' : $row['receipt_id'],
                'total_amount' => ( $row['total_amount'] > 0 ) ? number_format($row['total_amount'], 2)." บาท" : number_format(0, 2)." บาท"
            ];
        }
    }

    // $sql = "SELECT *,share_type_name FROM coop_mem_share INNER JOIN coop_share_type AS tb2 ON share_type = share_type_code WHERE  member_id = '{$member_id}' AND YEAR(share_date) = YEAR(CURDATE())
    // ORDER BY share_date DESC";
    // $rs = $mysqli->query($sql);
    // if ( $rs->num_rows ) {
    //     $data['is_data'] = 1;
    //     while( ($row = $rs->fetch_assoc()) ){
    //         $data['transection_share'][] = [
    //             'payment_date' => date2thaiformat($row['share_date']),
    //             'date_thai' =>dateDB2thaidate($row['share_date'],true,false),
    //             'share_type' => $row['share_type_name'],
    //             'account_list' => $row['account_list'],
    //             'period_count' => ( $row['period_count'] == null) ? 'N/A' : $row['period_count'],
    //             'loan_amount_balance' => ( $row['loan_amount_balance'] > 0 ) ? number_format($row['loan_amount_balance'], 2)." บาท" : number_format(0, 2)." บาท",
    //             'receipt_id' => ( $row['receipt_id'] == null) ? 'N/A' : $row['receipt_id'],
    //             'total_amount' => ( $row['total_amount'] > 0 ) ? number_format($row['total_amount'], 2)." บาท" : number_format(0, 2)." บาท",
    //             'share_early_value' => ( $row['share_early_value'] > 0 ) ? number_format($row['share_early_value'], 2) : number_format(0, 2),
    //         ];
    //     }
    // }

    echo json_encode($data); 
    exit();
?>