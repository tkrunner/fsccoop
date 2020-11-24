<div class="layout-content">
    <div class="layout-content-body">
<style>
    .form-group { margin-bottom: 0; }
    .border1 { border: solid 1px #ccc; padding: 0 15px; }
    .mem_pic { float: right; width: 150px; }
    .mem_pic img { width: 100%; border: solid 1px #ccc; }
    .mem_pic button { display: block; width: 100%; }

    .hide_error{color : inherit;border-color : inherit;}

    .has-error{color : #d50000;border-color : #d50000;}

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .alert-danger {
        background-color: #F2DEDE;
        border-color: #e0b1b8;
        color: #B94A48;
    }
    .modal-backdrop.in{
        opacity: 0;
    }
    .modal-backdrop {
        position: relative;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1040;
        background-color: #000;
    }
    .modal.fade {
        z-index: 10000000 !important;
    }
	th{
		text-align:center;
	}
</style>
<h1 style="margin-bottom: 0">อนุมัติสมัครสมาชิก</h1>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padding-l-r-0">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 padding-l-r-0">
        <?php $this->load->view('breadcrumb'); ?>
    </div>
	<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 padding-l-r-0">
		<!--<button type="button" class="btn btn-danger btn-lg bt-del" style="margin-left:5px;" onclick="submit_form('4')">-->
		<button type="button" class="btn btn-danger btn-lg bt-del" style="margin-left:5px;" onclick="pre_not_approve()">
			ไม่อนุมัติ
        </button>
		 <!--<button type="button" class="btn btn-primary btn-lg bt-add" style="margin-left:5px;" onclick="submit_form('1')">-->
		 <button type="button" class="btn btn-primary btn-lg bt-add" style="margin-left:5px;" onclick="pre_approve()">
			อนุมัติ
        </button>
        <a class="" href="<?php echo base_url(PROJECTPATH.'/report_member_data/coop_report_wait_for_approval_member_excel?approve_page=1')?>">
            <button type="button" class="btn btn-primary btn-lg bt-add" style="margin-left:5px; float: right;">
                Export Excel
            </button>
        </a>
    </div>
</div>
<div class="row gutter-xs">
    <div class="col-xs-12 col-md-12">
        <div class="panel panel-body">
            <div class="bs-example" data-example-id="striped-table">
                <div id="tb_wrap">
                    <h3></h3>
                    <h3></h3>
                    <h3></h3>
                    <form action="" method="GET" id="form_search" autocomplete="off">
                        <div class="g24-col-sm-24">
							<div class="form-group g24-col-sm-24">					
								<label class="g24-col-sm-1 control-label" for="faction">ค้นหา</label>
								<div class="g24-col-sm-8">
									<div class="form-group">
										<input class="form-control" type="text" placeholder="ป้อนชื่อสกุล หรือ เลขที่คำร้อง" name="search_member" id="search_member" value="<?php echo !empty($_GET['search_member']) ? $_GET['search_member'] : ""?>">                       
									</div>
								</div>
								<div class="g24-col-sm-11">
									<div class="form-group">
										<button name="search_btn" id="search_btn" type="submit" class="btn btn-primary" style="width: 90px;" onclick="">
											<span>แสดง</span>
										</button>
									</div>
								</div>
							</div>						
						</div>
                    </form>
                    <div class="g24-col-sm-24">&nbsp</div>
					<form id="form1" action="<?php echo base_url(PROJECTPATH.'/manage_member_share/approve_register_save')?>" method="post">
						<input type="hidden" name="status_to" id="status_to">
						<input type="hidden" name="date_approve" id="date_approve">
						<table class="table table-striped">
							<thead>
							<tr>
								<th><input type="checkbox" id="check_all" onclick="check_it_all()"></th>
								<th>ลำดับ</th>
								<th>เลขที่คำร้อง</th>
								<th>ชื่อสกุล</th>
								<th>วันที่สมัคร</th>
								<th>สถานะ</th>
							</tr>
							</thead>
							<tbody id="table_data">
							<?php $member_status = array('1'=>'ปกติ','2'=>'ลาออก','3'=>'รออนุมัติ','4'=>'ไม่อนุมัติ')?>
							<?php foreach($row as $key => $value){ ?>
								<tr>
									<td align="center"><input class="check_box" type="checkbox" name="checkbox[]" value="<?php echo $value['id']; ?>"></td>
									<td align="center"><?php echo $i++; ?></td>
									<td align="center">
                                        <a href="<?php echo base_url(PROJECTPATH.'/manage_member_share/add/'.$value["id"]); ?>">
                                            <?php echo $value['mem_apply_id']; ?>
                                        </a>
                                    </td>
									<td><?php echo $value['prename_short'].$value['firstname_th']." ".$value['lastname_th']; ?></td>
									<td align="center"><?php echo $this->center_function->mydate2date($value['apply_date']); ?></td>
									<td align="center"><?php echo @$member_status[$value['member_status']]; ?></td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</form>
                </div>
            </div>
        </div>
        <div id="page_wrap">
            <?php echo $paging ?>
        </div>
    </div>
</div>
    </div>
</div>

<div id="date_approve_modal" tabindex="-1" role="dialog" class="modal fade">
    <div class="modal-dialog modal-sm" style="width:400px;">
        <div class="modal-content">
            <div class="modal-header modal-header-info">
                <h4 class="modal-title">กำหนดวันที่มีผลเป็นสมาชิก</h4>
            </div>
            <div class="modal-body">
                <div class="row m-b-1">
                    <div class="form-group">
                        <label for="money" class="control-label g24-col-sm-2"></label>
                        <div class="g24-col-sm-6 text-right" style="margin-bottom: 5px;padding-top: 5px;">
                            วันที่มีผล
                        </div>
                        <div class="g24-col-sm-14" style="margin-bottom: 5px;padding-top: 5px;">
                            <div class="input-with-icon g24-col-sm-24">
                                <div class="form-group">
                                    <input id="date_approve_input" name="date_approve_input" class="form-control m-b-1" style="padding-left: 50px;" type="text" data-date-language="th-th" value="<?php echo $this->center_function->mydate2date(date('Y-m-d')); ?>" title="กรุณาป้อน วันที่">
                                    <span class="icon icon-calendar input-icon m-f-1"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-info" id="submit_approve">ยืนยัน</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var base_url = $('#base_url').attr('class');
    function get_search_member(){
        $.ajax({
            type: "POST",
            url: base_url+'manage_member_share/get_search_member',
            data: {
                search_text : $("#search_text").val(),
				form_target : 'index'
            },
            success: function(msg) {
                $("#table_data").html(msg);
            }
        });
    }
	function check_it_all(){
		if($('#check_all').is(':checked')){
			$('.check_box').prop('checked',true);
		}else{
			$('.check_box').prop('checked',false);
		}
	}
	function submit_form(status_to){
		$('#status_to').val(status_to);
		$('#form1').submit();
	}

	function pre_not_approve() {
		//check_box
		var check = 0;
		if($('.check_box').is(':checked')){
			check++;
		}

		if(check > 0){
			submit_form('4');
		}else{
			swal('กรุณาเลือกรายการ','','warning');
		}
    }

	function pre_approve() {
		//check_box
		var check = 0;
		if($('.check_box').is(':checked')){
			check++;
		}

		if(check > 0){
			$('#date_approve_modal').modal("show");
		}else{
			swal('กรุณาเลือกรายการ','','warning');
		}
    }

	 $(document).ready(function() {
        $("#date_approve_input").datepicker({
            prevText: "ก่อนหน้า",
            nextText: "ถัดไป",
            currentText: "Today",
            changeMonth: true,
            changeYear: true,
            isBuddhist: true,
            monthNamesShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
            dayNamesMin: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
            constrainInput: true,
            dateFormat: "dd/mm/yy",
            yearRange: "c-50:c+10",
            autoclose: true,
        });

        $("#submit_approve").on('click', function() {
			$("#submit_approve").attr('disabled','disabled');
            var date_approve = $('#date_approve_input').val();
            $("#date_approve").val(date_approve);
            submit_form(1)
        });
    });
</script>