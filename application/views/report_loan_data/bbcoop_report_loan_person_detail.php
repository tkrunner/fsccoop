<style type="text/css">
    .table-view>thead, .table-view>thead>tr>td, .table-view>thead>tr>th {
        font-size: 15px;
    }
    .spacing-table-view{
        width: 100px;
        text-align: right;
    }
    .spacing-value{
        width: 120px;
        text-align: right;
    }
</style>
<div style="width: 1400px" class="page-break">
    <div class="panel panel-body flex-box" style="height: 1000px;">
        <table style="width: 100%;">
            <tr>
                <td style="vertical-align: top;" class="text-right">
                    <a class="no_print" onclick="window.print();"><button class="btn btn-perview btn-after-input" type="button"><span class="icon icon-print" aria-hidden="true"></span></button></a>
                </td>
            </tr>
        </table>
        <table style="width: 100%;">
            <tr>
                <td>
                    <h3 class="title_view text-center"><?php echo @$_SESSION['COOP_NAME'];?></h3>
                    <h3 class="title_view text-center">ทะเบียนหุ้น และบัญชีเงินกู้ ประเภทสหกรณ์ออมทรัพย์</h3>
                    <p>&nbsp;</p>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <h3 class="title_view">
                    </h3>
                </td>
            </tr>
        </table>
            <table style="width: 100%; font-size: 14px">
                <tr>
                    <td class="spacing-table-view">ค่าหุ้นรายเดือน</td>
                    <td class="spacing-value"><?php echo number_format($row_member['share_month'],2);?></td>
                    <td style="width: 40px; padding: 0 10px">บาท</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="spacing-table-view">จำนวนหุ้นรวม</td>
                    <td class="spacing-value"> <?php echo number_format($share['share_collect'],2);?></td>
                    <td style="width: 40px; padding: 0 10px">หุ้น</td>
                    <td style="text-align: right">เลขทะเบียนที่ <?php echo $row_member['member_id'] ?></td>
                </tr>
                <tr>
                    <td class="spacing-table-view">มูลค่าหุ้นรวม</td>
                    <td class="spacing-value"><?php echo number_format($share['share_collect_value'],2);?></td>
                    <td style="width: 40px; padding: 0 10px">บาท</td>
                    <td style="text-align: right"><?php echo $row_member['fullname_th']?></td>
                </tr>
            </table>
        <table class="table table-view table-center">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>ประเถทสัญญา</th>
                    <th>หนังสือกู้เงิน</th>
                    <th>วันที่</th>
                    <th>วงเงินกู้</th>
                    <th>จำนวนงวดที่ขอกู้</th>
                    <th>งวดที่</th>
                    <th>วันที่ชำระล่าสุด</th>
                    <th>เงินต้น</th>
                    <th>ดอกเบี้ย</th>
                    <th>เงินกู้คงเหลือ</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($data as $key => $loan){ ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td class="text-left"><?php echo $loan_name[$loan['loan']['loan_type']]['loan_name'];?></td>
                        <td><?php echo $loan['loan']['contract_number']?></td>
                        <td><?php echo $this->center_function->ConvertToThaiDate($loan['loan']['approve_date'], '', '');?></td>
                        <td class="text-right"><?php echo number_format($loan['loan']['loan_amount'], 2);?></td>
                        <td><?php echo $loan['loan']['period_amount'];?></td>
                        <td><?php echo $loan['loan']['period_now'];?></td>
                        <td class="text-right"><?php echo $this->center_function->ConvertToThaiDate($loan['loan']['date_last_interest']);?></td>
                        <td class="text-right"><?php echo number_format($loan['receipt']['principal'], 2);?></td>
                        <td class="text-right"><?php echo number_format($loan['receipt']['interest'], 2);?></td>
                        <td class="text-right"><?php echo number_format($loan['loan']['loan_amount_balance'], 2);?></td>
                    </tr>
                <?php $i++; } ?>
            </tbody>
        </table>
    </div>
</div>