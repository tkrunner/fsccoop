<div class="layout-content">
    <div class="layout-content-body">
        <style>

            @keyframes spinner_in {
                to {transform: rotate(360deg);}
            }

            .spinner_in:before {
                content: '';
                box-sizing: border-box;
                position: absolute;
                top: 50%;
                left: 50%;
                width: 20px;
                height: 20px;
                margin-top: -10px;
                margin-left: -10px;
                border-radius: 50%;
                border-top: 4px solid #fcf9ff;
                border-right: 2px solid transparent;
                animation: spinner_in .6s linear infinite;
            }

            .btn-circle {
                display: inline-flex;
                margin: auto;
                justify-content: center;
                width: 30px;
                height: 30px;
                text-align: center;
                padding: 6px 0;
                font-size: 12px;
                line-height: 1.428571429;
                border-radius: 15px !important;
            }

            .input-with-icon .form-control {
                padding-left: 54px;
            }
        </style>
        <div class="row">
            <div class="form-group">
                <div class="col-sm-6">
                    <h1 class="title_top">อัพโหลดข้อมูลรายการกู้เงินฉุกเฉิน ATM </h1>
                    <?php $this->load->view('breadcrumb'); ?>
                </div>
                <div class="col-sm-6">
                    <div class="g24-col-sm-24" style="text-align:right;padding-right:0px;margin-right:0px;">
                    </div>
                </div>
            </div>
        </div>
        <div class="row gutter-xs">
            <div class="col-xs-12 col-md-12">
                <div class="panel panel-body" style="padding-top:15px !important;">
                    <div class="g24-col-sm-24" style="text-align:right;padding-right:0px;margin-right:0px;">
                        <form class="form-group" id="atm_file_upload" name="atm_file_upload" method="post" enctype="multipart/form-data" action="<?php echo base_url('/loan_atm/upload'); ?>">
                        <label class="g24-col-sm-6 control-label ">นำเข้าข้อมูลรายการกด ATM :</label>
                        <div class="g24-col-sm-6">
                            <span id="filename">ยังไม่ได้เลือกไฟล์</span>
                            <label class="fileContainer btn btn-info">
                                <span class="icon icon-paperclip"></span>
                                เลือกไฟล์
                                <input type="file" class="form-control" id="file_attach" name="file_attach[]" value="" multiple>
                            </label>
                        </div>
                        <div class="g24-col-md-3">
                            <button class="btn btn-primary" type="button" id="upload" onclick="call_upload()">
                                <span><i class="fa fa-cloud-upload"></i> อัพโหลด</span>
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-12">
                <div class="panel panel-body" style="padding-top:0px !important;">
                    <h3>ไฟล์อัพโหลดข้อมูล ATM</h3>
                    <table class="table table-bordered table-striped table-center">
                        <thead class="bg-primary">
                        <tr>
                            <th width="5%">ลำดับ</th>
                            <th width="20%">ชื่อไฟล์</th>
                            <th width="20%">วันที่</th>
                            <th width="10%">สถานะ</th>
                            <th width="15%">วันที่อัพโหลด</th>
                            <th width="15%">วันที่ทำรายการ</th>
                            <th width="25%">เครื่องมือ</th>
                        </tr>
                        </thead>
                        <tbody>
						<?php if (isset($data) && sizeof($data)) {
                            $no = 0;
                            foreach ($data as $key => $val){
                            ?>
                            <tr>
                                <td><?php echo ++$no;?></td>
                                <td><?php echo $val['file_name']; ?></td>
                                <td><?php echo $this->center_function->ConvertToThaiDate( $val['file_date'], 1); ?></td>
                                <td><?php echo $val['active_status'] == 0 ? 'รอประมวลผล' : 'ประมวลผลแล้ว' ?></td>
                                <td><?php echo $this->center_function->ConvertToThaiDate( $val['createdatetime'], 1); ?></td>
                                <td><?php echo $this->center_function->ConvertToThaiDate( $val['submit_date'], 1); ?></td>
                                <td>
                                    <button class="btn btn-circle btn-primary" <?php echo !empty($val['active_status']) ? '' : 'disabled="disabled"' ?> onclick="view_receive_file(<?php echo $val['id']; ?>)" title="แสดงข้อมูล">
										<i class="fa fa-eye" aria-hidden="true"></i>
									</button>
									<button class="btn btn-circle btn-primary" <?php echo $val['active_status'] == 0 ? '' : 'disabled="disabled"' ?> onclick="process(<?php echo $val['id']; ?>)" title="อ่านไฟล์ข้อมูล">
                                        <i class="fa fa-play" aria-hidden="true"></i>
                                    </button>
                                    <button class="btn btn-circle btn-primary" <?php echo $val['status'] <> 2 && $val['active_status'] == 1 ? '' : 'disabled="disabled"' ?> onclick="save(<?php echo $val['id']; ?>)" title="บันทึกข้อมูล">
                                        <i class="fa fa-save" aria-hidden="true"></i>
                                    </button>
                                    <button class="btn btn-circle btn-primary" <?php echo $val['status'] <> 2 && $val['active_status'] == 0 ? '' : 'disabled="disabled"' ?> onclick="delete_file(<?php echo $val['id']; ?>)" title="ลบไฟล์">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php }} else { ?>
                            <tr>
                                <td colspan="6" class="text-center"> ไม่พบข้อมูล</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_receive_check_list" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-info">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">ตรวจสอบข้อมูลรายการกู้เงินฉุกเฉิน ATM </h3>
            </div>
            <div class="modal-body">
                <table class="table" id="verify_list">
                    <thead>
                        <tr>
                            <th class="text-center">ลำดับ</th>
                            <th class="text-center">เลขทะเบียนสมาชิก</th>
                            <th class="text-center">เลขที่สัญญา</th>
                            <th class="text-center">ยอดเงินทำรายการ</th>
                            <th class="text-center">วันที่ทำรายการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4">ไม่พบรายการ</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer text-center">
                <button class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>
<?php
$v = date('YmdHis');
$link = array(
    'src' => PROJECTJSPATH . 'assets/js/atm_upload.js?v=' . $v,
    'type' => 'text/javascript'
);
echo script_tag($link);
?>
