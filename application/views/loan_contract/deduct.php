<style type="text/css">
    .deduct-list{
        padding-top: 25px;
    }
</style>
<div class="deduct-list">
    <?php if(isset($loan_deduct_list) && sizeof($loan_deduct_list) >= 1){ ?>
        <?php
        $j = 1;
        for ($i = 0; $i < round(count($loan_deduct_list) / 2); $i++) {
            ?>
            <div class="g24-col-sm-24 modal_data_input">
                <?php if(isset($loan_deduct_list_odd) && $loan_deduct_list_odd[$i]['loan_deduct_list'] != ''){ ?>
                    <?php if($loan_deduct_list_odd[$i]['loan_deduct_list_code'] != "deduct_insurance"){ ?>
                        <label class="g24-col-sm-4 control-label" <?php echo $loan_deduct_list_odd[$i]['loan_deduct_show'] <> 1 ? ' style="display: none" ' : ''; ?>><?php echo $j++ . ". " . $loan_deduct_list_odd[$i]['loan_deduct_list']; ?></label>
                        <div class="g24-col-sm-5" <?php echo $loan_deduct_list_odd[$i]['loan_deduct_show'] <> 1 ? ' style="display: none" ' : ''; ?>>
                            <div class="form-group">
                                <input class="form-control loan_deduct text-right" type="text" name="data[loan_deduct][<?php echo $loan_deduct_list_odd[$i]['loan_deduct_list_code']; ?>]"
                                       id="<?php echo $loan_deduct_list_odd[$i]['loan_deduct_list_code']; ?>" value="0.00" onkeyup="format_the_number_decimal(this);" onblur="cal_estimate_money()">
                            </div>
                        </div>
                    <?php }else{ ?>
                        <label class="g24-col-sm-4 control-label" for="deduct_insurance"><?php echo $j++ . ". " . $loan_deduct_list_odd[$i]['loan_deduct_list']; ?></label>
                        <div class="g24-col-sm-2">
                            <div class="form-group">
                                <input class="form-control loan_deduct text-right" type="text" name="data[loan_deduct][deduct_insurance]" id="deduct_insurance" value="0.00"  onkeyup="format_the_number_decimal(this);" onblur="cal_estimate_money()"/>
                            </div>
                        </div>
                        <label class="g24-col-sm-2 text-center" style="margin: 0 -15px">ทุนเอาประกัน</label>
                        <div class="g24-col-sm-4">
                            <div class="form-group">
                                <input class="form-control text-right" type="text" value="0.00" name="data[coop_left_insurance]" id="left_insurance" onkeyup="format_the_number_decimal(this);">
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
                <?php if (isset($loan_deduct_list_even) && $loan_deduct_list_even[$i]['loan_deduct_list'] != '') { ?>
                    <?php if($loan_deduct_list_even[$i]['loan_deduct_list_code'] != "deduct_insurance"){ ?>
                        <label class="g24-col-sm-4 control-label" <?php echo $loan_deduct_list_even[$i]['loan_deduct_show'] <> 1 ? ' style="display: none" ' : ''; ?>><?php echo $j++ . ". " . $loan_deduct_list_even[$i]['loan_deduct_list']; ?></label>
                        <div class="g24-col-sm-5" <?php echo $loan_deduct_list_even[$i]['loan_deduct_show'] <> 1 ? ' style="display: none" ' : ''; ?>>
                            <div class="form-group">
                                <input class="form-control loan_deduct text-right" type="text" name="data[loan_deduct][<?php echo $loan_deduct_list_even[$i]['loan_deduct_list_code']; ?>]"
                                       id="<?php echo $loan_deduct_list_even[$i]['loan_deduct_list_code']; ?>" value="0.00"  onkeyup="format_the_number_decimal(this);" onblur="cal_estimate_money()">
                            </div>
                        </div>
                    <?php }else{ ?>
                        <label class="g24-col-sm-4 control-label" for="deduct_insurance"><?php echo $j++ . ". " . $loan_deduct_list_even[$i]['loan_deduct_list']; ?></label>
                        <div class="g24-col-sm-2">
                            <div class="form-group">
                                <input class="form-control loan_deduct text-right" type="text" name="data[loan_deduct][deduct_insurance]" id="deduct_insurance" value="0.00"  onkeyup="format_the_number_decimal(this);" onblur="cal_estimate_money()"/>
                            </div>
                        </div>
                        <label class="g24-col-sm-2 text-center" style="margin: 0 -15px" for="left_insurance">ทุนเอาประกัน</label>
                        <div class="g24-col-sm-4">
                            <div class="form-group">
                                <input class="form-control text-right" type="text" value="0.00" name="data[coop_left_insurance]" id="left_insurance" onkeyup="format_the_number_decimal(this);">
                            </div>
                        </div>
                    <?php } ?>
                <?php }else{ ?><div class="g24-col-sm-12">&nbsp;</div>
                <?php } ?>
            </div>
            <?php
        }
    }
    ?>
</div>
