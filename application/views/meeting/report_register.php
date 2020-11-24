<style>
	.table-view>thead, .table-view>thead>tr>td, .table-view>thead>tr>th {
		font-size: 14px;
	}
	.table-view-2>thead>tr>th{
	    border-top: 1px solid #000 !important;
		border-bottom: 1px solid #000 !important;
		font-size: 16px;
	}
	.table-view-2>tbody>tr>td{
	    border: 0px !important;
		/*font-family: upbean;
		font-size: 16px;*/
		font-family: Tahoma;
		font-size: 12px;
	}
	.border-bottom{
	    border-bottom: 1px solid #000 !important;
		font-weight: bold;
	}

	.foot-border{
	    border-top: 1px solid #000 !important;
		border-bottom: double !important;
		font-weight: bold;
	}
</style>
<?php
//class="page-break"
//
$last_runno = 0;
$all_withdrawal = 0;
$all_deposit = 0;
$all_balance = 0;
// if(!empty($data)){
// 	foreach(@$data AS $page=>$data_row){
	?>

		<div style="width: 1000px;" class="page-break">
			<div class="panel panel-body" style="padding-top:10px !important;min-height: 1200px;">
				<table style="width: 100%;">
				<?php

					// if(@$page == 1){
				?>
					<tr>
						<td style="width:150px;vertical-align: top;">

						</td>
						<td class="text-center">
							<img src="<?php echo base_url(PROJECTPATH.'/assets/images/coop_profile/'.$_SESSION['COOP_IMG']); ?>" alt="Logo" style="height: 80px;" />
							<h3 class="title_view"><?php echo @$_SESSION['COOP_NAME'];?></h3>
							<h3 class="title_view"><?php echo $row_meeting['meeting_name']; ?></h3>
							<h3 class="title_view">
								<?php
									echo "วันที่ ".$this->center_function->ConvertToThaiDate($row_meeting['meeting_date'],1,0);
								?>
							</h3>
						 </td>
						 <td style="width:150px;vertical-align: top;" class="text-right">
							<a class="no_print" onclick="window.print();"><button class="btn btn-perview btn-after-input" type="button"><span class="icon icon-print" aria-hidden="true"></span></button></a>
							<?php
								$get_param = '?';
								foreach(@$_GET as $key => $value){
									//if($key != 'month' && $key != 'year' && $value != ''){
										$get_param .= $key.'='.$value.'&';
									//}
								}
								$get_param = substr($get_param,0,-1);
							?>
							<a class="no_print"  target="_blank" href="<?php echo base_url('/meeting/report_register'.$get_param.'&type=excel'); ?>">
								<button class="btn btn-perview btn-after-input" type="button"><span class="icon icon icon-file-excel-o" aria-hidden="true"></span></button>
							</a>
							<a class="no_print"  target="_blank" href="<?php echo base_url('/meeting/report_register'.$get_param.'&type=pdf'); ?>">
								<button class="btn btn-perview btn-after-input" type="button"><span class="icon icon icon-file-pdf-o" aria-hidden="true"></span></button>
							</a>
						</td>
					</tr>
				<?php
					// }else{
				?>
					<!-- <tr>
						<td colspan="3" style="text-align: left;">&nbsp;</td>
					</tr> -->
				<?php
					// }
				?>

					<tr>
						<td colspan="3" style="text-align: left;">
							<span class="title_view">วันที่ <?php echo $this->center_function->ConvertToThaiDate(@date('Y-m-d'),1,0);?></span>
							<span class="title_view">เวลา <?php echo date('H:i:s');?>111</span>
						</td>
					</tr>
				</table>

				<table class="table table-view table-center">
					<thead>
						<tr>
							<th style="width: 60px;vertical-align: middle;">ลำดับ</th>
							<th style="width: 90px;vertical-align: middle;">รหัสสมาชิก</th>
							<th style="vertical-align: middle;">ชื่อ - นามสกุล</th>
							<th style="width: 150px;vertical-align: middle;">วันที่/เวลาลงทะเบียน</th>
							<?php /* <th style="width: 150px;vertical-align: middle;">การลงทะเบียน</th> */ ?>
							<th style="width: 150px;vertical-align: middle;">เลขหางบัตร</th>
							<th style="width: 80px;vertical-align: middle;">ของที่ระลึก</th>
						</tr>
					</thead>
					<tbody>

					<?php
						/*$total_count = 0;
						$total_balance = 0;*/
						if(!empty($data)){
							foreach(@$data as $key => $row){
								$regis_type = '';
								if( $row['facescan_id'] ) $regis_type = 'ใบหน้า';
								elseif( $row['id_card_data'] ) $regis_type = 'บัตรประชาชน';
								else $regis_type = 'เลขสมาชิก';
								$fullname = "{$row['firstname_th']} {$row['lastname_th']}";

					?>
							<tr>
							  <td style="text-align: center;vertical-align: top;"><?php echo number_format($key + 1);?></td>
							  <td style="text-align: center;vertical-align: top;"><?php echo $row['member_id'];?></td>
							  <td style="text-align: left;vertical-align: top;"><?php echo $row['firstname_th'].' '.$row['lastname_th'];?></td>
							  <td style="text-align: center;vertical-align: top;"><?php echo $this->center_function->ConvertToThaiDate($row['create_time']);?></td>
							  <?php /* <td style="text-align: center;vertical-align: top;"><?php echo $regis_type; ?></td> */ ?>
							  <td style="text-align: center;vertical-align: top;"><?php echo $row['card_tail_number']; ?></td>
							  <td style="text-align: center;vertical-align: top;"><?php echo $row['is_gift'] ? '/' : '' ; ?></td>
							</tr>

					<?php
								/*$total_count += $row['account_count'];
								$total_balance += $row['transaction_balance'];*/
							}
						}
					?>

					<?php /*<tr style="font-weight: bold;">
						<td colspan="2" style="text-align: center;vertical-align: top;">รวม</td>
						<td style="text-align: center;vertical-align: top;"><?php echo number_format($total_count); ?></td>
						<td style="text-align: right;vertical-align: top;"><?php echo number_format($total_balance, 2); ?></td>
					</tr>*/ ?>
					</tbody>
				</table>
			</div>
		</div>
<?php
// 	}
// }
?>