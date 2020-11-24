<style type="text/css">
    .transfer{
        padding-top: 25px;
    }
    .content-bank{
        display: none;
    }
    .content-deposit{
        display: none;
    }
</style>
<div class="transfer">
    <div class="g24-col-sm-24">
        <div class="form-group g24-col-sm-12">
            <label class="g24-col-sm-8 control-label">รูปแบบการจ่ายเงิน</label>
            <div class="g24-col-sm-14">
                <select class="form-control" name="data[coop_loan][transfer_type]" id="transfer_type">
                    <option value="0">เงินสด</option>
                    <option value="1">โอนเงินบัญชีสหกรณ์</option>
                    <option value="2">โอนเงินบัญชีธนาคาร</option>
                    <option value="3">เช็ค</option>
                </select>
            </div>
        </div>
    </div>
    <div class="g24-col-sm-24 content-bank">
        <div class="form-group g24-col-sm-12">
            <label class="g24-col-sm-8 control-label" for="transfer_bank_id">ธนาคาร</label>
            <div class="g24-col-sm-14">
                <select name="data[coop_loan][transfer_bank_id]" id="transfer_bank_id" class="form-control">
						<option value="">เลือกธนาคาร</option>
						<?php foreach($rs_bank as $key => $value){ ?>
						<option value="<?php echo $value['bank_id']; ?>" <?php echo $content_bank['dividend_bank_id']==$value['bank_id'] ? "selected" : "" ?>><?php echo $value['bank_name']; ?></option>
					<?php } ?>
			    </select>	
            </div>
        </div>
    </div>
    <div class="g24-col-sm-24 content-bank">
        <div class="form-group g24-col-sm-12">
            <label class="g24-col-sm-8 control-label" for="transfer_bank_account_id">เลขที่บัญชี</label>
            <div class="g24-col-sm-14">
                <input type="text" name="data[coop_loan][transfer_bank_account_id]" id="transfer_bank_account_id" class="form-control" value="<?=@$content_bank['dividend_acc_num']?>">
            </div>
        </div>
    </div>
    <div class="g24-col-sm-24 content-deposit">
        <div class="form-group g24-col-sm-12">
            <label class="g24-col-sm-8 control-label" for="account_id"></label>
            <div class="g24-col-sm-14">
                <input class="form-control" id="account_id" name="account_id" type="text" value="">
            </div>
        </div>
    </div>
</div>