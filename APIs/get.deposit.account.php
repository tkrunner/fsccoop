<?php
    require_once("config.inc.php");
    require_once("token.validate.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    $sql = "SELECT tb1.account_id,tb1.account_name,tb2.type_name FROM coop_maco_account AS tb1
    INNER JOIN coop_deposit_type_setting AS tb2 ON tb1.type_id = tb2.type_id
    WHERE tb1.mem_id = '{$member_id}' AND tb1.account_status = '0' ORDER BY tb1.created ASC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $sum = 0.00;
        $data['status'] = 1;
        // this.deposit_sum = this.func.formatNumber(this.data.deposit_sum.toFixed(2))
        while( ($row = $rs->fetch_assoc()) ){
            $account_id = $row['account_id'];
            $sqlAcc = "SELECT transaction_balance FROM coop_account_transaction WHERE account_id = '{$account_id}' ORDER BY transaction_id DESC";
            $rsAcc = $mysqli->query($sqlAcc);
            $rowAcc = $rsAcc->fetch_assoc();
            $sum += $rowAcc['transaction_balance'];
            $data['data'][] = [
                'account_id' => $row['account_id'],
                'account_name' => $row['account_name'],
                'account_type' => $row['type_name'],
                'balance' => ( $rowAcc['transaction_balance'] > 0) ? number_format($rowAcc['transaction_balance'], 2) : '0.00'
            ];
            $data['deposit_sum'] = number_format($sum, 2);
        }
    } else {
        $data['responseText'] = 'ไม่พบบัญชีเงินฝาก';
    }

    echo json_encode($data); 
    exit();
?>