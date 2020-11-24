<div class="layout-content">
    <div class="layout-content-body">
        <style>
            .center {
                text-align: center;
            }
            .left {
                text-align: left;
            }
            .modal-dialog-account {
                margin:auto;
                margin-top:7%;
            }
            .modal-dialog-data {
                width:90% !important;
                margin:auto;
                margin-top:1%;
                margin-bottom:1%;
            }
            .modal-dialog-cal {
                width:80% !important;
                margin:auto;
                margin-top:1%;
                margin-bottom:1%;
            }
            .modal-dialog-file {
                width:50% !important;
                margin:auto;
                margin-top:1%;
                margin-bottom:1%;
            }
            .modal_data_input{
                margin-bottom: 5px;
            }
            .form-group{
                margin-bottom: 5px;
            }
            .red{
                color: red;
            }
            .green{
                color: green;
            }

            .tab-content {
                min-height: 25vh;
                border-left: 2px #eee solid;
                border-right: 2px #eee solid;
                border-bottom: 2px #eee solid;
            }
            .nav-tabs, .nav-tabs>li{
                background-color: #efefef;

            }
            .nav-tabs>li{
                border-left: 1px #e6eef1 solid;
            }
            .nav-tabs>li>a{
                padding: 10px 34px;
                color: #2c2c2c;
                font-size: 12px;
            }
            .nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover{
                background-color: #fff !important;
                border-top: 1px #d2d2d2 solid;
                border-left: 1px #d2d2d2 solid;
                border-right: 1px #d2d2d2 solid;
                color: #2c2c2c;
                font-size: 12px;
            }

            .btn-smaller{
                width: 40px;
            }
            .content-submit-btn{
                text-align: center;
                margin: 15px auto;
            }
            .text-green-light{
                color: #067c3b;
                font-weight: 700;
            }

        </style>
        <div class="row">
            <div class="form-group">
                <div class="col-sm-6">
                    <h1 class="title_top">เพิ่มคำขอกู้</h1>
                    <?php $this->load->view('breadcrumb'); ?>
                </div>

            </div>
        </div>
        <form id="form_contract" action="<?php echo base_url("loan_contract/save_contract")?>" method="post">
        <div class="row gutter-xs">
            <div class="col-xs-12 col-md-12">
                <div class="panel panel-body" style="padding-top:15px !important;">
                    <div class="g24-col-sm-24">
                        <div class="form-group g24-col-sm-8">
                            <label class="control-label g24-col-sm-6">ประเภทเงินกู้</label>
                                <div class="g24-col-sm-18">
                                    <select id="loan_type_choose" class="form-control" onchange="change_type()" >
                                        <option value="">เลือกประเภทการกู้เงิน</option>
                                        <?php foreach($rs_loan_type as $key => $value){ ?>
                                            <option value="<?php echo $value['id']; ?>"  <?php echo $value['id'] == @$default_loan ? 'selected="selected"' : ''; ?>><?php echo $value['loan_type']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                        </div>
                        <div class="form-group g24-col-sm-8">
                            <label class="g24-col-sm-6 control-label">ชื่อเงินกู้</label>
                            <div class="g24-col-sm-18">
                                <select id="loan_type_select" class="form-control" name="data[coop_loan][loan_type]">
                                    <option value="">เลือกชื่อเงินกู้</option>
                                    <?php foreach ($rs_loan_name as $key => $value){ ?>
                                        <option value="<?php echo $value['loan_name_id']?>" <?php echo $value['loan_name_id'] == @$default_loan_name ? 'selected="selected"' : '' ?>><?php echo $value['loan_name']?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php $this->load->view('search_member_for_loan_contract.php'); ?>
                    <fieldset class="g24-col-sm-24 sc">
                        <legend >ภาระอื่นๆ ที่ต้องชำระ</legend>
                        <div class="form-group g24-col-sm-5">
                            <label class="g24-col-sm-10">เงินฝาก</label>
                            <div class="g24-col-sm-14">
                                <input type="text" value="<?php echo number_format(@$payment_deposit,2); ?>" class="form-control text-right payment_per_month" readonly>
                            </div>
                        </div>
                        <div class="form-group g24-col-sm-4">
                            <label class="g24-col-sm-7">เงินกู้</label>
                            <div class="g24-col-sm-17">
                                <input type="text" value="<?php echo number_format(@$payment_loan,2); ?>" class="form-control text-right payment_per_month" readonly>
                            </div>
                        </div>
                        <div class="form-group g24-col-sm-4">
                            <label class="g24-col-sm-7">หุ้น</label>
                            <div class="g24-col-sm-17">
                                <input type="text" value="<?php echo number_format(@$payment_share,2); ?>" class="form-control text-right  payment_per_month" readonly>
                            </div>
                        </div>
                        <div class="form-group g24-col-sm-4">
                            <label class="g24-col-sm-7">อื่นๆ</label>
                            <div class="g24-col-sm-17">
                                <input type="text" name="data[coop_loan_cost][OTH]" value="<?php echo number_format(@$payment_other,2); ?> " class="form-control text-right loan_cost payment_per_month" onblur="calcCost(); number_format(this)">
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="g24-col-sm-24">
                        <legend>รายการชำระ</legend>
                        <div class="form-group g24-col-sm-5">
                            <label class="g24-col-sm-10">เงินต้น</label>
                            <div class="g24-col-sm-14">
                                <input type="text" value="<?php echo number_format(@$payment_principle,2); ?>" class="form-control text-right" readonly>
                            </div>
                        </div>
                        <div class="form-group g24-col-sm-4">
                            <label class="g24-col-sm-7">ดอกเบี้ย</label>
                            <div class="g24-col-sm-17">
                                <input type="text" value="<?php echo number_format(@$payment_interest,2); ?>" class="form-control text-right" readonly>
                            </div>
                        </div>
                        <div class="form-group g24-col-sm-4">
                            <label class="g24-col-sm-7">ซื้อหุ้น</label>
                            <div class="g24-col-sm-17">
                                <input type="text" value="<?php echo number_format(@$payment_share,2); ?>" class="form-control text-right" readonly>
                            </div>
                        </div>
                        <div class="form-group g24-col-sm-4">
                            <label class="g24-col-sm-7">เงินเดือน</label>
                            <div class="g24-col-sm-17">
                                <div class="input-group">
                                    <input class="form-control text-right" type="text" name="data[coop_loan][salary]" value="<?php echo number_format($income_per_month, 2);?>" readonly>
                                    <span class="input-group-btn">
                                            <button id="" type="button" class="btn btn-info btn-search" onclick="open_modal('update_salary_modal')">
                                                <span class="icon icon-pencil"></span>
                                            </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group g24-col-sm-4">
                            <label class="g24-col-sm-7">คงเหลือ</label>
                            <div class="g24-col-sm-17">
                                <input type="text"  value="<?php echo number_format($net_balance, 2) ?>" class="form-control text-right net_balance" readonly>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="g24-col-sm-24">
                        <legend>การกู้เงิน</legend>
                        <div class="form-group g24-col-sm-5">
                            <label class="g24-col-sm-10 control-label">สิทธิ์กู้สูงสุด</label>
                            <div class="g24-col-sm-14">
                                <input type="text" id="max_loan_limit" value="" data-optional="loan" class="form-control text-right" readonly>
                            </div>
                        </div>
                        <div class="form-group g24-col-sm-5">
                            <label class="g24-col-sm-8 control-label">จำนวนเงินขอกู้</label>
                            <div class="g24-col-sm-16">
                                <input type="text" id="loan_amount" name="data[coop_loan][loan_amount]" data-meta="credit_limit" data-optional="loan" value="" class="form-control validation text-right" onchange="setLoanAmount(this.value); calcPeriod()" onblur="calcProcessing();number_format(this)">
                            </div>
                        </div>
                        <div class="form-group g24-col-sm-8">
                            <label class="g24-col-sm-6 control-label">เหตุผลการกู้</label>
                            <div class="g24-col-sm-18">
                                <select name="data[coop_loan][loan_reason]" class="form-control" id="loan_reason">
                                    <option value="">ไม่ระบุ</option>
                                    <?php
                                    foreach($rs_loan_reason as $key => $row_loan_reason){
                                        ?>
                                        <option value="<?php echo $row_loan_reason['loan_reason_id']; ?>"><?php echo $row_loan_reason['loan_reason']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div style="clear: both">
                        <div class="form-group g24-col-sm-5">
                            <label class="g24-col-sm-10 control-label">การชำระ</label>
                            <div class="g24-col-sm-14">
                                <div class="radio-inline">
                                    <label>
                                        <input type="radio"  name="data[coop_loan][pay_type]" value="1" onclick="calcPeriod();" <?php echo ($this->Setting_model->get("data[coop_loan][pay_type]")==1) ? "checked" : "" ?>>ต้นเท่า
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                        <input type="radio"  name="data[coop_loan][pay_type]" value="2" onclick="calcPeriod();" <?php echo ($this->Setting_model->get("data[coop_loan][pay_type]")==2) ? "checked" : "" ?>> รวมเท่า
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group g24-col-sm-3">
                            <label class="g24-col-sm-14 control-label">จำนวนงวด</label>
                            <div class="g24-col-sm-10">
                                <input type="text" id="period_amount" name="data[coop_loan][period_amount]" value="" class="form-control text-right" onchange="setMaxPeriod(this.value);calcPeriod();" onblur="calcProcessing();">
                            </div>
                        </div>
                        <div class="form-group g24-col-sm-5">
                            <label class="g24-col-sm-10">วันที่เริ่มสัญญา</label>
                            <div class="input-with-icon g24-col-sm-14">
                                <div class="form-group">
                                    <input id="createdatetime" name="data[coop_loan][createdatetime]" class="form-control m-b-1" style="padding-left: 50px;" type="text" value="<?php echo $this->center_function->mydate2date(date('Y-m-d')); ?>" data-date-language="th-th" required title="" >
                                    <span class="icon icon-calendar input-icon m-f-1"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group g24-col-sm-5">
                            <label class="g24-col-sm-10">วันที่ชำระงวดแรก</label>
                            <div class="input-with-icon g24-col-sm-14">
                                <div class="form-group">
                                    <input id="date_receive_money" name="data[loan_deduct_profile][date_receive_money]" class="form-control m-b-1" style="padding-left: 50px;" type="text" value="<?php echo $this->center_function->mydate2date(@$first_receive_date); ?>" data-date-language="th-th" required title="" >
                                    <span class="icon icon-calendar input-icon m-f-1"></span>
                                </div>
                            </div>
                        </div>
                           <div style="clear: both">
                        <div class="form-group g24-col-sm-5">
                            <label class="g24-col-sm-10">ผ่อนต่องวด</label>
                            <div class="g24-col-sm-14">
                                <input type="text" id="pay_amount" name="data[coop_loan][pay_amount]" value="" class="form-control text-right" onblur="number_format(this)">
                            </div>
                        </div>
                        <div class="form-group g24-col-sm-5">
                            <label class="g24-col-sm-8">หักกลบหนี้เดิม</label>
                            <div class="g24-col-sm-14">
                                <input type="text" id="deduct_amount" value="" class="form-control text-right" readonly>
                            </div>
                        </div>
                        <div class="form-group g24-col-sm-5">
                            <label class="g24-col-sm-11"><span class="text-green-light">จำนวนเงินที่จะได้รับ</span></label>
                            <div class="g24-col-sm-9">
                                <input type="text" id="estimate_money" name="data[loan_deduct_profile][estimate_receive_money]" value="" class="form-control text-right text-green-light" readonly>
                            </div>
                        </div>
                    </fieldset>
                    <div class="g24-col-sm-24">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#Overdue" >ค้างชำระ</a></li>
                            <li><a data-toggle="tab" href="#Contract">สัญญาเงินกู้</a></li>
                            <li><a data-toggle="tab" href="#Guarantee">หลักประกัน</a></li>
                            <li><a data-toggle="tab" href="#Payment">การจ่ายเงินกู้</a></li>
                            <?php if(isset($deduct_list_enable) && $deduct_list_enable == 1){ ?>
                                <li><a data-toggle="tab" href="#DeductList">หักเพิ่มเติม</a></li>
                            <?php } ?>
                            <?php if(isset($buy_list_enable) && $buy_list_enable == 1){ ?>
                            <li><a data-toggle="tab" href="#BuyList">ซื้อเพิ่มเติม</a></li>
                            <?php } ?>
                            <?php if(isset($cost_list_enable) && $cost_list_enable == 1){ ?>
                                <li><a data-toggle="tab" href="#CostList">ค่าใช้จ่ายเพิ่มเติม</a></li>
                            <?php } ?>
                            <li><a data-toggle="tab" href="#MemberInfo">ข้อมูลสมาชิก</a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="Overdue" class="tab-pane fade in active">
                                <?php include("overdue.php"); ?>
                            </div>
                            <div id="Contract" class="tab-pane fade">
                                <?php include("contract.php"); ?>
                            </div>
                            <div id="Guarantee" class="tab-pane fade">
                                <?php include("guarantee.php"); ?>
                            </div>
                            <div id="Payment" class="tab-pane fade">
                                <?php include("payment.php"); ?>
                            </div>
                            <?php if(@$deduct_list_enable){?>
                            <div id="DeductList" class="tab-pane fade">
                                <?php include("deduct.php");?>
                            </div>
                            <?php } ?>
                            <?php if(@$buy_list_enable){ ?>
                            <div id="BuyList" class="tab-pane fade">
                                <?php include("buy.php");?>
                            </div>
                            <?php } ?>
                            <?php if(@$cost_list_enable){ ?>
                                <div id="CostList" class="tab-pane fade">
                                    <?php include("cost.php");?>
                                </div>
                            <?php } ?>
                            <div id="MemberInfo" class="tab-pane fade">
                                <?php include("mem_info.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="g24-col-sm-24 text-center">
                        <div class="content-submit-btn">
                            <button type="button" class="btn btn-primary" onclick="confirm();"><span class="fa fa-save"></span>&nbsp;บันทึก</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <input type="hidden" id="loan_id" name="loan_id" value="<?php echo $_GET['loan_id'];?>">
            <input type="hidden" id="deduct_pay_prev_loan" name="data[loan_deduct][deduct_pay_prev_loan]" value="">
            <input type="hidden" name="data[coop_loan][member_id]" value="<?php echo $_GET['member_id'];?>">
            <input type="hidden" name="data[coop_loan][petition_number]" value="">
            <input type="hidden" name="data[coop_loan][period_type]" value="1">
            <input id="interest_per_year" type="hidden" name="data[coop_loan][interest_per_year]" value="">
            <?php
            if(isset($income) && sizeof($income) > 0) {
                foreach ($income as $key => $value) {
                    echo '<input type="hidden" class="form-control numeral" name="income[' . $value['id'] . ']" value="' . $value['income_value'] . '" />';
                }
            }
            ?>
        </form>
    </div>
</div>
<?php $this->load->view('search_member_new_modal'); ?>
<div class="modal fade" id="search_member_loan_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ข้อมูลสมาชิก</h4>
            </div>
            <div class="modal-body">
                <div class="input-with-icon">
                    <div class="row">
                        <div class="col">
                            <label class="col-sm-2 control-label">รูปแบบค้นหา</label>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <select id="member_search_list" name="member_search_list" class="form-control m-b-1">
                                        <option value="">เลือกรูปแบบค้นหา</option>
                                        <option value="member_id">รหัสสมาชิก</option>
                                        <option value="id_card">หมายเลขบัตรประชาชน</option>
                                        <option value="firstname_th">ชื่อสมาชิก</option>
                                        <option value="lastname_th">นามสกุล</option>
                                    </select>
                                </div>
                            </div>
                            <label class="col-sm-1 control-label" style="white-space: nowrap;"> ค้นหา </label>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input id="member_search_text" name="member_search_text" class="form-control m-b-1" type="text" value="<?php echo @$data['id_card']; ?>">
                                        <span class="input-group-btn">
									<button type="button" id="member_loan_search" class="btn btn-info btn-search"><span class="icon icon-search"></span></button>
								</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bs-example" data-example-id="striped-table">
                    <table class="table table-striped">
                        <tbody id="result_member_search">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="input_id">
                <button type="button" id="close" class="btn btn-default" data-dismiss="modal">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="guarantee_person_data_modal" role="dialog">
    <div class="modal-dialog modal-dialog-file">
        <div class="modal-content data_modal">
            <div class="modal-header modal-header-confirmSave">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">ข้อมูลผู้ค้ำประกัน</h3>
            </div>
            <div class="modal-body">
                <div class="bs-example" data-example-id="striped-table" id="guarantee_person_data">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="close" class="btn btn-default" data-dismiss="modal">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="period_table" role="dialog">
    <div class="modal-dialog modal-dialog-data">
        <div class="modal-content data_modal">
            <div class="modal-header modal-header-confirmSave">
                <button type="button" class="close" onclick="close_modal('period_table')">&times;</button>
                <h2 class="modal-title" id="type_name">ตารางคำนวณการชำระเงิน</h2>
            </div>
            <div class="modal-body period_table">

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="check_term_of_loan_result_modal" role="dialog">
    <div class="modal-dialog modal-dialog-file">
        <div class="modal-content data_modal">
            <div class="modal-header modal-header-confirmSave">
                <button type="button" class="close" onclick="close_modal('check_term_of_loan_result_modal')">&times;</button>
                <h2 class="modal-title" id="type_name">ตรวจสอบเงื่อนไขการกู้เงิน</h2>
            </div>
            <div class="modal-body">
                <div id="check_term_of_loan_result"></div>
                <div class="center">
                    <button type="button" class="btn btn-primary" style="width:100px" onclick="submit_form()">ทำรายการต่อ</button>
                    <button type="button" style="width:100px" class="btn btn-danger" onclick="close_modal('check_term_of_loan_result_modal')">ยกเลิก</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="check_term_of_loan_before_result_modal" role="dialog">
    <div class="modal-dialog modal-dialog-file">
        <div class="modal-content data_modal">
            <div class="modal-header modal-header-confirmSave">
                <button type="button" class="close" onclick="close_modal('check_term_of_loan_before_result_modal')">&times;</button>
                <h2 class="modal-title" id="type_name">ตรวจสอบเงื่อนไขการกู้เงิน</h2>
            </div>
            <div class="modal-body">
                <div id="check_term_of_loan_before_result"></div>
                <div class="center">
                    <button type="button" class="btn btn-primary" style="width:100px" onclick="open_modal('normal_loan')">ทำรายการต่อ</button>
                    <button type="button" style="width:100px" class="btn btn-danger" onclick="close_modal('check_term_of_loan_before_result_modal')">ยกเลิก</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="update_salary_modal" role="dialog">
    <div class="modal-dialog modal-dialog-file">
        <div class="modal-content data_modal">
            <div class="modal-header modal-header-confirmSave">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">อัพเดทรายได้สมาชิก</h3>
            </div>
            <div class="modal-body">
                <div class="row m-b-1">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label col-sm-4">เงินเดือนค่าจ้าง</label>
                            <div class="col-sm-4">
                                <input type="text" id="update_salary" class="form-control" value="<?php echo number_format($salary); ?>" onkeyup="format_the_number_decimal(this)">
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if(isset($income) && sizeof($income)) {
                    foreach ($income as $key => $value) {
                        ?>
                        <div class="row m-b-1">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4"><?= $value['income_name'] ?></label>
                                    <div class="col-sm-4">
                                        <input type="text" id="income_<?= $value['id'] ?>" class="form-control income"
                                               value="<?php echo number_format($value['income_value']); ?>"
                                               onkeyup="format_the_number_decimal(this)" data-key="<?= $value['id'] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
                <div class="row m-b-1">
                    <div class="col-sm-12">
                        <div class="form-group" style="text-align:center">
                            <input type="button" class="btn btn-primary" value="บันทึก" onclick="update_salary();">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$v = date('YmdHis');
$link = array(
    'src' => PROJECTJSPATH.'assets/js/loan_contract.js?v='.$v,
    'type' => 'text/javascript'
);
echo script_tag($link);
$link = array(
    'src' => PROJECTJSPATH.'assets/js/validation.js?v='.$v,
    'type' => 'text/javascript'
);
echo script_tag($link);
?>
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
