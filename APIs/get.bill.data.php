<?php
    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    require_once("token.validate.php");

    $var_path = "https://system.tistrsaving.com";
    $data['member_id'] = $member_id;

    $year = isset($_POST['year']) ? $mysqli->real_escape_string($_POST['year']) : null;
    $month = isset($_POST['month']) ? $mysqli->real_escape_string($_POST['month']) : null;
    $receipt_id = isset($_POST['receipt_id']) ? $mysqli->real_escape_string($_POST['receipt_id']) : null;
    $type = isset($_POST['type']) ? $mysqli->real_escape_string($_POST['type']) : null;

    // =============================
    //       Start Signature
    // =============================
        $date = date('Y-m-d');
        $sql = "SELECT signature_3 AS sig_manager, signature_2 AS sig_finance FROM coop_signature WHERE start_date <= '{$date}' ORDER BY start_date DESC LIMIT 1";
        $rs = $mysqli->query($sql);
        if ( $rs->num_rows ) {
            $row = $rs->fetch_assoc();
            $data['sig_manager'] = $var_path."/assets/images/coop_signature/".$row['sig_manager'];
            $data['sig_finance'] = $var_path."/assets/images/coop_signature/".$row['sig_finance'];
        } else {
            $data['sig_manager'] = '';
            $data['sig_finance'] = '';
        }
    // ===========================
    //        End Signature
    // ===========================

    // =============================
    //     Start Bill Infomation
    // =============================
        $sql = "SELECT t1.firstname_th, t1.lastname_th, t2.prename_full, t3.mem_group_name
        FROM coop_mem_apply as t1
        LEFT JOIN coop_prename as t2 ON t1.prename_id = t2.prename_id
        LEFT JOIN coop_mem_group as t3 ON t1.level = t3.id
        WHERE t1.member_id = '{$member_id}'";
        $rs = $mysqli->query($sql);
        if ( $rs->num_rows ) {
            $row = $rs->fetch_assoc();
            $data['bill_name'] = $row['prename_full'].$row['firstname_th'].'  '.$row['lastname_th'];
            $data['group_name'] = $row['mem_group_name'];
        } else {
            $data['bill_name'] = '';
            $data['group_name'] = '';
        }

        if ( $type == 'month' ) {

            $sql = "SELECT t1.receipt_id, t3.id, t1.receipt_datetime, t5.date_move
            FROM coop_finance_month_detail as t0
            INNER JOIN coop_receipt as t1 ON t1.finance_month_profile_id = t0.profile_id AND t1.year_receipt = {$year} AND t1.month_receipt = {$month}
            INNER JOIN coop_finance_transaction as t2 ON t1.receipt_id = t2.receipt_id
            LEFT JOIN coop_loan as t3 ON t2.loan_id = t3.id
            LEFT JOIN coop_mem_apply as t4 ON t1.member_id = t4.member_id
            LEFT JOIN coop_mem_group_move as t5 ON t5.member_id = t4.member_id	AND t5.date_move >= t1.receipt_datetime
            WHERE t1.receipt_id NOT LIKE '%C%'
            AND 1 = 1  AND t2.member_id = '{$member_id}'
            GROUP BY t1.receipt_id
            ORDER BY t1.member_id ASC";
            $rs = $mysqli->query($sql);
            if ( $rs->num_rows ) {
                $row = $rs->fetch_assoc();
                $data['bill_date'] = date_bill($row['receipt_datetime']);
                $data['receipt_id'] = $receipt_id;
            } else {
                $data['bill_date'] = '';
                $data['receipt_id'] = '';
            }

        } else {

            $sql = "SELECT payment_date FROM coop_finance_transaction WHERE receipt_id = '{$receipt_id}' ORDER BY account_list_id DESC LIMIT 1";
            $rs = $mysqli->query($sql);
            if ( $rs->num_rows ) {
                $row = $rs->fetch_assoc();
                $data['bill_date'] = date_bill($row['payment_date']);
                $data['receipt_id'] = $receipt_id;
            } else {
                $data['bill_date'] = '';
                $data['receipt_id'] = '';
            }

        }
    // ===========================
    //     End Bill Infomation
    // ===========================

    // ===========================
    //     Start Bill List
    // ===========================
        $total = 0;
        if ( $type == 'month' ) {

            $sql = "SELECT t6.contract_number as bill_list
            , t5.account_list
            , t4.interest, t4.period_count
            , IF((SELECT SUM(loan_amount_balance) FROM coop_finance_transaction WHERE loan_id = t4.loan_id AND receipt_id = '{$receipt_id}'), (SELECT SUM(loan_amount_balance) FROM coop_finance_transaction WHERE loan_id = t4.loan_id AND receipt_id = '{$receipt_id}'), t4.loan_amount_balance) AS final_total
            , IF(((SELECT SUM(total_amount) FROM coop_finance_transaction WHERE loan_id = t4.loan_id AND receipt_id = '{$receipt_id}') - t4.interest), ((SELECT SUM(total_amount) FROM coop_finance_transaction WHERE loan_id = t4.loan_id AND receipt_id = '{$receipt_id}') - t4.interest), t4.principal_payment) AS final_principle
            , t9.seq_list_pdf
            FROM coop_finance_month_profile as t1
            INNER JOIN coop_finance_month_detail as t2 ON t1.profile_id = t2.profile_id
            LEFT JOIN coop_receipt as t3 ON t1.profile_id = t3.finance_month_profile_id
            LEFT JOIN coop_finance_transaction as t4 ON t3.receipt_id = t4.receipt_id
            LEFT JOIN coop_account_list as t5 ON t4.account_list_id = t5.account_id
            LEFT JOIN coop_loan as t6 ON t4.loan_id = t6.id
            LEFT JOIN coop_loan_atm as t7 ON t4.loan_atm_id = t7.loan_atm_id
            LEFT JOIN coop_mem_share as t8 ON t4.receipt_id = t8.share_bill
            INNER JOIN coop_deduct as t9 ON t9.deduct_id = t2.deduct_id
            WHERE t2.member_id = '{$member_id}'
            AND t1.profile_month = '{$month}'
            AND t3.receipt_id = '{$receipt_id}'
            GROUP BY t4.account_list_id, t4.loan_id
            ORDER BY t9.seq_list_pdf ASC, t5.account_list ASC";
            $rs = $mysqli->query($sql);
            if ( $rs->num_rows ) {
                $data['status'] = 1;
                while( ($row = $rs->fetch_assoc()) ) {
                    $total = ($total + ($row['final_principle'] + $row['interest']));
                    $data['bill_list'][] = [
                        'list'  =>  ($row['bill_list'] == null OR $row['bill_list'] == '') ? ($row['account_list'] == 'ชำระเงินค่าหุ้นรายเดือน') ? 'หุ้น' : $row['account_list'] : $row['bill_list'],
                        'period'    =>  ($row['period_count'] == 0 OR $row['period_count'] == null OR $row['period_count'] == '') ? '' : $row['period_count'],
                        'final_principle'   =>  number_format($row['final_principle'], 2),
                        'interest'  =>  number_format($row['interest'], 2),
                        'payment'   =>  number_format($row['final_principle'] + $row['interest'], 2),
                        'final_total'   =>  number_format($row['final_total'], 2)
                    ];
                }
                $data['payment_total'] = number_format($total, 2);
                $data['payment_total_char'] = NumberToChar($total);
            } 

        } else {

            $sql = "SELECT transaction_text, principal_payment, interest, loan_amount_balance FROM coop_finance_transaction WHERE receipt_id = '{$receipt_id}' ORDER BY account_list_id ASC";
            $rs = $mysqli->query($sql);
            if ( $rs->num_rows ) {
                $data['status'] = 1;
                while( ($row = $rs->fetch_assoc()) ) {
                    $total = ($total + ($row['principal_payment'] + $row['interest']));
                    $data['bill_list'][] = [
                        'list'  =>  $row['transaction_text'],
                        'period'    =>  '',
                        'final_principle'   =>  number_format($row['principal_payment'], 2),
                        'interest'  =>  number_format($row['interest'], 2),
                        'payment'   =>  number_format($row['principal_payment'] + $row['interest'], 2),
                        'final_total'   =>  number_format($row['loan_amount_balance'], 2)
                    ];
                }
                $data['payment_total'] = number_format($total, 2);
                $data['payment_total_char'] = NumberToChar($total);
            }

        }
    // ===========================
    //     End Bill List
    // ===========================

    echo json_encode($data); 
    exit();
?>
