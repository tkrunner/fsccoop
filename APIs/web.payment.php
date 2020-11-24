<?php
    require_once("config.inc.php");

    $data = [ 'chargback' => 0, 'responseText' => '', 'bill' => 0 ];

    require_once("parameter.inc.php");
    require_once("token.validate.php");

    $month = isset($_POST['month']) ? $mysqli->real_escape_string($_POST['month']) : null;
    $year = isset($_POST['year']) ? $mysqli->real_escape_string($_POST['year']) : null;
    $month = ((int)$month + 1);
    $year = ((int)$year + 543);

    $data['member_id'] = $member_id;

    $total = 0;

    // -------------------- Get chargback--------------------
    $sql = "SELECT
    tb1.pay_amount,
    tb1.loan_id,
    tb2.profile_year,
    tb2.profile_month,
    IF(tb3.deduct_id = 15,CONCAT(tb3.deduct_detail,' (หักเข้าบัญชีเงินฝาก)'),tb3.deduct_detail) AS deduct_detail
    FROM coop_finance_month_detail AS tb1
    INNER JOIN coop_finance_month_profile AS tb2 ON tb1.profile_id = tb2.profile_id
    LEFT JOIN coop_deduct AS tb3 ON tb1.deduct_id = tb3.deduct_id
    WHERE tb1.member_id = '{$member_id}' AND tb2.profile_month = {$month} AND tb2.profile_year = {$year}
    ORDER BY tb3.deduct_id ASC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $data['chargback'] = 1;
        $data['month'] = getMonths($month);
        $data['year'] = (string)$year;
        while( ($row = $rs->fetch_assoc()) ){
            $loan_id = $row['loan_id'];
            $period = "";
            if ( $row['loan_id'] != null OR  $row['loan_id'] != '' ) {
                $sql_p = "SELECT period_count
                FROM coop_finance_transaction 
                WHERE loan_id = '{$loan_id}' 
                ORDER BY period_count DESC LIMIT 1";
                $rs_p = $mysqli->query($sql_p);
                if ( $rs_p->num_rows ) {
                    $row_p = $rs_p->fetch_assoc();
                    $period = ((int)$row_p['period_count'] + 1);
                    $loan_amount_balance = $row['loan_amount_balance'];
                }
            }
            $total = $total += $row['pay_amount'];
            $data['chargback_data'][] = [
                'deduct_detail' => $row['deduct_detail'],
                'pay_amount' => number_format($row['pay_amount'], 2),
                'year' => $row['profile_year'],
                'month' => getMonths($row['profile_month']),
                'period' => $period
            ];
        }
        $data['total'] = number_format($total, 2);
    }

    $sql = "SELECT
    tb1.firstname_th,
    tb1.lastname_th,
    tb2.prename_short,
    tb3.mem_group_name AS Affiliation_1,
    tb4.mem_group_name AS Affiliation_2,
    tb5.mem_group_name AS Affiliation_3
    FROM coop_mem_apply AS tb1
    LEFT JOIN coop_prename AS tb2 ON tb2.prename_id = tb1.prename_id
    LEFT JOIN coop_mem_group AS tb3 ON tb3.id = tb1.department
    LEFT JOIN coop_mem_group AS tb4 ON tb4.id = tb1.faction
    LEFT JOIN coop_mem_group AS tb5 ON tb5.id = tb1.level
    WHERE tb1.member_id = '{$member_id}'";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $row = $rs->fetch_assoc();

        $affiliation_1 = ($row['Affiliation_1'] == '' || $row['Affiliation_1'] == 'ไม่ระบุ' || $row['Affiliation_1'] == null) ? '' : $row['Affiliation_1'];
        $affiliation_2 = ($row['Affiliation_2'] == '' || $row['Affiliation_2'] == 'ไม่ระบุ' || $row['Affiliation_2'] == null) ? '' : $row['Affiliation_2'];
        $affiliation_3 = ($row['Affiliation_3'] == '' || $row['Affiliation_3'] == 'ไม่ระบุ' || $row['Affiliation_3'] == null) ? '' : $row['Affiliation_3'];

        $comma_1 =  '';
        if ( $affiliation_1 != '' && $affiliation_2 != '' ) $comma_1 = ', ';

        $comma_2 =  '';
        if ( $affiliation_2 != '' && $affiliation_3 != '' ) $comma_2 = ', ';
        if ( $affiliation_1 != '' && $affiliation_2 == '' && $affiliation_3 != '' ) $comma_2 = ', ';

        $data['member'] = [
            'member_name' => $row['prename_short']." ".$row['firstname_th']." ".$row['lastname_th'],
            'affiliation' => $affiliation_1.$comma_1.$affiliation_2.$comma_2.$affiliation_3
        ];
    }
    // -------------------- Get chargback--------------------

    // -------------------- Get List--------------------
    $sql = "SELECT tb1.profile_year 
    FROM coop_finance_month_profile AS tb1
    INNER JOIN coop_finance_month_detail AS tb2 ON tb1.profile_id = tb2.profile_id
    WHERE tb2.member_id = '{$member_id}' GROUP BY tb1.profile_year ORDER BY tb1.profile_year DESC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $data['bill'] = 1;
        while( ($row = $rs->fetch_assoc()) ){
            $data['years'][] = [
                'year_count' => $row['profile_year']
            ];
        }
    }

    $sql = "SELECT tb1.profile_year, tb1.profile_month 
    FROM coop_finance_month_profile AS tb1
    INNER JOIN coop_finance_month_detail AS tb2 ON tb1.profile_id = tb2.profile_id
    WHERE tb2.member_id = '{$member_id}' AND tb2.run_status = '1' 
    GROUP BY tb1.profile_year,tb1.profile_month  
    ORDER BY tb1.profile_year DESC, tb1.profile_month DESC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        while( ($row = $rs->fetch_assoc()) ){
            if ( $row['profile_year'] > 2561 ) {
                $data['months'][] = [
                    'year_count' => $row['profile_year'],
                    'month_name' => getMonths($row['profile_month']),
                    'month_count' => $row['profile_month'],
                    'month_pdf' => "https://system.spktcoop.com/admin/receipt_account_month_spkt_pdf?month=".$row['profile_month']."&year=".$row['profile_year']."&choose_receipt=2&member_id=".$member_id
                ];
            } else if ( $row['profile_year'] == 2561 AND $row['profile_month'] > 9 ) {
                $data['months'][] = [
                    'year_count' => $row['profile_year'],
                    'month_name' => getMonths($row['profile_month']),
                    'month_count' => $row['profile_month'],
                    'month_pdf' => "https://system.spktcoop.com/admin/receipt_account_month_spkt_pdf?month=".$row['profile_month']."&year=".$row['profile_year']."&choose_receipt=2&member_id=".$member_id
                ];
            }
        }
    }
    // -------------------- Get List--------------------

    echo json_encode($data);
    exit();
?>