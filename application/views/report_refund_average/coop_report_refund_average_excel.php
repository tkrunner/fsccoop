<?php
    if(@$_GET['download']){
		header("Content-type: application/vnd.ms-excel;charset=utf-8;");
		header("Content-Disposition: attachment; filename=รายงานรับสมัครสมาชิก.xls"); 
		date_default_timezone_set('Asia/Bangkok');
    }
?>
<style>
			.header{
				font-family: AngsanaUPC, MS Sans Serif;
				font-size: 12px;
				font-weight: bold;
				text-align:center;
                color: #000;  
			}
			.header_h3{
				font-family: AngsanaUPC, MS Sans Serif;
				font-size: 16px;
				font-weight: bold;
				text-align:center;
                color: #000;  
			}
			.table {
				color: #000;
			}
            .footer{
                text-align: left;
                color: #000;
                padding-left: 1.8em;
            }
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
			.text-right{
				text-align: right;
			}
			.table_title{
				font-family: AngsanaUPC, MS Sans Serif;
				font-size: 14px;
				font-weight: bold;
				text-align:center;
                color: #000;  
			}
			.table_title_right{
				font-family: AngsanaUPC, MS Sans Serif;
				font-size: 16px;
				font-weight: bold;
				text-align:right;
                color: #000;
                background-color: #FFF;
			}
			.table_header_top{
				font-family: AngsanaUPC, MS Sans Serif;
				font-size: 14px;
				font-weight: bold;
				text-align:center;
				border-top: thin solid black;
				border-left: thin solid black;
				border-right: thin solid black;
                color: #000;
                background-color: #FFF;
			}
			.table_header_mid{
				font-family: AngsanaUPC, MS Sans Serif;
				font-size: 19px;
				font-weight: bold;
				text-align:center;
				border-left: thin solid black;
				border-right: thin solid black;
                color: #000;
                background-color: #FFF;
			}
			.table_header_bot{
				font-family: AngsanaUPC, MS Sans Serif;
				font-size: 19px;
				font-weight: bold;
				text-align:center;
				border-bottom: thin solid black;
				border-left: thin solid black;
				border-right: thin solid black;
                color: #000;
                background-color: #FFF;
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
				font-size: 14px;
				border: thin solid black;
                color: #000;
                background-color: #FFF;
			}
			.table_body_right{
				font-family: AngsanaUPC, MS Sans Serif;
				font-size: 21px;
				border: thin solid black;
				text-align:right;
                color: #000;
                background-color: #FFF;
			}
			table{
				/* border: 1px solid black; */
			}
			.title_header{
				color: #000;
				font-size: 21px;	
			}
		</style>
<?php 
    foreach($datas as $page=>$data) {
?>
		<div style="width: 1000px;" class="page-break">
			<div class="panel panel-body" style="padding-top:10px !important;height: 1420px;">
                <?php if($page == '1'){?>
                    <table style="width: 100%;">
						<tr>
						<?php if(@$_GET['download']!='excel'){ ?>
                            <td colspan="11" style="width:100px;vertical-align: top;" class="text-right">
                                <?php
                                    $get_param = '?';
                                    foreach(@$_GET as $key => $value){
                                            $get_param .= $key.'='.$value.'&';
                                    }
                                    $get_param = substr($get_param,0,-1);
                                ?>
                                <?php if(!@$_GET['download']){ ?>
                                    <a class="no_print" onclick="window.print();"><button class="btn btn-perview btn-after-input" type="button"><span class="icon icon-print" aria-hidden="true"></span></button></a>
                                    <a href="<?php echo base_url(PROJECTPATH.'/report_refund_average/coop_report_refund_average_excel'.$get_param."&download=excel"); ?>" class="no_print"><button class="btn btn-perview btn-after-input" type="button"><span>XLS</span></button></a>	
                                <?php } ?>
                            </td>
						<?php } ?>
						</tr>
                        <tr>
							<td colspan="8" width='400px' class="header"></td>
							<td class="text-right" width='200px' style="background-color: #FFF;">
								<span class="<?php echo(@$_GET['download']=='excel')?"header":"title_header";?>" style="text-align: right;">วันที่พิมพ์รายงาน:</span>
							</td>
                            <td class="text-right" width='100px' colspan="2" style="background-color: #FFF;mso-number-format:'@';">
                                <span class="<?php echo(@$_GET['download']=='excel')?"header":"title_header";?>" style="text-align: right;"><?php echo date("d/m/");echo date("Y",strtotime(date("Y/m/d")))+543 ; echo ' '.date("h:i:s");?></span>
							</td>
						</tr>
						<tr>
							<td colspan="8"></td>
							<td class="text-right" style="background-color: #FFF;">
                                <span class="<?php echo(@$_GET['download']=='excel')?"header":"title_header";?>" style="text-align: right;">หน้า:</span>
							</td>
                            <td class="text-right" colspan="2" style="background-color: #FFF;mso-number-format:'@';">
                                <span class="<?php echo(@$_GET['download']=='excel')?"header":"title_header";?>" style="text-align: right;"><?php echo $page.'/'.$page_all;?></span>
							</td>
						</tr>
						<tr>
							<td class="text-center" colspan="11" style="background-color: #FFF;">
							    <!-- <h3 class="<?php echo(@$_GET['download']=='excel')? "header":"title_header";?>"><?php echo @$_SESSION['COOP_NAME'];?></h3> -->
                                <span class="<?php echo(@$_GET['download']=='excel')?"header_h3":"title_header";?>">รายงานดอกเบี้นเงินกู้และเฉลี่ยคืนประจำปี <?php echo @$_GET['year'];?></span>
                                
                                
                            </td>
                        </tr>
                        <tr>
                        </tr> 
                    </table>
                <?php }else if(@$_GET['download']!='excel'){ ?>
					<table style="width: 100%;border: 1px;">
						<tr>
							<td colspan="8" width='800px'></td>
							<td class="text-right" style="background-color: #FFF;">
                                <span class="title_header" style="text-align: right;">หน้า:</span>
							</td>
                            <td class="text-right" colspan="2" style="background-color: #FFF;">
                                <span class="title_header" style="text-align: right;mso-number-format:'@';"><?php echo $page.'/'.$page_all;?></span>
							</td>
						</tr>
						
					</table>
				<?php } ?>
				<table class="table table-view table-center">
					<thead> 
						<tr>
							<th rowspan="1" class="<?php echo(@$_GET['download']=='excel')?"table_header_top":"title_view";?>" style="width: 40px;vertical-align: middle;">ลำดับ</th>
							<th rowspan="1" colspan="3" class="<?php echo(@$_GET['download']=='excel')?"table_header_top":"title_view";?>" style="width: 375px;vertical-align: middle;">ชื่อ-สกุล</th>
							<th rowspan="1" colspan="3" class="<?php echo(@$_GET['download']=='excel')?"table_header_top":"title_view";?>" style="width: 355px;vertical-align: middle;">สังกัด</th>
                            <th colspan="2" class="<?php echo(@$_GET['download']=='excel')?"table_header_top":"title_view";?>" style="width: 175px;vertical-align: middle;">จำนวนดอกเบี้ยเงินกู้</th>
                            <th colspan="2" class="<?php echo(@$_GET['download']=='excel')?"table_header_top":"title_view";?>" style="width: 175px;vertical-align: middle;">จำนวนเฉลี่ยคืน</th> 
						</tr> 
					</thead>
					<tbody>
					    <?php
                            $sum_share_month = 0;
                            foreach($data as $row) {
						?>
                        <tr> 
                            <td class="<?php echo(@$_GET['download']=='excel')?"table_body":"title_view";?>" style="text-align: center;"><?php echo ++$j;?></td>
                            <td colspan="3" class="<?php echo(@$_GET['download']=='excel')?"table_body":"title_view";?>" style="text-align: left;"><?php echo $row['prename_short'].$row['firstname_th'].' '.$row['lastname_th']; ?></td>
							<td colspan="3" class="<?php echo(@$_GET['download']=='excel')?"table_body":"title_view";?>" style="text-align: left;"><?php echo $row['mem_group_name']; ?></td>
                            <td colspan="2" class="<?php echo(@$_GET['download']=='excel')?"table_body":"title_view";?>" style="text-align: right;"><?php echo $row['interest_sum']; ?></td>
                            <td colspan="2" class="<?php echo(@$_GET['download']=='excel')?"table_body":"title_view";?>" style="text-align: right;"><?php echo $row['average_return_value_sum']; ?></td>				 
                        </tr>
                        <?php 
                            }
                        ?>					
					</tbody>
				</table>
			</div>
		</div>

<?php
    }
?>