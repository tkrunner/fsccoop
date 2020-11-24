<?php
    require_once("config.inc.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    require_once("token.validate.php");

    // $var_path = "https://system.nationco-op.org/";
	
	$var_path = "https://dev.policehospital-coop.com" ; 

    $year = isset($_POST['year']) ? $mysqli->real_escape_string($_POST['year']) : null;
    $month = isset($_POST['month']) ? $mysqli->real_escape_string($_POST['month']) : null;

    $yearThai = $year;
    $year = ((int)$year - 543);

    $sql = "SELECT 
    tb1.receipt_id,YEAR(tb2.receipt_datetime) + 543 AS years,MONTH(tb2.receipt_datetime) AS months,
    CASE 
        WHEN tb1.account_list_id = 14 THEN 'หุ้น'
        ELSE IF(tb2.finance_month_profile_id IS NULL,'อื่น ๆ','รายเดือน')
    END AS types
    FROM coop_finance_transaction AS tb1
    INNER JOIN coop_receipt AS tb2 ON tb1.receipt_id = tb2.receipt_id
    WHERE tb1.member_id = '{$member_id}' AND YEAR(tb2.receipt_datetime) = '{$year}' AND MONTH(tb2.receipt_datetime) = '{$month}'
    GROUP BY tb1.receipt_id
    ORDER BY MONTH(tb2.receipt_datetime) DESC";
    $rs = $mysqli->query($sql);
    if ( $rs->num_rows ) {
        $data['status'] = 1;
        while( ($row = $rs->fetch_assoc()) ){
            // if ( $row['types'] == 'หุ้น' ) {
            //     $url = "http://system.spktcoop.com/buy_share/receipt_buy_share?receipt_id=".$row['receipt_id'];
            //     $url_download = "http://system.spktcoop.com/buy_share/receipt_buy_share?receipt_id=".$row['receipt_id']."&is_download";
            // } else if ( $row['types'] == 'อื่น ๆ' ) {
            //     $url = "http://system.spktcoop.com/admin/receipt_form_pdf/".$row['receipt_id'];
            //     $url_download = "http://system.spktcoop.com/admin/receipt_form_pdf/".$row['receipt_id']."&is_download";
            // } else if ( $row['types'] == 'รายเดือน' ) {
            //     $url = "http://system.spktcoop.com/admin/receipt_month_pdf?month={$row['months']}&year={$row['years']}&action_type=real_print&choose_receipt=1";
            //     $url_download = "http://system.spktcoop.com/admin/receipt_month_pdf?month={$row['months']}&year={$row['years']}&action_type=real_print&choose_receipt=1&is_download";
            // }

            if ( $row['types'] == 'อื่น ๆ' ) {
                $url = $var_path."/admin/receipt_form_pdf_rev/".urlsafeB64Encode(encrypt_text($row['receipt_id']));
                $url_download = $var_path."/admin/receipt_form_pdf_rev/".urlsafeB64Encode(encrypt_text($row['receipt_id']))."?is_image=true";
                $base64 = $var_path."/admin/receipt_form_pdf_rev/".urlsafeB64Encode(encrypt_text($row['receipt_id']))."?is_base64=true";

                // $url = "https://system.spktcoop.com/admin/receipt_form_pdf_rev/".$row['receipt_id'];
                // $url_download = "https://system.spktcoop.com/admin/receipt_form_pdf_rev/".$row['receipt_id']."&is_download=true";
                // $url = "https://system.spktcoop.com/admin/receipt_form_pdf/".$row['receipt_id'];
                // $url_download = $var_path."/admin/receipt_form_pdf/".$row['receipt_id']."&is_download=true";
            } else {
                $url = $var_path."/admin/receipt_form_pdf_rev/".urlsafeB64Encode(encrypt_text($row['receipt_id']));
                $url_download = $var_path."/admin/receipt_form_pdf_rev/".urlsafeB64Encode(encrypt_text($row['receipt_id']))."?is_image=true";
                $base64 = $var_path."/admin/receipt_form_pdf_rev/".urlsafeB64Encode(encrypt_text($row['receipt_id']))."?is_base64=true";

                // $url = "https://system.spktcoop.com/admin/receipt_account_month_spkt_pdf_rev?month=".$month."&year=".$yearThai."&choose_receipt=2&member_id=".$member_id;
                // $url_download = "https://system.spktcoop.com/admin/receipt_account_month_spkt_pdf_rev?month=".$month."&year=".$yearThai."&choose_receipt=2&member_id=".$member_id."&is_download=true";
                // $url = "https://system.spktcoop.com/admin/receipt_account_month_spkt_pdf?month=".$month."&year=".$yearThai."&choose_receipt=2&member_id=".$member_id;
                // $url_download = "https://system.spktcoop.com/admin/receipt_account_month_spkt_pdf?month=".$month."&year=".$yearThai."&choose_receipt=2&member_id=".$member_id."&is_download=true";
            }

            $data['data'][] = [
                'bill_no' => $row['receipt_id'],
                'url' => $url,
                'url_download' => $url_download,
                'base64' => $base64,
                'genBase64URL' => $var_path.'/base64/get.base64.php',
                'type' => (trim($row['types']) == 'รายเดือน' ) ? 'month' : 'other'
            ];
        }
    }

    echo json_encode($data); 
    exit();
?>
