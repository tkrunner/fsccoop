<?php
    require_once("config.inc.php");
    require_once("parameter.inc.php");
    require_once("token.validate.php");

    $data = [ 'status' => 0, 'responseText' => '' ];

    //Get member_info
    $sql = "SELECT * FROM login_session WHERE token = '".$_POST["token"]."'";
    $rs = $mysqli->query($sql);
    $login_session = $rs->fetch_assoc();

    $sql = "SELECT t1.member_id,
                    t1.member_date,
                    t1.salary,
                    t1.birthday,
                    t1.other_income,
                    t3.share_collect_value as share_value,
                    t3.share_collect as share,
                    t4.count_share
            FROM coop_mem_apply as t1
            LEFT JOIN (SELECT MAX(share_date) as max_date, member_id FROM coop_mem_share WHERE member_id = '".$login_session["member_id"]."' AND share_status in (1,4,5,6))  as t2 ON t1.member_id = t2.member_id
            LEFT JOIN coop_mem_share as t3 ON t3.member_id = '".$login_session["member_id"]."' AND t3.share_date = t2.max_date AND t3.share_status in (1,4,5,6)
            LEFT JOIN (SELECT count(share_id) as count_share FROM coop_mem_share WHERE member_id = '".$login_session["member_id"]."' AND share_status = 1 GROUP BY member_id) as t4 ON t1.member_id = t2.member_id
            WHERE t1.member_id = '".$login_session["member_id"]."'";
    $rs = $mysqli->query($sql);
    $member = $rs->fetch_assoc();

    //Get Blue Deposit Accounts total balance
    $blue_acc_amount = 0;
    $sql = "SELECT transaction_balance
            FROM coop_maco_account as t1
            INNER JOIN coop_deposit_type_setting as t4 ON t1.type_id = t4.type_id AND t4.deduct_loan = '1'
            INNER JOIN (SELECT account_id, MAX(transaction_time) as lastest_timestamp FROM coop_account_transaction WHERE cancel_status IS NULL GROUP BY account_id) as t2 ON t1.account_id = t2.account_id
            INNER JOIN coop_account_transaction as t3 ON t2.account_id = t3.account_id AND t2.lastest_timestamp = t3.transaction_time
            WHERE t1.mem_id = '".$login_session["member_id"]."' AND t1.account_status = '0'";
    $rs = $mysqli->query($sql);
    while($account = $rs->fetch_assoc()) {
        $blue_acc_amount += $account["transaction_balance"];
    }

    //Generate Result
    $result = array();
    $result["member"] = $member;

    $date1 = new DateTime($member["member_date"]);
    $date2 = new DateTime(date("Y-m-d"));
    $interval = $date1->diff($date2);
    $months = $interval->m + ($interval->y * 12);
    $result["member"]["member_month"] = $months;

    $date1 = new DateTime($member["birthday"]);
    $date2 = new DateTime(date("Y-m-d"));
    $interval = $date1->diff($date2);
    $years = $interval->y;
    $result["member"]["age"] = $years;
    $result["member"]["age_month"] = $interval->m;

    $result["member"]["blue_acc_amount"] = $blue_acc_amount;

    $data['responseText'] = "success";
    $data["data"] = $result;
    echo json_encode($data);
    exit();
?>