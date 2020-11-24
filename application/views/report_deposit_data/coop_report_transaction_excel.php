<?php 
header("Content-type: application/vnd.ms-excel;charset=utf-8;");
header("Content-Disposition: attachment; filename=รายงานการทำรายการ ประจำวัน.xls"); 
date_default_timezone_set('Asia/Bangkok');
?>
<?php
if(@$_GET['start_date']){
	$start_date_arr = explode('/',@$_GET['start_date']);
	$start_day = $start_date_arr[0];
	$start_month = $start_date_arr[1];
	$start_year = $start_date_arr[2];
	$start_year -= 543;
	$start_date = $start_year.'-'.$start_month.'-'.$start_day;
}

if(@$_GET['end_date']){
	$end_date_arr = explode('/',@$_GET['end_date']);
	$end_day = $end_date_arr[0];
	$end_month = $end_date_arr[1];
	$end_year = $end_date_arr[2];
	$end_year -= 543;
	$end_date = $end_year.'-'.$end_month.'-'.$end_day;
}		
		
//class="page-break"
//
$last_runno = 0;
$all_withdrawal = 0;
$all_deposit = 0;
$all_balance = 0;		

	?>
		
		<div>
                <table style="width: 100%;" style="background-color: #FFF !important;">
                    <tr>
						<td style="width:100px;vertical-align: top;">
							
						</td>
						<td class="text-center" colspan="8">
                             <?php
                                if(@$_GET['excel']==""){
                                    ?>
                                        <img src="<?php echo base_url(PROJECTPATH.'/assets/images/coop_profile/'.$_SESSION['COOP_IMG']); ?>" alt="Logo" style="height: 80px;" />	
                                    <?php
                                }
                             ?>
							 <h3 class="title_view"><?php echo @$_SESSION['COOP_NAME'];?></h3>
							 <h3 class="title_view">รายงานการทำรายการ ประจำวัน</h3>
							 <h3 class="title_view">
								<?php echo (@$_GET['type_id']!='all') ? " ประเภทบัญชี ".@$type_deposit[@$_GET['type_id']] : ""?>
							</h3>
							 <h3 class="title_view">
								<?php 
									echo " วันที่ ".$this->center_function->ConvertToThaiDate($start_date);
									echo (@$_GET['start_date'] == @$_GET['end_date'])?"":"  ถึง  ".$this->center_function->ConvertToThaiDate($end_date);
								?>
							</h3>
						 </td>
						 <td style="width:100px;vertical-align: top;" class="text-right">
							<a class="no_print" onclick="window.print();"><button class="btn btn-perview btn-after-input" type="button"><span class="icon icon-print" aria-hidden="true"></span></button></a>
						</td>
					</tr> 
				</table>

			<div class="panel-body" style="padding-top:10px !important;min-height: 1200px;">

			
				<table class="table table-view-2 table-center" style="background-color: #FFF !important;" border="1">
					<thead> 
						<tr>
							<th style="width: 40px;vertical-align: middle;">ลำดับ</th>
							<th style="width: 100px;vertical-align: middle;">วันที่ทำรายการ</th>
							<th style="width: 60px;vertical-align: middle;">เวลาที่ทำรายการ</th>
							<th style="width: 100px;vertical-align: middle;">หมายเลขบัญชี</th>
							<th style="width: 180px;vertical-align: middle;">ชื่อบัญชี</th>
							<th style="width: 70px;vertical-align: middle;">รายการ</th>
							<th style="width: 80px;vertical-align: middle;">ฝาก</th>
							<th style="width: 80px;vertical-align: middle;">ถอน</th>
							<th style="width: 80px;vertical-align: middle;">คงเหลือ</th>
							<th style="vertical-align: middle;">ผู้บันทึก</th>
						</tr>  
					</thead>
					<tbody>
					<?php 
                    if(!empty($data)){
                    foreach(@$data AS $page=>$data_row){
						$runno = $last_runno;
						$total_transaction_withdrawal = 0;
						$total_transaction_deposit = 0;
						$total_transaction_balance = 0;
						if(!empty($data_row)){
							foreach(@$data_row as $key => $row){
								$runno++;
								$total_transaction_withdrawal += @$row['transaction_withdrawal'];
								$total_transaction_deposit += @$row['transaction_deposit'];
								$total_transaction_balance += @$row['transaction_balance'];
					?>
							<tr> 
							  <td style="text-align: center;vertical-align: top;"><?php echo @$runno; ?></td>
							  <td style="text-align: center;vertical-align: top;"><?php echo (@$row['transaction_time'])?$this->center_function->ConvertToThaiDate(@$row['transaction_time'],1,0):"";?></td>
							  <td style="text-align: center;vertical-align: top;"><?php echo (@$row['transaction_time'])?date(" H:i" , strtotime(@$row['transaction_time'])):""?></td>						 
							  <td style="text-align: center;vertical-align: top;"><?php echo @$row['account_id'];?></td>						 
							  <td style="text-align: left;vertical-align: top;"><?php echo @$row['account_name'];?></td>	
							  <td style="text-align: center;vertical-align: top;"><?php echo @$row['transaction_list'];?></td> 					 
							  <td style="text-align: right;vertical-align: top;"><?php echo number_format($row['transaction_deposit'],2); ?></td> 					 
							  <td style="text-align: right;vertical-align: top;"><?php echo number_format($row['transaction_withdrawal'],2); ?></td> 					 
							  <td style="text-align: right;vertical-align: top;"><?php echo number_format($row['transaction_balance'],2); ?></td> 						 
							  <td style="text-align: center;vertical-align: top;">
								<?php 
									if($row['user_name']!=''){
										echo $row['user_name'];
									}else if($row['member_id_atm'] != ''){
										echo @$row['member_name_atm'];
									}else{
										echo "";
									}
								?>
							  </td> 						 
							</tr>										
					
					<?php									
							}
							$last_runno = $runno;
					?>

					<?php
						}
						
						$all_withdrawal += @$total_transaction_withdrawal;
						$all_deposit +=  @$total_transaction_deposit;
						$all_balance +=  @$total_transaction_balance;
					?>
						
					<?php	
						if(@$page == @$page_all){							
					?>
						   <tr class="foot-border"> 
							  <td style="text-align: center;" colspan="4">รวมทั้งหมด <?php echo @$num_rows;?> รายการ</td>					 
							  <td style="text-align: center;" colspan="2">จำนวนเงินทั้งหมด</td> 					 
							  <td style="text-align: right;"><?php echo number_format(@$all_deposit,2); ?></td> 						 
							  <td style="text-align: right;"><?php echo number_format(@$all_withdrawal,2); ?></td> 						 
							  <td style="text-align: right;"><?php echo number_format(@$all_balance,2); ?></td> 						 
							  <td style="text-align: center;">บาท</td> 						 
						  </tr>
					<?php } ?>	  
						
			</div>
		</div>
<?php 
	}
} 
?>
					</tbody>    
				</table>


<style>
				.num {
				  mso-number-format:General;
				}
				.text{
				  mso-number-format:"\@";/*force text*/ 
				}
				.text-center{
					text-align: center;
				}
				.text-left{
					text-align: left;
				}
				.table_title{
					font-family: AngsanaUPC, MS Sans Serif;
					font-size: 22px;
					font-weight: bold;
					text-align:center;
				}
				.table_title_right{
					font-family: AngsanaUPC, MS Sans Serif;
					font-size: 16px;
					font-weight: bold;
					text-align:right;
				}
				.table_header_top{
					font-family: AngsanaUPC, MS Sans Serif;
					font-size: 19px;
					font-weight: bold;
					text-align:center;
					border-top: thin solid black;
					border-left: thin solid black;
					border-right: thin solid black;
				}
				.table_header_mid{
					font-family: AngsanaUPC, MS Sans Serif;
					font-size: 19px;
					font-weight: bold;
					text-align:center;
					border-left: thin solid black;
					border-right: thin solid black;
				}
				.table_header_bot{
					font-family: AngsanaUPC, MS Sans Serif;
					font-size: 19px;
					font-weight: bold;
					text-align:center;
					border-bottom: thin solid black;
					border-left: thin solid black;
					border-right: thin solid black;
				}
				.table_header_bot2{
					font-family: AngsanaUPC, MS Sans Serif;
					font-size: 19px;
					font-weight: bold;
					text-align:center;
					border: thin solid black;
				}
				.table_body{
					font-family: AngsanaUPC, MS Sans Serif;
					font-size: 21px;
					border: thin solid black;
				}
				.table_body_right{
					font-family: AngsanaUPC, MS Sans Serif;
					font-size: 21px;
					border: thin solid black;
					text-align:right;
				}
</style>

